<?php
namespace Oino\Validation;

use \Oino\Args;
use \Oino\Constants\CommandConst;

class ImportValidation implements IValidation
{
    public function validate(Args $args): bool
    {
        return is_string($args->getValue(CommandConst::FILE_OPTION)) &&
            is_string($args->getValue(CommandConst::TAB_OPTION)) &&
            file_exists($args->getValue(CommandConst::FILE_OPTION));
    }
}