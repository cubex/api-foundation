<?php
namespace Cubex\ApiFoundation\Routing;

use Cubex\ApiFoundation\Controller\ApiModuleController;
use Cubex\ApiFoundation\Module\Module;
use Packaged\Routing\Route;

class ModuleRoute extends Route
{
  protected $_module;

  public static function withModule(Module $module)
  {
    $route = new static();
    $route->_module = $module;
    $route->add(ModuleCondition::withModule($module));
    return $route;
  }

  public function getHandler()
  {
    return new ApiModuleController($this->_module);
  }
}
