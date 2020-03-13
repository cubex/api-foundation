<?php
namespace Cubex\ApiFoundation\Module;

use Cubex\ApiFoundation\Module\Procedures\ProcedureRoute;

interface Module
{
  /**
   * @return ProcedureRoute[]|\Generator
   */
  public function getRoutes();

  public function getUri(): string;
}
