<?php
namespace Jan\Components\DependencyInjection;


use Closure;
use Jan\Components\DependencyInjection\Exceptions\NotInstanciableException;


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
     * @param $abstract
     * @param null $concrete
     * @param bool $shared
    */
    public function bindings($abstract, $concrete = null, $shared = false)
    {

    }
}