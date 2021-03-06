<?php

namespace PHP\Cache\Core\Strategy;

use PHP\Cache\API\CacheStrategy;
use PHP\Cache\Core\System\StaticArrayCacheSystem;

class DefaultCacheStrategy implements CacheStrategy {

    private $cache;

    public function __construct() {
        $this->cache = new StaticArrayCacheSystem();
    }

    public function init() {
        return [self::class . '::class' => true];
    }

    public function update($state) {
        return [$state, false];
    }

    public function getValue($key) {
        return $this->cache->getValue($key);
    }

    public function setValue($key, $value, $ttl = 0) {
        $this->cache->setValue($key, $value, $ttl);
    }

    public function getHash($object, $methodName, $args) {
        $normalizedArguments = array_map(function ($argument) {
            return is_object($argument) ? spl_object_hash($argument) : $argument;
        }, $args);

        $hashObject = is_object($object) ? spl_object_hash($object) : $object . "#";

        return md5($hashObject . '#' . $methodName . '#' . serialize($normalizedArguments));
    }
}
