<?php
namespace Oino\Exceptions;

class ExportFileNotFoundException extends \Exception
{
    protected $message = "Import json file not found";
}