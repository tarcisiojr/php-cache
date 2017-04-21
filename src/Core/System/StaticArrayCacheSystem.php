<?php
/**
 * Created by PhpStorm.
 * User: tarcisio
 * Date: 20/04/17
 * Time: 23:21
 */

namespace PHP\Cache\Core\System;

use PHP\Cache\API\CacheSystem;

class StaticArrayCacheSystem implements CacheSystem {
    private static $cache = [];

    public function getValue($key) {
        return isset(static::$cache[$key]) ? static::$cache[$key] : null;
    }

    public function setValue($key, $value, $ttl = 0) {
        static::$cache[$key] = $value;
    }

    public function delete($key) {
        unset(static::$cache[$key]);
    }

    public function clear() {
        static::$cache = [];
    }
}
