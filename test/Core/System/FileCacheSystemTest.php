<?php

namespace Test\PHP\Cache\Core\System;

use PHP\Cache\Core\System\FileCacheSystem;

class Value implements \Serializable {
    public $valueA;
    public $valueB;

    public function __construct($valueA, $valueB) {
        $this->valueA = $valueA;
        $this->valueB = $valueB;
    }

    public function serialize() {
        return serialize([
            $this->valueA, $this->valueB
        ]);
    }

    public function unserialize($serialized) {
        $serialized = unserialize($serialized);

        $this->valueA = $serialized[0];
        $this->valueB = $serialized[1];
    }
}

class FileCacheSystemTest extends \PHPUnit_Framework_TestCase {

    protected $fileName;
    protected $lockFileName;

    public function setUp() {
        $this->fileName  = sys_get_temp_dir() . '/' . uniqid('FileCacheSystemTest', true) . '.json';
        $this->lockFileName = $this->fileName . '.lock';

        $this->deleteFiles();
    }

    public function tearDown() {
        $this->deleteFiles();
    }

    public function testIsPersistente() {
        $ins = new FileCacheSystem();
        $this->assertTrue($ins->isPersistent());
    }

    public function testGetFileName() {
        $fileName = sys_get_temp_dir()  . '/teste1.json';

        $ins = new FileCacheSystem($fileName);
        $this->assertEquals($fileName, $ins->getFileName());
    }

    public function testSetValueCreateFile() {
        $ins = new FileCacheSystem($this->fileName);
        $ins->setValue('teste', 123);

        $this->assertTrue(file_exists($this->fileName));
        $this->assertTrue(file_exists($this->lockFileName));
    }

    public function testSetValue() {
        $ins = new FileCacheSystem($this->fileName);
        $ins->setValue('test1', 123);
        $ins->setValue('test2', 'abc');
        $ins->setValue('test3', array('1', 2));
        $ins->setValue('test4', 10.5);

        $this->assertEquals(123, $ins->getValue('test1'));
        $this->assertEquals('abc', $ins->getValue('test2'));

        $arr = $ins->getValue('test3');

        $this->assertCount(2, $arr);
        $this->assertEquals('1', $arr[0]);
        $this->assertEquals(2, $arr[1]);
        $this->assertEquals(10.5, $ins->getValue('test4'));
    }

    public function testSetValueClass() {
        $value = new Value('a', 'b');
        $ins = new FileCacheSystem($this->fileName);
        $ins->setValue('test1', $value);

        $ret = $ins->getValue('test1');

        $this->assertInstanceOf(get_class($value), $ret);
        $this->assertEquals('a', $ret->valueA);
        $this->assertEquals('b', $ret->valueB);

        $ins->setValue('test2', $this);

        $this->assertNull($ins->getValue('test2'));
    }

    public function testDelete() {
        $ins = new FileCacheSystem($this->fileName);
        $ins->setValue('test1', 'abcd');

        $this->assertEquals('abcd', $ins->getValue('test1'));

        $ins->delete('test1');

        $this->assertNull($ins->getValue('test1'));
    }

    private function deleteFiles() {
        if (file_exists($this->fileName)) {
            unlink($this->fileName);
        }

        if (file_exists($this->lockFileName)) {
            unlink($this->lockFileName);
        }
    }
}
