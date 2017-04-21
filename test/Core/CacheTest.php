<?php
/**
 * Created by PhpStorm.
 * User: tarcisio
 * Date: 19/04/17
 * Time: 21:42
 */

namespace PHP\Cache\Core\Test;

use PHP\Cache\Core\Cache;

class CacheTest extends \PHPUnit_Framework_TestCase {

    public function testCacheOnce() {
        //echo " > ".$this->dummyMethod();
        //echo " > ".$this->dummyMethod("a");
        //echo " > ".$this->dummyMethod();


        echo " | > " . $this->dummyMethod2();
        echo " | > " . $this->dummyMethod2();
        echo " | > " . $this->dummyMethod2();
        echo " | > " . $this->dummyMethod2();
        echo " | > " . $this->dummyMethod2();
    }

    private function dummyMethod($a = "a", $b = "b") {
        return Cache::create(function() {
            return rand(1, 100);
        })->once();
    }

    private function dummyMethod2() {
        return Cache::create(function() {
            return  rand(1, 100);
        })->times(2);
    }
}
