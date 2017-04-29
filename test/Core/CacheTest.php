<?php

namespace Test\PHP\Cache\Core;

use PHP\Cache\Core\Cache;

class CacheTest extends \PHPUnit_Framework_TestCase
{

    public function testCacheMethod()
    {
        $initialValue = $this->dummyMethod();
        $this->assertNotEquals($initialValue, $this->dummyMethod("b"), 'Valor retornado foi idêntico');
        $this->assertEquals($initialValue, $this->dummyMethod(), 'Valor retornado não foi idêntico');
        $this->assertEquals($initialValue, $this->dummyMethod(), 'Valor retornado não foi idêntico');
    }

    public function testCacheClass()
    {
        $class = new DummyClass();
        $initialValue = $class->dummyMethod();
        $this->assertNotEquals($initialValue, $class->dummyMethod("b"), 'Valor retornado foi idêntico');
        $this->assertEquals($initialValue, $class->dummyMethod(), 'Valor retornado não foi idêntico');
        $this->assertEquals($initialValue, $class->dummyMethod(), 'Valor retornado não foi idêntico');
    }

    private function dummyMethod($a = "a", $b = "b")
    {
        return Cache::create(function () use ($a, $b) {
            $curDate = date("Y-m-d H:i:s");
            sleep(1);
            return $curDate;
        })
            ->once()
            ->statefull()
            ->scope(false)
            ->ttl(10)
            ->get();
    }
}
