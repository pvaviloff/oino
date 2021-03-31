<?php
namespace Oino\Services\DBFormatter;

use \Oino\Constants\WorkspaceConst;
use \Oino\Aggregates\ADatabase;
use Oino\Aggregates\AExistTable;
use Oino\ValueObjects\VOExistColumn;
use \Oino\Helpers\InstanceHelper;
use Ramsey\Uuid\Uuid;

class JsonExecutor
{
    private $databases;

    public function __construct(array $databases)
    {
        InstanceHelper::isArrayInstanceOf($databases, ADatabase::class);

        $this->databases = $databases;
    }

    private function getTab(ADatabase $database, bool $isSelected): \stdClass
    {
        $tab = new \stdClass();
        $tab->guid = Uuid::uuid4()->serialize();
        $tab->tabName = $database->getName();
        $tab->selected = $isSelected;
        $tab->tables = [];
        foreach ($database->getTables() as $tableObject) {
            $tab->tables[] = $this->getTable($tableObject);
        }

        return $tab;
    }

    private function getTable(AExistTable $existTable): \stdClass
    {
        $table = new \stdClass();
        $table->guid = Uuid::uuid4()->serialize();
        $table->tableName = $existTable->getTable()->getTableName();
        $table->posX = WorkspaceConst::START_X;
        $table->posY = WorkspaceConst::START_Y;
        $table->rows = [];
        foreach ($existTable->getColumns() as $column) {
            $table->rows[] = $this->getColumn($column);
        }

        return $table;
    }

    private function getColumn(VOExistColumn $existColumn): \stdClass
    {
        $column = new \stdClass();
        $column->guid = Uuid::uuid4()->serialize();
        $column->isKey = $existColumn->isPrimary();
        $column->columnName = $existColumn->getName();
        $column->type = $existColumn->getType();
        $column->typeSize = $existColumn->getLength();
        $column->defaultValue = $existColumn->getDefault();
        $column->comment = $existColumn->getComment();
        $column->foreignKeyToGuid = "";

        return $column;
    }

    private function addForeignKeys(\stdClass $tab, ADatabase $database): \stdClass
    {
        foreach ($tab->tables as $fromTable) {
            foreach ($fromTable->rows as $fromRow) {
                foreach ($tab->tables as $toTable) {
                    foreach ($toTable->rows as $toRow) {
                        if ($database->isForeignKeyExist(
                            $fromTable->tableName,
                            $fromRow->columnName,
                            $toTable->tableName,
                            $toRow->columnName
                        )) {
                            $fromRow->foreignKeyToGuid = $toRow->guid;
                        }
                    }
                }
            }
        }

        return $tab;
    }

    private function sortTablesByRelations(array $tables): array
    {
        $sortByRelationTables = [];
        foreach ($tables as $table) {
            $foreignKeysCount = 0;
            foreach ($table->rows as $row) {
                if (!empty($row->foreignKeyToGuid)) {
                    $foreignKeysCount++;
                }
            }
            $sortByRelationTables[$foreignKeysCount][] = $table;
        }

        return $sortByRelationTables;
    }

    private function setPosition(\stdClass $tab): \stdClass
    {
        $sortByRelationTables = $this->sortTablesByRelations($tab->tables);
        $handledTables = [];
        $distanceY = WorkspaceConst::START_Y;
        foreach ($sortByRelationTables as $relationCount => $tables) {
            $distanceX = WorkspaceConst::START_X;
            $maxCountRows = 0;
            $tableProcessed = 0;
            foreach ($tables as $table) {
                $countRows = count($table->rows);
                $maxCountRows = ($maxCountRows > $countRows) ?: $countRows;
                $maxCountSigns = 0;
                foreach ($table->rows as $row) {
                    $countSigns = strlen($row->columnName) + strlen($row->type) + strlen($row->typeSize) + strlen($row->defaultValue);
                    $maxCountSigns = ($maxCountSigns > $countRows) ?: $countSigns;
                }
                $tableWidth = $maxCountSigns * WorkspaceConst::SIGN_WIDTH + WorkspaceConst::FOREIGN_KEY_ICON_WIDTH;
                $tableWidth = ($tableWidth < WorkspaceConst::MIN_WIDTH_TABLE) ? WorkspaceConst::MIN_WIDTH_TABLE : $tableWidth;

                if ($distanceX + $tableWidth > WorkspaceConst::WIDTH) {
                    $distanceX = WorkspaceConst::START_X;
                    $distanceY += WorkspaceConst::ROW_HEIGHT * $maxCountRows + WorkspaceConst::TABLE_HEIGHT_SPACE;
                }
                $table->posX = $distanceX;
                $table->posY = $distanceY;

                $distanceX += $tableWidth + WorkspaceConst::TABLE_WIDTH_SPACE;
                $tableProcessed++;
            }
            $distanceY += WorkspaceConst::ROW_HEIGHT * $maxCountRows + WorkspaceConst::TABLE_HEIGHT_SPACE;

            $handledTables = array_merge($tables, $handledTables);
        }
        $tab->tables = $handledTables;

        return $tab;
    }

    public function get(): string
    {
        $data = new \stdClass();
        $isFirstTab = true;
        foreach ($this->databases as $database) {
            $tab = $this->getTab($database, $isFirstTab);
            $tab = $this->addForeignKeys($tab, $database);
            $tab = $this->setPosition($tab);

            $data->tabs[] = $tab;
            $isFirstTab = false;
        }

        return json_encode($data);
    }
}