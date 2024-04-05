<?php
namespace Cubex\ApiFoundation\Routing;

use Cubex\ApiFoundation\Module\Module;
use Packaged\Context\Context;
use Packaged\Routing\RequestCondition;

class ModuleCondition extends RequestCondition
{
  /**
   * @param class-string<Module> $_moduleClassName
   * @param string               $moduleBasePath
   */
  public function __construct(private string $_moduleClassName, string $moduleBasePath)
  {
    $this->path($moduleBasePath);
  }

  /**
   * @deprecated Use the constructor instead
   * @param Module $module
   *
   * @return static
   */
  public static function withModule(Module $module)
  {
    return new static($module::class, $module::getBasePath());
  }

  /**
   * @param Context $context
   *
   * @return void
   */
  public function complete(Context $context)
  {
    $context->meta()->set('api.module', $this->_moduleClassName);
  }
}
