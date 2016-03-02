<?php

namespace TheCodingMachine;

use Franzl\Middleware\Whoops\ErrorMiddleware;
use Franzl\Middleware\Whoops\Middleware;
use Interop\Container\ContainerInterface;
use Interop\Container\ServiceProvider;
use JsonSchema\Exception\InvalidArgumentException;

class WhoopsMiddlewareServiceProvider implements ServiceProvider
{

    public static function getServices()
    {
        return [
            ErrorMiddleware::class => 'createErrorMiddleware',
            Middleware::class => 'createMiddleware',
            'middlewaresQueue' => 'updatePriorityQueue'
        ];
    }

    public static function createErrorMiddleware() : ErrorMiddleware
    {
        return new ErrorMiddleware();
    }

    public static function createMiddleware() : Middleware
    {
        return new Middleware();
    }

    public static function updatePriorityQueue(ContainerInterface $container, callable $previous = null) : \SplPriorityQueue
    {
        if ($previous) {
            $priorityQueue = $previous();
            $priorityQueue->insert($container->get(ErrorMiddleware::class), 3000);
            return $priorityQueue;
        } else {
            throw new InvalidArgumentException("Could not find declaration for service 'middlewaresQueue'.");
        }
    }
}
