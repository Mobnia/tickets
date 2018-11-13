<?php declare(strict_types=1);

namespace App\Controllers;


use App\Models\Base;
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
        $data = json_decode(json_encode($object), true);
        return [
            'data' => $data
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getAllRecords(Base $model)
    {
        $records = $model::all();
        return $records;
    }

/*    protected function getPaginatedRecords(Base $model)
    {
//        $numberOfRecords = $this->pdoConnection->query($model->generateCountSql());
//        $queryParams = $this->request->getQueryParams();
//
//        $page = isset($queryParams['page']) ? (int) $queryParams['page'] : 1;
//        $perPage = isset($queryParams['number']) ? (int) $queryParams['number'] : 20;
//        $pageCount = ceil($numberOfRecords/$perPage);

//        $records = $model::whereBetween('id', [1, 20])->get();
        $records = $model::paginate(10);

        return $records;
    }*/
}