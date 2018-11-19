<?php declare(strict_types=1);

namespace App\Middleware;


use App\Authentication\Entities\User;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class AuthenticationMiddleware
 *
 * @package \App\Middleware
 */
class AuthenticationMiddleware implements MiddlewareInterface
{
    private $authServer;

    public function __construct(AuthorizationServer $authServer)
    {
        $this->authServer = $authServer;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $authRequest = $this->authServer->validateAuthorizationRequest($request);

            $authRequest->setUser(new User(1));

            $authRequest->setAuthorizationApproved(true);

            $response = $handler->handle($request);

            return $this->authServer->completeAuthorizationRequest($authRequest, $response);

        } catch (OAuthServerException $authServerException) {
            return $this->returnAuthServerException($authServerException);

        } catch (\Exception $exception) {
            return $this->returnOtherException($exception);
        }
    }

    private function returnAuthServerException($authServerException): ResponseInterface
    {
        $response = new JsonResponse([
            'error_code' => 401,
            'possible_fix' => $authServerException->getHint(),
            'reason_phrase' => $authServerException->getMessage(),
        ]);

        return $response->withStatus(401);
    }

    private function returnOtherException(\Exception $exception): ResponseInterface
    {
        $response = new JsonResponse([
            'error_code' => 401,
            'reason_phrase' => $exception->getMessage(),
            'stack' => $exception->getTrace()
        ]);

        return $response->withStatus(401);
    }
}