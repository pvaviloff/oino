<?php
namespace Oino\Services;

use \Oino\ValueObjects\VOExistColumn;
use \Oino\Aggregates\AUpdateTable;
use \Oino\Constants\MigrationConst;
use \Oino\Args;

class UpdateTables
{
    private $dbParser;

    private $args;

    public function __construct(DBParser $dbParser, Args $args)
    {
        $this->dbParser = $dbParser;
        $this->args = $args;
    }

    public function execute(array $tables)
    {
        foreach ($tables as $key => $table) {
            if (! $table instanceof AUpdateTable) {
                throw new \Exception('Table must be instance of AUpdateTable');
            }
            $existTableObject = $table->getExistTable();

            $existColumns = $this->dbParser->getColumnsByTable(
                $existTableObject->getTableDatabase(),
                $existTableObject->getTableName()
            );

            foreach ($existColumns as $existColumn) {
                if (!$existColumn instanceof VOExistColumn) {
                    throw new \Exception('Column must be instance of VOExistColumn');
                }
                if ($table->isColumnChanged($existColumn)) {
                    continue;
                }

                if($table->removeColumnByName($existColumn->getName()) && $table->isColumnsEmpty()) {
                    unset($tables[$key]);
                }
            }
        }

        $createMigration = new CreateMigration($tables, $this->args);
        $createMigration->execute(MigrationConst::UPDATE_TABLE);
    }
}