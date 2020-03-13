<?php
namespace Cubex\ApiFoundation\Module;

use Cubex\ApiFoundation\Module\Procedures\ProcedureRoute;

abstract class Module
{
  /**
   * @return ProcedureRoute[]|\Generator
   */
  abstract public function getRoutes();

  abstract public static function getBasePath(): string;
}
