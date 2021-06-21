<?php

namespace App\Controllers;

use Interop\Container\ContainerInterface;

abstract class Container
{
    protected $ci;

    /**
     * Controller constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface  $container)
    {
        $this->ci = $container;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($this->ci->has($name)) {
            return $this->ci->get($name);
        }
    }
}
