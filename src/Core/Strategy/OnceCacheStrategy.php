<?php
/**
 * Created by PhpStorm.
 * User: tarcisio
 * Date: 20/04/17
 * Time: 22:59
 */

namespace PHP\Cache\Core\Strategy;

use PHP\Cache\API\CacheStrategy;
use PHP\Cache\Core\System\StaticArrayCacheSystem;

class OnceCacheStrategy implements CacheStrategy {
    private static $cache;

    private static function getCache() {
        if (static::$cache == null) {
            static::$cache = new StaticArrayCacheSystem();
        }

        return static::$cache;
    }

    public function init() {
        return [ 'count' => 0 ];
    }

    public function update($state, $value = null) {
        $state['count']++;

        return false;
    }

    public function getValue($key) {
        return static::getCache()->getValue($key);
    }

    public function setValue($state, $key, $value) {
        static::getCache()->setValue($key, $value);

        $state['count']++;

        return $state;
    }
}
