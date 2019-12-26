<?php
namespace JK\DI;


use Exception;
use JK\DI\Containers\Factory;
use JK\DI\Containers\Singleton;
use JK\DI\Contracts\ContainerInterface;


/**
 * Class Container
 * @package JK\DI
 */
class Container
{
    /**
     * @var Container
     */
    private static $instance;


    /**
     * @var array
     */
    private $container = [];


    /**
     * @var array
     */
    private $instances  = [];


    /**
     * @var bool
     */
    private $autowire = false;

    /**
     * @var string
     */
    private $autowireClass;


    /**
     * prevent instance from being cloned
     * @return void
     */
    private function __clone() {}


    /**
     * prevent instance from being unserialized
     * @return void
     */
    private function __wakeup() {}




    /**
     * Bind item
     *
     * Example:
     *  $app = new Container();
     *  $app->bind(\Framework\Test::class, new \Framework\Test());
     *  $app->bind(\Framework\Test::class, function () {
     *     return new \Framework\Test();
     * });
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function bind(string $key, $value)
    {
        $this->container['bind'][$key] = $value;
    }


    /**
     * Add singleton
     *
     * Example:
     *  $app = new Container();
     *  $app->singleton(\Framework\Test::class, new \Framework\Test());
     *  $app->singleton(\Framework\Test::class, function () {
     *     return new \Framework\Test();
     * });
     *
     * @param string $key
     * @param $resolver
     */
    public function singleton(string $key, $resolver)
    {
        $this->container['singleton'][$key] = $resolver;
    }


    /**
     * Add factory
     *
     * Example:
     *  $app = Container::instance();
     *  $app->factory(\Framework\Test::class, new \Framework\Test())
     *  $app->factory(\Framework\Test::class, function () {
     *     return new \Framework\Test();
     * });
     *
     * @param string $key
     * @param object $resolver
     * @throws Exception
     */
    public function factory(string $key, $resolver)
    {
        $this->container['factory'][$key] = $resolver;
    }


    /**
     * Set instance
     * @throws \ReflectionException
     */
    public function instance()
    {
        if($arguments = func_get_args())
        {
            $name = $this->reflection($arguments[0])->getName();
            $instance = null;

            if(count($arguments) === 1)
            {
                $instance = $arguments[0];
            }

            if(count($arguments) === 2)
            {
                $instance = $arguments[1];
            }

            if(! is_object($instance))
            {
                throw new Exception('Please add an object!');
            }

            $this->instances[$name] = $instance;
        }
    }


    /**
     * Create new object with or without parameters
     *
     * Example:
     *  $app = Container::instance();
     *  $arguments = [dirname(__DIR__)];
     *  $fileObject1 = $app->make(\Framework\File::class, $arguments);
     *  $fileObject2 = $app->make(\Framework\File::class, dirname(__DIR__));
     *
     * @param string $classname
     * @param mixed $arguments
     * @return object
     * @throws \ReflectionException
     */
    public function make(string $classname, $arguments = null): object
    {
        $reflectedClass = $this->reflection($classname);

        if(! $reflectedClass->isInstantiable())
        {
            throw new Exception(
                sprintf('Sorry, This (%s) does not instantiable', $reflectedClass->getName())
            );
        }

        if($arguments)
        {
            if($reflectedClass->getConstructor())
            {
                if(! is_array($arguments))
                {
                    $arguments = (array) $arguments;
                }
                return $reflectedClass->newInstanceArgs($arguments);
            }
        }
        return $reflectedClass->newInstance() ?: new \stdClass();
    }


    /**
     * Do autowiring
     * @param bool $status
     */
    public function useAutowiring($status = false)
    {
        $this->autowire = $status;
    }


    /**
     * Autowiring
     *
     * Example:
     *  $app = Container::instance();
     *  $testObject = $app->autowire(\Framework\Test::class);
     *
     * @param string $key
     * @return Container
     */
    public function autowire(string $key)
    {
        $this->autowireClass = $key;
        return $this;
    }


    /**
     * Add constructor parameters
     *
     * Example:
     *  $app = Container::instance();
     *  $app->bind(Foo::class, $app->autowire(\Framework\Foo::class)
     *      ->constructorParameter(\Framework\Bar::class, \App\Entity\User::class);
     *
     * @return object
     * @throws \ReflectionException
     * @throws Exception
     */
    public function constructorParameter()
    {
        if($this->autowireClass && $this->autowire === true)
        {
            $parameters = [];
            if ($arguments = func_get_args()) {
                foreach ($arguments as $classname) {
                    if ($parameter = $this->get($classname)) {
                        array_push($parameters, $parameter);
                    } else {
                        $parameters = $arguments;
                        break;
                    }
                }
            }

            return $this->createObject($this->autowireClass, $parameters);
        }
    }


    /**
     * Get item from container
     * @param string $key
     * @return mixed
     * @throws \Exception
     */
    public function get(string $key)
    {
        foreach($this->container as $type => $repositories) {

            if(isset($repositories[$key]))
            {
                if($type !== 'singleton')
                {
                    echo 'No singleton';
                    return $this->determineTypeOfResolver($repositories[$key]);
                }

                if (! isset($this->instances[$key])) {
                    echo 'Singleton';
                    $this->instances[$key] = $this->determineTypeOfResolver($repositories[$key]);
                }

                return $this->instances[$key];
            }

        }
    }


    /**
     * Get ( OLD METHOD )
     * @param string $key
     * @return mixed
     */
    public function getOLD(string $key)
    {
        foreach($this->container as $type => $repositories) {

            if(isset($repositories[$key]))
            {
                if ($type != 'singleton') {
                    echo 'No singleton';
                    return $this->determineTypeOfResolver($repositories[$key]);
                }

                if (! isset($this->instances[$key])) {
                    echo 'Singleton';
                    $this->instances[$key] = $repositories[$key];
                }
                return $this->determineTypeOfResolver($this->instances[$key]);

            }else{

                die('No resolvable');
            }
        }
    }


    /**
     * Determine type of given value
     * @param $resolver
     * @return mixed
     */
    protected function determineTypeOfResolver($resolver)
    {
        if($resolver instanceof \Closure)
        {
            return call_user_func($resolver);
        }
        return $resolver;
    }



    /**
     * Get array collection of container
     * @return array
     */
    public function collection()
    {
        return $this->container;
    }




    /**
     * Determine given argument
     * @param string $classname
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    protected function reflection($classname)
    {
        if(is_string($classname) && ! class_exists($classname))
        {
            throw new Exception(
                sprintf('Sorry, class (%s) does not exist', $classname)
            );
        }

        return new \ReflectionClass($classname);
    }

}