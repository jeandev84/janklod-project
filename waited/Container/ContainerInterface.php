<?php
namespace Jan\Contracts\Container;


/**
 * Interface ContainerInterface
 * @package Jan\Contracts\Container
*/
interface ContainerInterface extends \ArrayAccess, \Countable
{
    /**
     * Bind param
     * @param $key
     * @param $concrete
     * @return mixed
    */
    public function set($key, $concrete);


    /**
     * Determine if the given param is in container collection
     * @param $key
     * @return mixed
    */
    public function has($key);


    /**
     * Remove given item from container
     * @param $key
     * @return mixed
    */
    public function remove($key);


    /**
     * Remove all binded params
     * @return mixed
    */
    public function flush();


    /**
     * Get item from container
     * @param $key
     * @param array $parameters
     * @return mixed
    */
    public function get($key, $parameters = []);
}