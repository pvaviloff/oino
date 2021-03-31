<?php
namespace Oino\Exceptions;

class TabNotFoundException extends \Exception
{
    protected $message = "Tab not found";
}