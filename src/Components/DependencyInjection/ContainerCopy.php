<?php
namespace Jan\Components\DependencyInjection;


use Closure;
use Jan\Components\DependencyInjection\Exceptions\NotInstanciableException;


/**
 * Class ContainerCopy
 * @package Jan\Components\DependencyInjection
*/
class ContainerCopy
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
     * @param string $abstract
     * @param null $concrete
     * @param bool $shared
     */
     public function bind($abstract, $concrete = null, $shared = false)
     {
         if(is_null($concrete))
         {
             $concrete = $abstract;
         }

         /* if(! $this->isClosure($concrete)) { } */

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
     * @param string $abstract
     * @return mixed
     * @throws \ReflectionException
     * @throws NotInstanciableException
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
     * @param null $parameters
     * @return object
     * @throws NotInstanciableException
     * @throws \ReflectionException
     */
     public function make($abstract, $parameters = null)
     {
         $reflectedClass = new \ReflectionClass($abstract);

         if(! $reflectedClass->isInstantiable())
         {
             throw $this->createNotInstantiableException(
                 sprintf('Class (%s) is not instantiable!', $reflectedClass->getName())
             );
         }

         $constructor = $reflectedClass->getConstructor();

         if(is_null($constructor))
         {
             return $reflectedClass->newInstance();
         }

         $dependencies = (array) $parameters ?? [];

         if(empty($parameters))
         {
             /* $dependencies = $this->getDependencies(
                 $this->getMethodParameters($constructor)
             );*/

             /* die('No parameters'); $dependencies = []; */
             $dependencies = $this->getConstructorDependencies($constructor);
         }

         return $reflectedClass->newInstanceArgs($dependencies);
     }


    /**
     * @param $abstract
     * @return mixed
     * @throws \ReflectionException
     * @throws NotInstanciableException
     */
     public function get($abstract)
     {
         return $this->resolve($abstract);
     }


    /**
     * @param $abstract
     * @param array $parameters
     * @return mixed
     * @throws NotInstanciableException
     * @throws \ReflectionException
     */
     public function resolve($abstract)
     {
         if($this->hasConcrete($abstract))
         {
             return $this->getConcrete($abstract);
         }

         if(! $this->isInstantiated($abstract));
         {
             if($this->hasConcrete($abstract) && $this->isShared($abstract))
             {
                 $this->instances[$abstract] = $this->getConcrete($abstract);

             }else{

                 /*
                 $relectedClass = new \ReflectionClass($abstract);
                 $this->instances[$abstract] = $this->resolveClassDependencies($relectedClass);
                 */

                 die('In Resolution!');
             }
         }

         return $this->instances[$abstract];
     }


     /**
      * @param \ReflectionMethod $constructor
     */
     public function getConstructorDependencies(\ReflectionMethod $constructor)
     {
         $parameters = $this->getMethodParameters($constructor);


         debug($parameters, true);
     }


    /**
     * @param \ReflectionMethod $reflectionMethod
     * @return \ReflectionParameter[]
    */
    public function getMethodParameters(\ReflectionMethod $reflectionMethod)
    {
         return $reflectionMethod->getParameters();
    }


    /**
     * @param \ReflectionClass $reflectionClass
     * @return \ReflectionClass[]
    */
    public function getInterfaces(\ReflectionClass $reflectionClass)
    {
         return $reflectionClass->getInterfaces();
    }


    /**
     * @param \ReflectionClass $reflectionClass
     * @return false|\ReflectionClass
    */
    public function getParentClass(\ReflectionClass $reflectionClass)
    {
        return $reflectionClass->getParentClass();
    }

    /**
     * @param \ReflectionClass $reflectionClass
    */
    public function resolveClassDependencies(\ReflectionClass $reflectionClass)
    {

    }



    /**
     * @param $parameters
     * @return array
     * @throws \ReflectionException
     * @throws NotInstanciableException
     */
    public function getDependencies($parameters)
    {
       return $this->populateDependencies($parameters);
    }


    /**
     * @param $parameters
     * @return array
     * @throws NotInstanciableException
     * @throws \ReflectionException
    */
    public function populateDependencies($parameters)
    {
        $dependencies = [];
        foreach ($parameters as $parameter)
        {
            $dependency = $parameter->getClass();
            if($dependency === NULL)
            {
                if ($parameter->isDefaultValueAvailable())
                {
                    // get default value of parameter
                    $dependencies[] = $parameter->getDefaultValue();

                } else {

                    throw new Exception("Can not resolve class dependency {$parameter->name}");

                }

            }else {

                /* die($dependency->name); */
                echo ('Found: '. $dependency->getName()) . '<br>';
                $dependencies[] = $this->get($dependency->getName());
            }

            return $dependencies;
        }
    }


    /**
     * @param \ReflectionClass $reflectionClass
     * @return \ReflectionMethod
    */
    public function getConstructorClass(\ReflectionClass $reflectionClass): \ReflectionMethod
    {
        return $reflectionClass->getConstructor();
    }

    /**
     * @param $abstract
     * @return bool
    */
    protected function hasConcrete($abstract)
    {
        return isset($this->bindings[$abstract]['concrete']);
    }


    /**
     * @param $abstract
     * @return mixed|null
    */
    protected function getConcrete($abstract)
    {
        return $this->bindings[$abstract]['concrete'] ?? null;
    }


    /**
     * @param string $abstract
     * @return bool
     */
    public function bound($abstract)
    {
        return isset($this->bindings[$abstract])
            || isset($this->instances[$abstract])
            || isset($this->aliases[$abstract]);
    }

    /**
     * @param $id
     * @return bool
     */
    public function has($id)
    {
        return $this->bound($id);
    }


    /**
     * @param string $abstract
     * @return bool
     */
    public function isShared($abstract)
    {
        return (isset($this->bindings[$abstract]['shared'])
            && $this->bindings[$abstract]['shared'] === true) ;
    }


    /**
     * @param $abstract
     * @return bool
     */
    public function isInstantiatedOrShared($abstract)
    {
        return  isset($this->instances[$abstract]) || $this->isShared($abstract);
    }



    public function bindings()
    {
        return debug($this->bindings);
    }


    /**
     * @param $abstract
     * @return mixed|null
    */
    protected function getShared($abstract)
    {
        return $this->bindings[$abstract]['shared'] ?? null;
    }


    /**
     * @param string $message
     * @return NotInstanciableException
    */
    protected function createNotInstantiableException($message)
    {
        return new NotInstanciableException($message);
    }
}