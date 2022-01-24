<?php

namespace thecodeholic\phpmvc\exception;


/**
 * Class MethodNotAllowedException
 *
 * @package thecodeholic\phpmvc\exception
 * @author Ar Rakin <rakinar2@gmail.com>
 */
class MethodNotAllowedException extends \Exception
{
    protected $message = 'The request method is not allowed for this URL';
    protected $code = 405;
}