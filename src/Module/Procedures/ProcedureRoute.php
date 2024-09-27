<?php
namespace Cubex\ApiFoundation\Module\Procedures;

use Cubex\ApiFoundation\Auth\ApiAuthenticator;
use Cubex\ApiFoundation\Exceptions\InvalidPayloadException;
use Cubex\ApiTransport\Endpoints\ApiEndpoint;
use Cubex\ApiTransport\Payloads\AbstractPayload;
use Exception;
use Packaged\Context\Context;
use Packaged\Context\ContextAware;
use Packaged\Http\Responses\JsonResponse;
use Packaged\Routing\Handler\Handler;
use Packaged\Routing\RequestCondition;
use Packaged\Routing\Route;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Response;

class ProcedureRoute extends Route implements Handler
{
  /**
   * @param ApiEndpoint                     $_endpoint
   * @param class-string<AbstractProcedure> $_procedureClass
   */
  public function __construct(protected ApiEndpoint $_endpoint, protected string $_procedureClass)
  {
    $this->add(
      RequestCondition::i()
        ->path($this->_endpoint->getPath(), RequestCondition::TYPE_EXACT)
        ->method($this->_endpoint->getVerb())
    );
  }

  /**
   * @var ApiAuthenticator
   */
  protected $_authenticator;

  /**
   * @param ApiAuthenticator $authenticator
   *
   * @return ProcedureRoute
   */
  public function setAuthenticator(ApiAuthenticator $authenticator)
  {
    $this->_authenticator = $authenticator;
    return $this;
  }

  /**
   * @param Context $context
   *
   * @return bool
   */
  public function match(Context $context): bool
  {
    if(parent::match($context))
    {
      if($this->_endpoint->requiresAuthentication())
      {
        if(!$this->_authenticator || !$this->_authenticator->isAuthenticated())
        {
          return false;
        }
      }

      if(empty($this->_endpoint->getRequiredPermissions()))
      {
        return true;
      }

      return $this->_authenticator
        && $this->_authenticator->hasPermission(...$this->_endpoint->getRequiredPermissions());
    }
    return false;
  }

  /** @return $this */
  public function getHandler()
  {
    return $this;
  }

  /**
   * @throws InvalidPayloadException|ReflectionException|Exception
   */
  public function handle(Context $c): Response
  {
    $procedure = new $this->_procedureClass();
    if(!($procedure instanceof Procedure))
    {
      throw new Exception("Invalid procedure class given");
    }

    if($procedure instanceof ContextAware)
    {
      $procedure->setContext($c);
    }

    if(!method_exists($procedure, 'execute'))
    {
      return \Packaged\Http\Response::create("Unable to handle procedure: execute missing", 404);
    }

    $plClass = $this->_endpoint->getPayloadClass();

    if (!$plClass)
    {
      return JsonResponse::create($procedure->execute());
    }

    $payload = new $plClass();
    if($payload instanceof AbstractPayload)
    {
      $payload->fromContext($c);
    }

    if(!$payload->isValid())
    {
      throw new InvalidPayloadException((new ReflectionClass($payload))->getShortName());
    }

    //Execute with payload
    return JsonResponse::create($procedure->execute($payload));
  }
}
