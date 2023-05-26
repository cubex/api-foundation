<?php
namespace Cubex\ApiFoundation\Module\Procedures;

use Cubex\ApiFoundation\Auth\ApiAuthenticator;
use Cubex\ApiTransport\Endpoints\ApiEndpoint;
use Cubex\ApiTransport\Payloads\AbstractPayload;
use Packaged\Context\Context;
use Packaged\Context\ContextAware;
use Packaged\Http\Responses\JsonResponse;
use Packaged\Routing\Handler\Handler;
use Packaged\Routing\RequestCondition;
use Packaged\Routing\Route;
use Symfony\Component\HttpFoundation\Response;

class ProcedureRoute extends Route implements Handler
{
  /** @var */
  protected $_endpoint;
  /** @var string */
  protected $_procedureClass;

  public function __construct(ApiEndpoint $endpoint, string $procedureClass)
  {
    $this->_endpoint = $endpoint;
    $this->_procedureClass = $procedureClass;
    $this->add(
      RequestCondition::i()->path($endpoint->getPath(), RequestCondition::TYPE_EXACT)->method($endpoint->getVerb())
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

  public function getHandler()
  {
    return $this;
  }

  public function handle(Context $c): Response
  {
    $procedure = new $this->_procedureClass();
    if(!($procedure instanceof Procedure))
    {
      throw new \Exception("Invalid procedure class given");
    }

    if($procedure instanceof ContextAware)
    {
      $procedure->setContext($c);
    }

    if(method_exists($procedure, 'execute'))
    {
      $plClass = $this->_endpoint->getPayloadClass();
      if($plClass !== null)
      {
        $payload = new $plClass();
        if($payload instanceof AbstractPayload)
        {
          $payload->fromContext($c);
        }

        if (!$payload->isValid())
        {
          throw new \Exception("Invalid payload");
        }

        //Execute with payload
        $response = $procedure->execute($payload);
      }
      else
      {
        //Execute without payload
        $response = $procedure->execute();
      }
      return JsonResponse::create($response);
    }
    return \Packaged\Http\Response::create("Unable to handle procedure: execute missing", 404);
  }
}
