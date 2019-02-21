<?php declare(strict_types=1);

namespace App\Controllers;


use App\Models\Base;
use Aura\Filter\ValueFilter;
use Illuminate\Database\Eloquent\Collection;
use League\Route\Http\Exception\NotFoundException;
use Zend\Diactoros\ServerRequest as Request;
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
    protected function getAllRecords(Base $model): Collection
    {
        return $model::all();
    }

    protected function getPage(Request $request): int
    {
        $queries = $request->getQueryParams();
        return isset($queries['page']) ? (int) $queries['page'] : 1;
    }

    private function convertObjectToArray($object)
    {
        return json_decode(json_encode($object), true);
    }

    protected function getPaginator($data): Pagerfanta
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
            $boolean = !empty($object->all());
        }
        else if (!isset($object)) {
            $boolean = false;
        }

        if ($boolean === false) {
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

    private function returnNonPaginatedResponse($data): array
    {
        return [
            'data' => $data
        ];
    }

}