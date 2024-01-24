<?php

namespace Cubex\ApiFoundation\Exceptions;

class InvalidPayloadException extends \Exception
{
  protected $message = 'Invalid payload';
  protected $code = 400;

  public static function withType($type): static
  {
    $ex = new static();
    $ex->message .= ' ' . $type;
    return $ex;
  }
}
