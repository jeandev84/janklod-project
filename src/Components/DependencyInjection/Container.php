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
     private $instances = [];

     /** @var array  */
     private $services = [];

     /** @var array  */
     private $factories = [];

     /** @var array  */
     private $singletons = [];


     /**
      * Container constructor.
     */
     public function __construct()
     {

     }
}