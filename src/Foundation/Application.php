<?php
namespace Jan\Foundation;


use Jan\Components\DependencyInjection\Container;


/**
 * Class Application
 * @package Jan\Foundation
*/
class Application extends Container
{

     /** @var string */
     private $basePath;


    /**
     * Application constructor.
     * @param string $basePath
     */
     public function __construct(string $basePath)
     {
         /* die($basePath); */
         $this->basePath = $basePath;
     }

     public function run()
     {
         die($this->basePath);
     }
}