<?php declare(strict_types=1);

namespace App\Controllers;


use stdClass;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest as Request;
/**
 * Class BaseController
 *
 * @package \App\Controllers
 */
class BaseController
{
    protected $request;
    protected $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function convertObjectToArray($object): array
    {
        return json_decode(json_encode($object), true);
    }
}