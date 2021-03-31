<?php
namespace Oino\Validation;

use \Oino\Args;

interface IValidation
{
    public function validate(Args $args): bool;
}