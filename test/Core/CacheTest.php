<?php

namespace PHP\Cache\Core;

use PHP\Cache\Core\ApplicationAspectKernel;
use PHP\Cache\Core\Cache;
use PHP\Cache\Core\TesteCache;

class CacheTest extends \PHPUnit_Framework_TestCase {

    public function testCacheOnce() {
        $initialValue = $this->dummyMethod();
        $this->assertNotEquals($initialValue, $this->dummyMethod("a"), 'Valor retornado não foi idêntico');
        $this->assertEquals($initialValue, $this->dummyMethod(), 'Valor retornado não foi idêntico');
        $this->assertEquals($initialValue, $this->dummyMethod(), 'Valor retornado não foi idêntico');
    }

    private function dummyMethod($a = "a", $b = "b") {
        return Cache::create(function () {
            return rand(1, 100);
        })->once()->statefull()->scope(false)->ttl(10)->get()
            ;
    }
}
