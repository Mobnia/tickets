<?php declare(strict_types=1);

namespace App\Controllers\Authentication;


use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\Route\Http\Exception\BadRequestException;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

/**
 * Class AuthController
 *
 * @package \App\Controllers\Authentication
 */
class AuthController
{
    private $authServer;
    private $response;

    /**
     * AuthController constructor.
     * @param AuthorizationServer $authServer
     * @param Response $response
     */
    public function __construct(AuthorizationServer $authServer, Response $response)
    {
        $this->authServer = $authServer;
        $this->response = $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @return array|\Psr\Http\Message\ResponseInterface
     * @throws BadRequestException
     */
    public function getToken(ServerRequestInterface $request)
    {
        try {
            return $this->authServer->respondToAccessTokenRequest($request, $this->response);

        } catch (OAuthServerException $exception) {
            return $this->returnAuthServerException($exception);

        } catch (Exception $exception) {
            return $this->returnOtherException($exception);

        }
    }

    /**
     * @param OAuthServerException $authServerException
     * @return array
     * @throws BadRequestException
     */
    private function returnAuthServerException(OAuthServerException $authServerException): array
    {
        throw new BadRequestException($authServerException->getMessage() . ' ' .  $authServerException->getHint());
    }

    /**
     * @param Exception $exception
     * @return array
     * @throws BadRequestException
     */
    private function returnOtherException(\Exception $exception): array
    {
        throw new BadRequestException($exception->getMessage() . ' ' .   $exception->getTrace());
    }
}