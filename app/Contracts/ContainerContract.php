<?php declare(strict_types=1);

namespace App\Contracts;


use Illuminate\Container\Container;

trait ContainerContract
{
    public $container;

    public function getContainer()
    {
        if(!$this->container) {
            $this->container = Container::getInstance();
        }

        return $this->container;
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
        return $this;
    }
}