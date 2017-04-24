<?php

namespace PHP\Cache\API;

interface CacheSystem {

    public function getValue($key);

    public function setValue($key, $value, $ttl = 0);

    public function delete($key);

    public function clear();

    public function isPersistent();
}
