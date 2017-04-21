<?php
/**
 * Created by PhpStorm.
 * User: tarcisio
 * Date: 19/04/17
 * Time: 21:40
 */

namespace PHP\Cache\API;

interface CacheSystem {

    public function getValue($key);

    public function setValue($key, $value);
}
