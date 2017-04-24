<?php

namespace PHP\Cache\Core\System;

use PHP\Cache\API\CacheSystem;

class FileCacheSystem implements CacheSystem {

    private static $cache;

    private static $lastModified;

    private $fileName;

    private $fileLock;

    public function __construct($fileName = null) {
        if ($fileName == null) {
            $this->fileName = sys_get_temp_dir() . '/cache.json';

        } else {
            $this->fileName = $fileName;
        }

        $this->fileLock = fopen($this->fileName . '.lock', 'w+');
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
                return $cachedInfo['value'];
            }
        } finally {
            $this->unlock();
        }

        return null;
    }

    public function setValue($key, $value, $ttl = 0) {
        try {
            $this->lock(false);
            $this->init();

            static::$cache[$key] = [
                'ttl'   => $ttl == 0 ? 'forever' : date("Y-m-d H:i:s", strtotime('now + ' . $ttl . ' seconds')),
                'value' => $value,
            ];

            $this->update();
        } finally {
            $this->unlock();
        }
    }

    public function delete($key) {
        unset(static::$cache[$key]);
    }

    public function clear() {
        static::$cache = [];
    }

    private function lock($share = false) {
        flock($this->fileLock, $share ? LOCK_SH : LOCK_EX);
    }

    private function unlock() {
        flock($this->fileLock, LOCK_UN);
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
}
