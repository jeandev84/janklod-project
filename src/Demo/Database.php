<?php
namespace Jan\Demo;


/**
 * Class Database
 * @package Jan\Demo
*/
class Database
{

    private $driver = 'mysql';

    /**
     * Bar constructor.
     * @param string $host
     * @param string $dbname
     * @param string $user
     * @param string $password
    */
     public function __construct($host = '127.0.0.1', $dbname = 'db_name', $user = 'root', $password = 'no')
     {
          return sprintf(
              '%s:host=%s;dbname=%s;charset=utf8:user=%s:password=%s',
              $this->driver,
              $host,
              $dbname,
              $user,
              $password
          );
     }
}