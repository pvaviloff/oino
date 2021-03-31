<?php
namespace Oino\Validation;

use Oino\Args;
use Oino\Constants\CommandConst;

class ExportValidation implements IValidation
{
    public function validate(Args $args): bool
    {
        return is_dir($args->getValue(CommandConst::EXPORT_DIR));
    }
}