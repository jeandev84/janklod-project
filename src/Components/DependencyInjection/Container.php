<?php
namespace Jan\Components\DependencyInjection;


use Jan\Contracts\Container\ContainerInterface;


/**
 * Class Container
 * @package Jan\Components\DependencyInjection
*/
class Container implements ContainerInterface
{
     /** @var array  */
     protected $instances = [];

     /** @var array  */
     protected $services = [];

     /** @var array  */
     protected $factories = [];

     /** @var array  */
     protected $singletons = [];


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
     public function set($key, $concrete)
     {
         $this->services[$key] = $concrete;
     }

     /**
      * @param array $aliases
      * @return Container
     */
     public function setAliases(array $aliases)
     {
         $this->aliases = $aliases;
         return $this;
     }

     /**
      * @param string $key
      * @return mixed|null
     */
     public function getAlias(string $key)
     {
         return $this->aliases[$key] ?? null;
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
         $this->singletons[$key] = $concrete;
     }


    /**
     * @param $abstract
     * @param array $parameters
     * @return mixed
     * @throws \ReflectionException
     */
     public function make($abstract, $parameters = [])
     {
         return $this->resolve($abstract, $parameters);
     }

     /**
      * @param $key
      * @param null $concrete
     */
     public function bind($key, $concrete = null)
     {
          if(is_null($concrete))
          {
              $concrete = $key;
          }
          $this->set($key, $concrete);
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
          if(isset($this->factories[$key]))
          {
              return $this->factories[$key];
          }

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