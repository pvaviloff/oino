<?php
namespace Oino\Services;

use \Oino\Constants\MigrationConst;
use \Oino\Args;

class CreateTables
{
    private $args;

    public function __construct(Args $args)
    {
        $this->args = $args;
    }

    public function execute(array $tables)
    {
        $createMigration = new CreateMigration($tables, $this->args);
        $createMigration->execute(MigrationConst::CREATE_TABLES);
    }
}