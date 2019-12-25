<?php
namespace Jan\Components\DependencyInjection;


use Closure;


/**
 * Class Container
 * @package Jan\Components\DependencyInjection
 */
class Container
{
    /** @var array  */
    protected $instances = [];

    /** @var array  */
    protected $bindings = [];


    /** @var array  */
    protected $aliases = [];


    /**
     * Container constructor.
     */
    public function __construct() {}


    /**
     * @param $key
     * @param $concrete
     */
    /**
     * @param $abstract
     * @param null $concrete
     * @param bool $shared
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        if(is_null($concrete))
        {
            $concrete = $abstract;
        }

        // if(! $this->isClosure($concrete)) { }

        $this->bindings[$abstract] = compact('concrete', 'shared');
    }


    /**
     * @param $concrete
     * @return bool
     */
    public function isClosure($concrete)
    {
        return $concrete instanceof Closure;
    }


    /**
     * @param $key
     * @param object $concrete
     */
    public function factory($key, object $concrete)
    {
        $this->factories[$key] = $concrete;
    }


    /**
     * @param $key
     * @param $concrete
     */
    public function singleton($key, $concrete)
    {

    }


    /**
     * @param $abstract
     * @param array $parameters
     * @return mixed
     * @throws \ReflectionException
     */
    public function make($abstract, $parameters = [])
    {
        // return $this->resolve($abstract, $parameters);
    }


    /**
     * @param $key
     * @return bool
     */
    public function bound($key)
    {
        return isset(
            $this->instances[$key],
            $this->factories[$key],
            $this->singletons[$key],
            $this->services[$key]
        );
    }


    /**
     * @param $key
     * @param array $parameters
     * @return mixed
     * @throws \ReflectionException
     */
    public function get($key, $parameters = [])
    {
        return $this->resolve($key, $parameters);
    }


    /**
     * @param $key
     * @return bool
     */
    public function isInstantiated($key)
    {
        return isset($this->instances[$key]);
    }

    /**
     * @param $key
     * @param array $parameters
     * @return mixed
     * @throws \ReflectionException
     */
    public function resolve($key, $parameters = [])
    {
        // get services
        if(isset($this->services[$key]))
        {
            return $this->services[$key];
        }

        // factories
        /*
        if(isset($this->factories[$key]))
        {
            return $this->factories[$key];
        }
        */

        // singletons
        if(! isset($this->instances[$key]))
        {
            if(isset($this->singletons[$key]))
            {
                $this->instances[$key] = $this->singletons[$key];
            }
        }

        if(! $this->bound($key))
        {
            $reflection = new \ReflectionClass($key);
        }

        return $this->instances[$key] ?? null;
    }


    /**
     * @inheritDoc
     */
    public function has($key)
    {
        return $this->bound($key);
    }

    /**
     * @inheritDoc
     */
    public function remove($key)
    {
        unset($this->instances[$key]);
    }

    /**
     * @inheritDoc
     */
    public function flush()
    {
        $this->instances = [];
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }


    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        // TODO: Implement count() method.
    }
}