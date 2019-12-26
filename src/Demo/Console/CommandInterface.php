<?php
namespace Jan\Demo\Console;


/**
 * Interface CommandInterface
 * @package Jan\Demo
*/
interface CommandInterface
{
    /**
     * @return mixed
    */
    public function execute();

    /**
     * @return mixed
    */
    public function undo();
}