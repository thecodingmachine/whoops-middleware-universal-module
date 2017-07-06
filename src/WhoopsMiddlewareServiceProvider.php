<?php

namespace TheCodingMachine;

use Franzl\Middleware\Whoops\ErrorMiddleware;
use Franzl\Middleware\Whoops\Middleware;
use Franzl\Middleware\Whoops\PSR15Middleware;
use Interop\Container\ContainerInterface;
use Interop\Container\ServiceProvider;
use JsonSchema\Exception\InvalidArgumentException;

class WhoopsMiddlewareServiceProvider implements ServiceProvider
{

    public function getServices()
    {
        return [
            ErrorMiddleware::class => [self::class,'createErrorMiddleware'],
            Middleware::class => [self::class,'createMiddleware'],
            MiddlewareListServiceProvider::MIDDLEWARES_QUEUE => [self::class,'updatePriorityQueue']
        ];
    }

    public static function createErrorMiddleware() : ErrorMiddleware
    {
        return new ErrorMiddleware();
    }

    public static function createMiddleware() : Middleware
    {
        return new PSR15Middleware();
    }

    public static function updatePriorityQueue(ContainerInterface $container, callable $previous = null) : \SplPriorityQueue
    {
        if ($previous) {
            $priorityQueue = $previous();
            $priorityQueue->insert($container->get(Middleware::class), MiddlewareOrder::EXCEPTION_EARLY);
            return $priorityQueue;
        } else {
            throw new InvalidArgumentException("Could not find declaration for service '".MiddlewareListServiceProvider::MIDDLEWARES_QUEUE."'.");
        }
    }
}
