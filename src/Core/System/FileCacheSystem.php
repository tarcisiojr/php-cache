<?php
/**
 * Created by PhpStorm.
 * User: tarcisio
 * Date: 20/04/17
 * Time: 23:21
 */

namespace PHP\Cache\Core\System;

use PHP\Cache\API\CacheSystem;

class FileCacheSystem implements CacheSystem {

    private $fileName;

    private static $cache;

    public function __construct($fileName = null) {
        if ($fileName == null) {
            $this->fileName = sys_get_temp_dir() . '/cache.json';

        } else {
            $this->fileName = $fileName;
        }
    }

    public function getValue($key) {
        $this->init();

        $cachedInfo = isset(static::$cache[$key]) ? static::$cache[$key] : null;

        if (isset($cachedInfo['ttl'], $cachedInfo['value'])
            && ($cachedInfo['ttl'] == 'forever'
                || strtotime($cachedInfo['ttl']) > strtotime('now'))
        ) {
            return $cachedInfo['value'];
        }

        return null;
    }

    public function setValue($key, $value, $ttl = 0) {
        $this->init();

        static::$cache[$key] = [
            'ttl' => $ttl == 0 ? 'forever' : date("Y-m-d H:i:s", strtotime('now + ' . $ttl . ' seconds')),
            'value' => $value
        ];

        $this->update();
    }

    public function delete($key) {
        unset(static::$cache[$key]);
    }

    public function clear() {
        static::$cache = [];
    }

    private function init() {
        if (!static::$cache) {
            if (file_exists($this->fileName)) {
                static::$cache = json_decode(file_get_contents($this->fileName), true);
            }
        }
    }

    private function update() {
        file_put_contents($this->fileName, json_encode(static::$cache, JSON_PRETTY_PRINT));
    }

}
