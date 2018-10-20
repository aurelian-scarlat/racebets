<?php
/**
 * Created by PhpStorm.
 * User: aurelian
 * Date: 20.10.2018
 * Time: 16:34
 */

namespace App\Services;


use Psr\Container\ContainerInterface;

class BaseService
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


}