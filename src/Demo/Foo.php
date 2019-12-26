<?php
namespace Jan\Demo;


use Jan\Demo\Console\CommandInterface;

/**
 * Class Foo
 * @package Jan\Demo
*/
class Foo
{

    /**
     * Foo constructor.
     * @param Database $database
     * @param $argument
     * @param array $config
   */
   /*
   public function __construct(Database $database, $argument, $config = [])
   {
       return  'I am : '. __METHOD__;
   }
   */


   /**
     * Foo constructor.
     * @param CommandInterface $command
     * @param array $config
   */
   /*
   public function __construct(CommandInterface $command, $config = [])
   {
   }
   */

   /**
     * Foo constructor.
     * @param Bar $bar
     * @param array $config
   */
   public function __construct(Bar $bar, $config = [])
   {
   }
}