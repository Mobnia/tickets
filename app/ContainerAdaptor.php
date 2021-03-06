<?php declare(strict_types=1);

namespace App;


use Illuminate\Container\Container;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
/**
 * Class ContainerAdaptor
 *
 * @package \App
 */
class ContainerAdaptor extends Container implements ContainerInterface
{

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        return $this->make($id);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        try {
            if ($this->offsetExists($id)) {
                return true;
            }
            $this->make($id);
            return true;
        } catch (\ReflectionException $e) {
            return false;
        }
    }
}