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

    public function setValue($key, $value) {
        static::$cache[$key] = $value;
    }
}
