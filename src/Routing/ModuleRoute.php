<?php
namespace Cubex\ApiFoundation\Routing;

use Cubex\ApiFoundation\Controller\ApiModuleController;
use Cubex\ApiFoundation\Module\Module;
use Packaged\Routing\Route;

class ModuleRoute extends Route
{
  /** @param Module $_module */
  public function __construct(protected Module $_module)
  {
    $this->add(new ModuleCondition($this->_module::class, $this->_module::getBasePath()));
  }

  /**
   * @deprecated Use the constructor instead
   * @param Module $module
   *
   * @return static
   */
  public static function withModule(Module $module)
  {
    return new static($module);
  }

  /** @return ApiModuleController */
  public function getHandler()
  {
    return new ApiModuleController($this->_module->getRoutes());
  }
}
