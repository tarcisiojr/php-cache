<?php
/**
 * Created by PhpStorm.
 * User: tarcisio
 * Date: 20/04/17
 * Time: 22:59
 */

namespace PHP\Cache\Core\Strategy;

use PHP\Cache\API\CacheStrategy;
use PHP\Cache\Core\System\FileCacheSystem;
use PHP\Cache\Core\System\StaticArrayCacheSystem;

class ExactlyCacheStrategy implements CacheStrategy {

    private static $cache;

    private $times;

    public function __construct($times = 0) {
        $this->times = $times;
    }

    private static function getCache() {
        if (static::$cache == null) {
            static::$cache = new FileCacheSystem();//new StaticArrayCacheSystem();
        }

        return static::$cache;
    }

    public function init() {
        return ['count' => 1];
    }

    public function update($state, $value = null) {
        if ($this->times > 0 && $state['count'] >= $this->times) {
            return [ $this->init(), true ];
        }

        $state['count']++;

        return [ $state, false ];
    }

    public function getValue($key) {
        return static::getCache()->getValue($key);
    }

    public function setValue($key, $value) {
        static::getCache()->setValue($key, $value);
    }

    public function getHash($object, $methodName, $args) {
        $normalizedArguments = array_map(function($argument) {
            return is_object($argument) ? spl_object_hash($argument) : $argument;
        }, $args);

        $hashObject = $object ? spl_object_hash($object) : "#";

        return md5($hashObject . '#' . $methodName . '#' . serialize($normalizedArguments));
    }
}
