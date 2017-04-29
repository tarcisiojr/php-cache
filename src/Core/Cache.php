<?php

namespace PHP\Cache\Core;

use PHP\Cache\API\CacheStrategy;
use PHP\Cache\API\CacheSystem;
use PHP\Cache\Core\Strategy\DefaultCacheStrategy;
use PHP\Cache\Core\Strategy\ExactlyCacheStrategy;
use PHP\Cache\Core\Strategy\ScopeCacheStrategy;
use PHP\Cache\Core\Strategy\StatefulCacheStrategy;
use PHP\Cache\Core\Strategy\StatelessCacheStrategy;
use PHP\Cache\Core\Strategy\TTLCacheStrategy;
use PHP\Cache\Core\System\FileCacheSystem;
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
     * @var callable function
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
        $this->trace = $trace;
        $this->callback = $callback;
        $this->cacheStrategy = new DefaultCacheStrategy();
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

        $state = static::getStateCache()
            ->getValue($hash . '.state');

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

            if ($value === null) {
                $value = $this->exec();

                $this->cacheStrategy->setValue($hash . '.value', $value);
            }
        }

        static::getStateCache()
            ->setValue($hash . '.state', $state);

        return $value;
    }

    public function statefull() {
        $this->cacheStrategy = new StatefulCacheStrategy($this->cacheStrategy);

        return $this;
    }

    public function stateless() {
        $this->cacheStrategy = new StatelessCacheStrategy($this->cacheStrategy);

        return $this;
    }

    public function ttl($ttl) {
        $this->cacheStrategy = new TTLCacheStrategy($this->cacheStrategy, $ttl);

        return $this;
    }

    public function once() {
        return $this->times(0);
    }

    public function times($times = 1) {
        $this->cacheStrategy = new ExactlyCacheStrategy($this->cacheStrategy, $times);

        return $this;
    }

    public function scope($instance = true) {
        $this->cacheStrategy = new ScopeCacheStrategy($this->cacheStrategy, $instance);

        return $this;
    }

    public function get() {
        return $this->getValue();
    }

    private static function getStateCache() {
        if (static::$stateCache === null) {
            static::$stateCache = new FileCacheSystem();
        }

        return static::$stateCache;
    }

    public static function setStateCacheSystem(CacheSystem $cacheSystem) {
        static::$stateCache = $cacheSystem;
    }

    public static function create($callback) {
        return new Cache(debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[1], $callback);
    }
}
