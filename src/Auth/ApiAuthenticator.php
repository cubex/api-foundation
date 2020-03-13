<?php
namespace Cubex\ApiFoundation\Auth;

use Cubex\ApiTransport\Permissions\ApiPermission;
use Packaged\Context\ContextAware;
use Packaged\Context\ContextAwareTrait;
use Packaged\Context\WithContext;
use Packaged\Context\WithContextTrait;

class ApiAuthenticator implements ContextAware, WithContext
{
  use WithContextTrait;
  use ContextAwareTrait;

  public function isAuthenticated(): bool
  {
    return false;
  }

  public function hasPermission(ApiPermission ...$permission)
  {
    return false;
  }
}
