<?php
namespace Cubex\ApiFoundation\Controller;

use Cubex\ApiFoundation\Auth\ApiAuthenticator;
use Cubex\ApiFoundation\Module\Module;
use Cubex\ApiFoundation\Routing\ModuleRoute;
use Packaged\Context\Context;
use Packaged\Http\Responses\JsonResponse;

abstract class ApiController extends ApiFoundationController
{
  const VERSION = '1.0.0';

  public function getVersion()
  {
    return JsonResponse::create(static::VERSION);
  }

  public function getAuthenticator(Context $context)
  {
    return ApiAuthenticator::withContext($context);
  }

  protected function _prepareHandler(Context $c, $handler)
  {
    if($handler instanceof ApiModuleController)
    {
      $handler->setAuthenticator($this->getAuthenticator($c));
    }
    return parent::_prepareHandler($c, $handler);
  }

  /**
   * @return \Generator|Module[]
   */
  abstract protected function _yieldModules();

  protected function _generateRoutes()
  {
    foreach($this->_yieldModules() as $module)
    {
      yield ModuleRoute::withModule($module);
    }
    yield self::_route('version', 'version');
  }
}
