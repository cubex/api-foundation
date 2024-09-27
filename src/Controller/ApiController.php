<?php
namespace Cubex\ApiFoundation\Controller;

use Cubex\ApiFoundation\Auth\ApiAuthenticator;
use Cubex\ApiFoundation\Module\Module;
use Cubex\ApiFoundation\Routing\ModuleRoute;
use Generator;
use Packaged\Context\Context;
use Packaged\Http\Responses\JsonResponse;
use Packaged\Routing\Handler\FuncHandler;
use Packaged\Routing\Handler\Handler;
use Packaged\Routing\Route;

abstract class ApiController extends ApiFoundationController
{
  const VERSION = '1.0.0';

  /** @return JsonResponse */
  public function getVersion(): JsonResponse
  {
    return JsonResponse::create(static::VERSION);
  }

  /**
   * @param Context $context
   *
   * @return ApiAuthenticator
   */
  public function getAuthenticator(Context $context): ApiAuthenticator
  {
    return ApiAuthenticator::withContext($context);
  }

  /**
   * @param Context $c
   * @param         $handler
   *
   * @return array|callable|\Closure|mixed|FuncHandler|Handler|string
   */
  protected function _prepareHandler(Context $c, $handler)
  {
    if($handler instanceof ApiModuleController)
    {
      $handler->setAuthenticator($this->getAuthenticator($c));
    }
    return parent::_prepareHandler($c, $handler);
  }

  /** @return Generator<Module>|Array<Module> */
  abstract protected function _yieldModules();

  /** @return Generator<Route> */
  protected function _generateRoutes()
  {
    foreach($this->_yieldModules() as $module)
    {
      yield new ModuleRoute($module);
    }
    yield self::_route('version', 'version');
  }
}
