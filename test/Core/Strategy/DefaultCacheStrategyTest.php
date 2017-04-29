<?php
/**
 * Created by PhpStorm.
 * User: tarcisio
 * Date: 28/04/17
 * Time: 21:28
 */

namespace PHP\Cache\Core\Strategy;

use PHP\Cache\API\CacheStrategy;
use PHP\Cache\Core\System\StaticArrayCacheSystem;

class DefaultCacheStrategyTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {
        $sys = new StaticArrayCacheSystem();
        $sys->clear();
    }

    public function testInstance() {
        $this->assertInstanceOf(CacheStrategy::class, new DefaultCacheStrategy());
    }

    public function testInit() {
        $default = new DefaultCacheStrategy();
        $ret = $default->init();

        $this->assertCount(1, $ret);
        $this->assertTrue(isset($ret[DefaultCacheStrategy::class . '::class']));
        $this->assertEquals(true, $ret[DefaultCacheStrategy::class . '::class']);
    }

    public function testUpdate() {
        $default = new DefaultCacheStrategy();

        list($ret, $upd) = $default->update(["test" => 1234]);

        $this->assertCount(1, $ret);
        $this->assertTrue(isset($ret['test']));
        $this->assertEquals(1234, $ret[ 'test']);
        $this->assertFalse($upd);
    }

    public function testGetValue() {
        $def = new DefaultCacheStrategy();
        $def->setValue('test1', 123);
        $def->setValue('test2', 'abc');

        $this->assertEquals(123, $def->getValue('test1'));
        $this->assertEquals('abc', $def->getValue('test2'));
        $this->assertNull($def->getValue('test3'));

        $def->setValue('test1', 'xxx');
        $this->assertEquals('xxx', $def->getValue('test1'));
    }
}
