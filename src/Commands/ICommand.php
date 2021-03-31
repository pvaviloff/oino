<?php
declare(strict_types=1);

namespace Oino\Commands;

use \Oino\Args;

interface ICommand
{
    public function handle(Args $args);
}