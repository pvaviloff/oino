<?php

namespace Oino\Services\MigrationExecutors;

interface MigrationExecutor
{
    public function execute(array $tables): void;
}