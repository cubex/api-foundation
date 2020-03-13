<?php
namespace Cubex\ApiFoundation\Controller;

use Cubex\ApiFoundation\Auth\ApiAuthenticator;
use Cubex\ApiFoundation\Module\Module;

class ApiModuleController extends ApiFoundationController
{
  /**
   * @var Module
   */
  protected $_module;

  public function __construct(Module $module)
  {
    $this->_module = $module;
  }

  protected $_authenticator;

  /**
   * @param ApiAuthenticator $authenticator
   *
   * @return ApiModuleController
   */
  public function setAuthenticator(ApiAuthenticator $authenticator)
  {
    $this->_authenticator = $authenticator;
    return $this;
  }

  protected function _generateRoutes()
  {
    foreach($this->_module->getRoutes() as $route)
    {
      yield $route->setAuthenticator($this->_authenticator);
    }
  }

}
