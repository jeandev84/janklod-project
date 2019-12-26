<?php
namespace Jan\Demo\Console;


/**
 * Class MigrateCommand
 * @package Jan\Demo\Console
*/
class MigrateCommand implements CommandInterface
{

    /**
     * @inheritDoc
     */
    public function execute()
    {
        return 'Migrate data to the database';
    }

    /**
     * @inheritDoc
     */
    public function undo()
    {
        return '';
    }
}