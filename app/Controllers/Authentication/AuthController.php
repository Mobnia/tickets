<?php declare(strict_types=1);

namespace App\Controllers\Authentication;


use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
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

    public function __construct(AuthorizationServer $authServer, Response $response)
    {
        $this->authServer = $authServer;
        $this->response = $response;
    }

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

    private function returnAuthServerException(OAuthServerException $authServerException): array
    {
        return [
            'error_code' => 401,
            'possible_fix' => $authServerException->getHint(),
            'reason_phrase' => $authServerException->getMessage(),
        ];
    }

    private function returnOtherException(\Exception $exception): array
    {
        return [
            'error_code' => 401,
            'reason_phrase' => $exception->getMessage(),
            'stack' => $exception->getTrace()
        ];
    }
}