<?php
namespace Cubex\ApiFoundation\Routing;

use Cubex\ApiFoundation\Module\Module;
use Packaged\Context\Context;
use Packaged\Routing\RequestCondition;

class ModuleCondition extends RequestCondition
{
  private $_module;

  public static function withModule(Module $module)
  {
    $condition = new static();
    $condition->_module = $module;
    $condition->path($module::getBasePath());
    return $condition;
  }

  public function complete(Context $context)
  {
    $context->meta()->set('api.module', get_class($this->_module));
  }

}
