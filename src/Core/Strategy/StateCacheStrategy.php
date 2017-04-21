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

class StateCacheStrategy implements CacheStrategy {

    private $strategy;

    public function __construct(CacheStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function init() {
        return $this->strategy->init();
    }

    public function update($state, $value = null) {
        return $this->strategy->update($state, $value);
    }

    public function getValue($key) {
        return $this->strategy->getValue($key);
    }

    public function setValue($key, $value) {
        $this->strategy->setValue($key, $value);
    }

    public function getHash($object, $methodName, $args) {
        return $this->strategy->getHash($object, $methodName, $args);
    }
}
