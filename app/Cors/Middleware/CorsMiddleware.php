<?php declare(strict_types=1);

namespace App\Cors\Middleware;


use League\Route\Http\Exception\BadRequestException;
use Neomerx\Cors\Analyzer;
use Neomerx\Cors\Contracts\AnalysisResultInterface;
use Neomerx\Cors\Strategies\Settings;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\ResponseFactory;

/**
 * Class CorsMiddleware
 *
 * @package \App\Cors\Middleware
 */
class CorsMiddleware implements MiddlewareInterface
{
    private $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $analyzer = Analyzer::instance($this->getSettings());
        $cors = $analyzer->analyze($request);

        $response = (new ResponseFactory())->createResponse();

        switch ($cors->getRequestType()) {
            case AnalysisResultInterface::ERR_ORIGIN_NOT_ALLOWED:
                $message = 'CORS request origin is not allowed.';
                break;
            case AnalysisResultInterface::ERR_METHOD_NOT_SUPPORTED:
                $message = 'CORS requested method is not supported.';
                break;
            case AnalysisResultInterface::ERR_HEADERS_NOT_SUPPORTED:
                $message = 'CORS requested header is not allowed.';
                break;
            case AnalysisResultInterface::TYPE_PRE_FLIGHT_REQUEST:
                $corsHeaders = $cors->getResponseHeaders();
                $response = $this->addHeaders($corsHeaders, $response);
                return $response->withStatus(200);
            case AnalysisResultInterface::TYPE_REQUEST_OUT_OF_CORS_SCOPE:
                return $handler->handle($request);
            default:
                /* Actual CORS request. */
                $response = $handler->handle($request);
                $corsHeaders = $cors->getResponseHeaders();
                return $this->addHeaders($corsHeaders, $response);
        }

        $exception = new BadRequestException($message);
        return $exception->buildJsonResponse($response);
    }

    public function handlePreFlightRequest(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $analyzer = Analyzer::instance($this->getSettings());
        $cors = $analyzer->analyze($request);

        if ($cors->getRequestType() === AnalysisResultInterface::TYPE_PRE_FLIGHT_REQUEST) {
            $corsHeaders = $cors->getResponseHeaders();
            $response = $this->addHeaders($corsHeaders, $response);
            return $response->withStatus(204);
        }

        $exception = new BadRequestException('Pre-Flight request could not be handled');
        return $exception->buildJsonResponse($response);
    }

    private function getSettings(): Settings
    {
        return $this->settings;
    }

    /**
     * @param array $corsHeaders
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    private function addHeaders(array $corsHeaders, ResponseInterface $response): ResponseInterface
    {
        foreach ($corsHeaders as $header => $value) {
            /* Diactoros errors on integer values. */
            if (false === is_array($value)) {
                $value = (string)$value;
            }
            $response = $response->withAddedHeader($header, $value);
        }
        return $response;
    }
}