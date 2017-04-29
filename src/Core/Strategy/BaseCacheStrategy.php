<?php

namespace PHP\Cache\Core\Strategy;

use PHP\Cache\API\CacheStrategy;

class BaseCacheStrategy implements CacheStrategy {

    private $strategy;

    public function __construct(CacheStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function init() {
        return $this->strategy->init();
    }

    public function update($state) {
        list($newState, $update) = $this->strategy->update($state);

        return [array_merge($state, $newState), $update];
    }

    public function getValue($key) {
        return $this->strategy->getValue($key);
    }

    public function setValue($key, $value, $ttl = 0) {
        $this->strategy->setValue($key, $value, $ttl);
    }

    public function getHash($object, $methodName, $args) {
        return $this->strategy->getHash($object, $methodName, $args);
    }
}
