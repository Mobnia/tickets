<?php declare(strict_types=1);

namespace App\Controllers;


use App\Models\Base;
use Illuminate\Database\Eloquent\Collection;
use League\Route\Http\Exception\NotFoundException;
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

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function convertObjectToArray($object): array
    {
        $this->ensureObjectExists($object);

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

    /**
     * @param $object
     * @throws NotFoundException
     */
    private function ensureObjectExists($object): void
    {
        $boolean = true;

        if ($object instanceof  Collection) {
            $boolean = empty($object->all()) ? false : true;
        }
        else if (!isset($object)) {
            $boolean = false;
        }

        if ($boolean == false) {
            throw new NotFoundException("The requested resource doesn't exist on this server");
        }
    }

}