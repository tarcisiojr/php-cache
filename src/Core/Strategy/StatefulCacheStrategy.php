<?php

namespace PHP\Cache\Core\Strategy;

use PHP\Cache\API\CacheSystem;
use PHP\Cache\Core\System\FileCacheSystem;

class StatefulCacheStrategy extends BaseCacheStrategy {

    private static $cache;

    public static function setCacheSystem(CacheSystem $cacheSystem) {
        if (!$cacheSystem->isPersistent()) {
            throw new CacheSystemNotAllowedException('Only allowed persistent cache system.');
        }

        static::$cache = $cacheSystem;
    }

    /**
     * @return CacheSystem
     */
    private static function getCache() {
        if (static::$cache === null) {
            static::$cache = new FileCacheSystem();
        }

        return static::$cache;
    }

    public function getValue($key) {
        return static::getCache()
            ->getValue($key);
    }

    public function setValue($key, $value, $ttl = 0) {
        static::getCache()
            ->setValue($key, $value, $ttl);
    }
}
