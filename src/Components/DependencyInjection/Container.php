<?php
namespace Jan\Components\DependencyInjection;


use Closure;
use Jan\Components\DependencyInjection\Exceptions\NotInstantiableException;


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


    /**
     * Container constructor.
    */
    public function __construct() {}


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


    /**
     * @param $abstract
     * @return mixed|null
    */
    protected function getShared($abstract)
    {
        return $this->bindings[$abstract]['shared'] ?? null;
    }


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

         $this->bindings[$abstract] = compact('concrete', 'shared');
    }


    /**
     * @param $abstract
     * @param null $concrete
    */
    public function singleton($abstract, $concrete = null)
    {
        $this->bind($abstract, $concrete, true);
    }


    /**
     * @param $abstract
     * @return mixed
     * @throws \ReflectionException
     * @throws NotInstantiableException
    */
    public function factory($abstract)
    {
        return $this->make($abstract);
    }


    /**
     * @param $abstract
     * @param null $dependencies
     * @return mixed|null
     * @throws \ReflectionException
     * @throws NotInstantiableException
    */
    public function make($abstract, $dependencies = null)
    {
         return $this->resolve($abstract, $dependencies);
    }


    /**
     * @param $abstract
     * @param array $parameters
     * @return mixed|null
     * @throws \ReflectionException
     * @throws NotInstantiableException
    */
    public function get($abstract, $parameters = [])
    {
        if($this->hasConcrete($abstract))
        {
            return $this->getConcrete($abstract);
        }

        return $this->resolve($abstract, $parameters);
    }


    /**
     * @param $abstract
     * @param array $dependencies
     * @return mixed|null
     * @throws NotInstantiableException
     * @throws \ReflectionException
    */
    public function resolve($abstract, $dependencies = [])
    {
        if(! $this->isInstantiated($abstract));
        {
            if($this->hasConcrete($abstract) && $this->isShared($abstract))
            {
                $this->instances[$abstract] = $this->getConcrete($abstract);

            }else{

                $reflectedClass = new \ReflectionClass($abstract);
                $this->instances[$abstract] = $this->createNewInstanceOfObject($reflectedClass);
                /* die('In Resolution!'); */
            }
        }

        return $this->instances[$abstract];

        /*
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

        if($dependencies)
        {
            return $reflectedClass->newInstanceArgs((array)$dependencies);
        }

        // return $standard === true ? new \stdClass() : null;
        */
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
     * @param \ReflectionClass $reflectedClass
     * @param null $parameters
     * @return object
     * @throws NotInstantiableException
     * @throws \ReflectionException
    */
    public function createNewInstanceOfObject(\ReflectionClass $reflectedClass, $parameters = null)
    {
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

        $dependencies = $parameters ?? $this->getMethodDependencies($constructor);
        return $reflectedClass->newInstanceArgs((array)$dependencies);
    }


    /**
     * @param \ReflectionMethod $reflectionMethod
     * @return array
     * @throws \ReflectionException
     * @throws \Exception
    */
    public function getMethodDependencies(\ReflectionMethod $reflectionMethod)
    {
        $dependencies = [];
        foreach ($reflectionMethod->getParameters() as $parameter)
        {
            $dependency = $parameter->getClass(); /* dump($dependency); */
            if(is_null($dependency))
            {
                if($parameter->isDefaultValueAvailable())
                {
                    $dependencies[] = $parameter->getDefaultValue();
                }else {
                    throw new Exception(
                        sprintf('Can not resolve class dependency (%s)', $parameter->getName())
                    );
                }
            }else{

                $dependencies[] = $this->get($dependency->getName());
            }

        }

        /* dd($reflectionMethod->getParameters()); */
        return $dependencies;
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
     * Determine if the given abstract param has parsed concrete value
     * @param $abstract
     * @return bool
    */
    protected function hasConcrete($abstract)
    {
        return isset($this->bindings[$abstract]['concrete']);
    }


    /**
     * Determine if the given param is instantiated
     * @param $key
     * @return bool
    */
    public function isInstantiated($key)
    {
        return isset($this->instances[$key]);
    }

    /**
     * Create an instance of not instantiable exception
     * @param string $message
     * @return NotInstantiableException
    */
    protected function createNotInstantiableException($message)
    {
        return new NotInstantiableException($message);
    }
}