<?php declare(strict_types=1);

namespace App\Controllers;


use App\Models\Base;
use Aura\Filter\ValueFilter;
use Illuminate\Database\Eloquent\Collection;
use League\Route\Http\Exception\NotFoundException;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Class BaseController
 *
 * @package \App\Controllers
 */
class BaseController
{
    protected $filter;

    public function __construct(ValueFilter $filter)
    {
        $this->filter = $filter;
    }

    protected function returnResponse($object, $page = 1): array
    {
        $this->ensureObjectExists($object);

        $data = $this->convertObjectToArray($object);

        $paginator = $this->getPaginator($data);
        $paginator->setCurrentPage($page);

        return $paginator->haveToPaginate()?
            $this->returnPaginatedResponse($paginator) : $this->returnNonPaginatedResponse($data);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getAllRecords(Base $model)
    {
        $records = $model::all();
        return $records;
    }

    private function convertObjectToArray($object)
    {
        return json_decode(json_encode($object), true);
    }

    protected function getPaginator($data)
    {
        return new Pagerfanta(new ArrayAdapter($data));
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

    private function returnPaginatedResponse(Pagerfanta $paginator): array
    {
        return [
            'data' => $paginator->getCurrentPageResults(),
            'meta' => [
                'total_pages' => $paginator->getNbPages(),
                'next_page' => $paginator->getNextPage()
            ]
        ];
    }

    private function returnNonPaginatedResponse($data)
    {
        return [
            'data' => $data
        ];
    }

}