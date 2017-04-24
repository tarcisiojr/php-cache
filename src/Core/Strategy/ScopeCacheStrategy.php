<?php

namespace PHP\Cache\Core\Strategy;

use PHP\Cache\API\CacheStrategy;

class ScopeCacheStrategy extends BaseCacheStrategy {

    private $instance = true;

    public function __construct(CacheStrategy $strategy, $instance = true) {
        parent::__construct($strategy);

        $this->instance = $instance;
    }

    public function getHash($object, $methodName, $args) {
        if (!$this->instance && is_object($object)) {
            $object = get_class($object);
        }

        return parent::getHash($object, $methodName, $args);
    }
}
