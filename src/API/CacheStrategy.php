<?php
/**
 * Created by PhpStorm.
 * User: tarcisio
 * Date: 20/04/17
 * Time: 22:33
 */

namespace PHP\Cache\API;

interface CacheStrategy {

    /**
     * @return mixed Initial state.
     */
    public function init();

    /**
     * @param $state Last state.
     *
     * @return bool
     */
    public function update($state);

    public function getValue($key);

    public function setValue($key, $value, $ttl = 0);

    public function getHash($object, $method, $args);
}
