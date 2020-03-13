<?php
namespace Cubex\ApiFoundation\Controller;

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

  protected function _generateRoutes()
  {
    foreach($this->_module->getRoutes() as $route)
    {
      yield $route;
    }
  }

}
