<?php
/**
 * Created by PhpStorm.
 * User: tarcisio
 * Date: 20/04/17
 * Time: 22:03
 */

namespace PHP\Cache\Core;

use PHP\Cache\API\CacheStrategy;
use PHP\Cache\API\CacheSystem;
use PHP\Cache\Core\Strategy\ExactlyCacheStrategy;
use PHP\Cache\Core\System\StaticArrayCacheSystem;

class Cache {

    /**
     * @var CacheSystem
     */
    private static $stateCache;

    /**
     * @var array
     */
    protected $trace;

    /**
     * @var function
     */
    protected $callback;

    /**
     * @var CacheStrategy
     */
    protected $cacheStrategy;

    /**
     * Cache constructor.
     *
     * @param               $trace         Debug trace.
     * @param               $callback      Callback function.
     * @param CacheStrategy $cacheStrategy Strategy that will be used for update value.
     * @param CacheSystem   $cacheSystem   Cache system.
     */
    private function __construct($trace, $callback) {
        $this->trace    = $trace;
        $this->callback = $callback;
    }

    private function getArguments() {
        return $this->trace['args'];
    }

    private function getFunctionName() {
        return $this->trace['function'];
    }

    /**
     * @return mixed
     */
    private function getObject() {
        return isset($this->trace['object']) ? $this->trace['object'] : null;
    }

    private function getHash() {
        return $this->cacheStrategy->getHash($this->getObject(), $this->getFunctionName(), $this->getArguments());
    }

    private function exec() {
        return call_user_func($this->callback, $this->getArguments());
    }

    private function getValue() {
        $hash = $this->getHash();

        $state = static::getStateCache()->getValue($hash . '.state');

        $update = false;

        if ($state === null) {
            $state = $this->cacheStrategy->init();

            $update = true;
        } else {
            list($state, $update) = $this->cacheStrategy->update($state);
        }

        if ($update) {
            $value = $this->exec();

            $this->cacheStrategy->setValue($hash . '.value', $value);

        } else {
            $value = $this->cacheStrategy->getValue($hash . '.value');
        }

        static::getStateCache()->setValue($hash . '.state', $state);

        return $value;
    }

    public function once() {
        return $this->times(0);
    }

    public function times($times = 1) {
        $this->cacheStrategy = new ExactlyCacheStrategy($times);

        return $this->getValue();
    }

    private static function getStateCache() {
        if (static::$stateCache == null) {
            static::$stateCache = new StaticArrayCacheSystem();
        }

        return static::$stateCache;
    }

    public static function create($callback) {
        return new Cache(debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[1], $callback);
    }
}
