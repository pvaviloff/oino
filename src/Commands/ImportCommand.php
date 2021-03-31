<?php
namespace Oino\Commands;

use \Oino\Services\UpdateTables;
use \Oino\Services\CreateTables;
use \Oino\Services\TablesHandler;
use \Oino\Services\FileParser;
use \Oino\Services\DBParser;
use \Oino\Constants\CommandConst;
use \Oino\Args;

class ImportCommand implements ICommand
{
    public function handle(Args $args)
    {
        $fileParser = new FileParser($args->getValue(CommandConst::FILE_OPTION));
        $fileParser->execute();
        $tab = $fileParser->getByTab($args->getValue(CommandConst::TAB_OPTION));
        $dbParser = new DBParser();
        $tablesFromDatabase = $dbParser->getTables();

        $tablesHandler = new TablesHandler($tab->tables, $tablesFromDatabase);
        $tablesHandler->handle();

        $createTables = new CreateTables($args);
        $createTables->execute($tablesHandler->getTablesForCreate());

        $addColumnsTables = new UpdateTables($dbParser, $args);
        $addColumnsTables->execute($tablesHandler->getTablesForUpdate());
    }
}