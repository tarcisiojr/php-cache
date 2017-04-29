<?php

namespace PHP\Cache\API;

interface CacheStrategy {

    /**
     * @return mixed Initial state.
     */
    public function init();

    /**
     * @param mixed $state Last state.
     *
     * @return bool
     */
    public function update($state);

    public function getValue($key);

    public function setValue($key, $value, $ttl = 0);

    public function getHash($object, $method, $args);
}
