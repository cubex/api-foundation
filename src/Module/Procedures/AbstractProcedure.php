<?php
namespace Cubex\ApiFoundation\Module\Procedures;

use Packaged\Context\ContextAware;
use Packaged\Context\ContextAwareTrait;
use Packaged\Context\WithContext;
use Packaged\Context\WithContextTrait;

abstract class AbstractProcedure implements Procedure, WithContext, ContextAware
{
  use WithContextTrait;
  use ContextAwareTrait;
}
