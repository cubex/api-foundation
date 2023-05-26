<?php

namespace Cubex\ApiFoundation\Exceptions;

class InvalidPayloadException extends \Exception
{
  protected $message = 'Invalid payload';
  protected $code = 400;
}
