<?php

namespace TheCodingMachine;

use Franzl\Middleware\Whoops\ErrorMiddleware;
use Franzl\Middleware\Whoops\PSR15Middleware;
use Psr\Container\ContainerInterface;
use Interop\Container\ServiceProvider;
use Interop\Container\ServiceProviderInterface;

class WhoopsMiddlewareServiceProvider implements ServiceProviderInterface
{

    public function getFactories()
    {
        return [
            ErrorMiddleware::class => [self::class,'createErrorMiddleware'],
            PSR15Middleware::class => [self::class,'createMiddleware'],
        ];
    }

    public function getExtensions()
    {
        return [
            MiddlewareListServiceProvider::MIDDLEWARES_QUEUE => [self::class,'updatePriorityQueue']
        ];
    }

    public static function createErrorMiddleware() : ErrorMiddleware
    {
        return new ErrorMiddleware();
    }

    public static function createMiddleware() : PSR15Middleware
    {
        return new PSR15Middleware();
    }

    public static function updatePriorityQueue(ContainerInterface $container, \SplPriorityQueue $queue) : \SplPriorityQueue
    {
        $queue->insert($container->get(PSR15Middleware::class), MiddlewareOrder::EXCEPTION_EARLY);
        return $queue;
    }
}
