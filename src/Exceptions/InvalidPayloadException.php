<?php

namespace Cubex\ApiFoundation\Exceptions;

use Throwable;

class InvalidPayloadException extends \Exception
{
  /** @var string */
  protected $message = 'Invalid payload';

  /** @var int */
  protected $code = 400;

  /**
   * @param string         $type
   * @param string         $message
   * @param int            $code
   * @param Throwable|null $previous
   */
  public function __construct(string $type, string $message = "", int $code = 0, ?Throwable $previous = null)
  {
    parent::__construct($message, $code, $previous);
    $this->message .= ' ' . $type;
  }

  /**
   * @deprecated Use the constructor instead
   * @param string $type
   *
   * @return static
   */
  public static function withType(string $type)
  {
    return new static($type);
  }
}
