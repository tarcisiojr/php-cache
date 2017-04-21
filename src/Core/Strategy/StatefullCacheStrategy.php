<?php
/**
 * Created by PhpStorm.
 * User: tarcisio
 * Date: 20/04/17
 * Time: 22:59
 */

namespace PHP\Cache\Core\Strategy;

use PHP\Cache\Core\System\FileCacheSystem;

class StatefullCacheStrategy extends StateCacheStrategy {

    private static $cache;

    private static function getCache() {
        if (static::$cache == null) {
            static::$cache = new FileCacheSystem();
        }

        return static::$cache;
    }

    public function getValue($key) {
        return static::getCache()->getValue($key);
    }

    public function setValue($key, $value) {
        static::getCache()->setValue($key, $value);
    }
}
