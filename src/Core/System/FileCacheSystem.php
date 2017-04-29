<?php

namespace PHP\Cache\Core\System;

use PHP\Cache\API\CacheSystem;

class FileCacheSystem implements CacheSystem {

    private static $cache;

    private static $lastModified;

    private $fileName;

    private $lockFileName;

    private $lockFile;

    public function __construct($fileName = null) {
        if ($fileName == null) {
            $this->fileName = sys_get_temp_dir() . '/cache.json';

        } else {
            $this->fileName = $fileName;
        }

        $this->lockFileName = $this->fileName . '.lock';

        $this->initLockFile();
    }

    public function getValue($key) {
        try {
            $this->lock(true);

            $this->init();

            $cachedInfo = isset(static::$cache[$key]) ? static::$cache[$key] : null;

            if (isset($cachedInfo['ttl'], $cachedInfo['value'])
                && ($cachedInfo['ttl'] == 'forever'
                    || strtotime($cachedInfo['ttl']) > strtotime('now'))
            ) {
                return unserialize($cachedInfo['value']);
            }
        } finally {
            $this->unlock();
        }

        return null;
    }

    public function setValue($key, $value, $ttl = 0) {
        try {
            if (is_object($value) && !($value instanceof \Serializable)) {
                $value = null;
            }

            $this->lock(false);
            $this->init();

            static::$cache[$key] = [
                'ttl'   => $ttl == 0 ? 'forever' : date("Y-m-d H:i:s", strtotime('now + ' . $ttl . ' seconds')),
                'value' => serialize($value),
            ];

            $this->update();
        } finally {
            $this->unlock();
        }
    }

    public function delete($key) {
        try {
            $this->lock();

            unset(static::$cache[$key]);

            $this->update();

        } finally {
            $this->unlock();
        }
    }

    public function clear() {
        static::$cache = [];

        if (file_exists($this->fileName)) {
            unlink($this->fileName);
        }

        if (file_exists($this->lockFileName)) {
            $this->releaseLockFile();

            unlink($this->lockFileName);

            $this->initLockFile();
        }
    }

    private function lock($share = false) {
        flock($this->lockFile, $share ? LOCK_SH : LOCK_EX);
    }

    private function unlock() {
        flock($this->lockFile, LOCK_UN);
    }

    private function init() {
        if (file_exists($this->fileName)) {
            $modified = filemtime($this->fileName);

            if (!static::$cache || $modified > static::$lastModified) {
                static::$lastModified = $modified;

                static::$cache = json_decode(file_get_contents($this->fileName), true);
            }
        } else {
            touch($this->fileName);
        }
    }

    private function update() {
        static::$lastModified = filemtime($this->fileName);

        file_put_contents($this->fileName, json_encode(static::$cache, JSON_PRETTY_PRINT));
    }

    public function isPersistent() {
        return true;
    }

    public function getFileName() {
        return $this->fileName;
    }

    public function getLockFileName() {
        return $this->lockFileName;
    }

    private function initLockFile() {
        $this->lockFile = fopen($this->lockFileName, 'w+');
    }

    private function releaseLockFile() {
        fclose($this->lockFile);
    }
}
