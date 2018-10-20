<?php
/**
 * Created by PhpStorm.
 * User: aurelian
 * Date: 20.10.2018
 * Time: 16:36
 */

namespace App\Services;


use Psr\Container\ContainerInterface;

class ContainerRegistrationService
{
    /**
     * @param ContainerInterface $container
     * @param string             $service
     * @param array              $args
     */
    public static function register(ContainerInterface $container, string $service, array $args = []): void
    {
        array_unshift($args, $container);
        $container[$service] = function () use ($service, $args){
            return new $service(...$args);
        };
    }
}