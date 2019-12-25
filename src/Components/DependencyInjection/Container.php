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
     protected $shared = [];


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
     * @param $abstract
     * @return mixed
     * @throws \ReflectionException
     */
     public function factory($abstract)
     {
         return $this->make($abstract);
     }


    /**
     * @param $abstract
     * @param $concrete
     */
     public function singleton($abstract, $concrete = null)
     {
          $this->bind($abstract, $concrete, true);
     }


    /**
     * @param $abstract
     * @param array $parameters
     * @return mixed
     */
     public function make($abstract, $parameters = [])
     {
         // return $this->resolve($abstract, $parameters);
     }


    /**
     * @param $abstract
     * @return mixed
     * @throws \ReflectionException
     */
     public function get($abstract)
     {
         return $this->resolve($abstract);
     }


     /**
     * @param $abstract
     * @return mixed
     */
     public function resolve($abstract)
     {

     }
}