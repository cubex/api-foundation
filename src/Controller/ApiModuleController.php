<?php
namespace Cubex\ApiFoundation\Controller;

use Cubex\ApiFoundation\Auth\ApiAuthenticator;
use Cubex\ApiFoundation\Module\Procedures\ProcedureRoute;
use Generator;

class ApiModuleController extends ApiFoundationController
{
  /** @var ApiAuthenticator */
  protected ApiAuthenticator $_authenticator;

  /** @param Array<ProcedureRoute>|Generator<ProcedureRoute>  $_routes */
  public function __construct(protected array|Generator $_routes)
  {
  }

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

  /**
   * @return Generator<ProcedureRoute>
   */
  protected function _generateRoutes()
  {
    foreach($this->_routes as $route)
    {
      yield $route->setAuthenticator($this->_authenticator);
    }
  }
}
