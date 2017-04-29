<?php

namespace Test\PHP\Cache\Core\System;

use PHP\Cache\Core\System\StaticArrayCacheSystem;

class StaticArrayCacheSystemTest extends \PHPUnit_Framework_TestCase {


    public function setUp() {
        $sys = new StaticArrayCacheSystem();
        $sys->clear();
    }

    public function tearDown() {
    }

    public function testIsPersistente() {
        $ins = new StaticArrayCacheSystem();
        $this->assertFalse($ins->isPersistent());
    }

    public function testGetValue() {
        $sys = new StaticArrayCacheSystem();
        $sys->setValue('test1', 1234);
        $sys->setValue('test2', $this);

        $this->assertEquals(1234, $sys->getValue('test1'));
        $this->assertEquals($this, $sys->getValue('test2'));
    }

    public function testClear() {
        $sys = new StaticArrayCacheSystem();
        $sys->setValue('test1', 1234);
        $sys->clear();

        $this->assertNull($sys->getValue('test1'));
    }

    public function testDelete() {
        $sys = new StaticArrayCacheSystem();
        $sys->setValue('test1', 1234);
        $sys->setValue('test2', 1234);
        $sys->delete('test1');

        $this->assertNull($sys->getValue('test1'));
        $this->assertEquals(1234, $sys->getValue('test2'));
    }
}
