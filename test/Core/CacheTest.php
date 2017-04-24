<?php
/**
 * Created by PhpStorm.
 * User: tarcisio
 * Date: 19/04/17
 * Time: 21:42
 */

namespace PHP\Cache\Core\Test;

use PHP\Cache\Core\ApplicationAspectKernel;
use PHP\Cache\Core\Cache;
use PHP\Cache\Core\TesteCache;


class CacheTest extends \PHPUnit_Framework_TestCase {

    public function testCacheOnce() {
        echo "\n > ".$this->dummyMethod();
        echo "\n > ".$this->dummyMethod("a");
        echo "\n > ".$this->dummyMethod();
        echo "\n > ".$this->dummyMethod();

        //echo "\n\n";
        //
        //$startTime = microtime(true);
        //for ($i = 0; $i < 100; $i++) {
        //    $this->dummyMethod2();
        //}
        //echo "Time:  " . number_format(( microtime(true) - $startTime), 4) . " Seconds\n";
        //
        //$startTime = microtime(true);
        //for ($i = 0; $i < 100; $i++) {
        //    $this->dummyMethod3();
        //}
        //echo "Time:  " . number_format(( microtime(true) - $startTime), 4) . " Seconds\n";

        //echo "\n> ".$this->dummyMetodo4();
        //echo "\n> ".$this->dummyMetodo4();
        //echo "\n> ".$this->dummyMetodo4();
        //echo "\n> ".$this->dummyMetodo4();
        //echo "\n> ".$this->dummyMetodo4();

        //echo "\n". (new TesteCache())->teste() . "\n";
    }

    //private function dummyMetodo4() {
    //    if (!function_exists(__NAMESPACE__ . '\fDummy')) {
    //        function fDummy() {
    //            return Cache::create(function() {
    //                return rand(1, 100);
    //            })->statefull()->times(3);
    //        }
    //    }
    //
    //    return fDummy();
    //}

    private function dummyMethod($a = "a", $b = "b") {
        return Cache::create(function() {
            return rand(1, 100);
        })->once()->statefull()->scope(false)->ttl(10)->get();
    }

    //private function dummyMethod3() {
    //    return  rand(1, 100);
    //}
    //
    //private function dummyMethod2() {
    //    return Cache::create(function() {
    //        return  rand(1, 100);
    //    })->times(0);
    //}
}
