<?php

namespace TheCodingMachine;

use Middlewares\Whoops;
use Psr\Container\ContainerInterface;
use Interop\Container\ServiceProviderInterface;
use Whoops\Run;
use Whoops\Util\SystemFacade;

class WhoopsMiddlewareServiceProvider implements ServiceProviderInterface
{

    public function getFactories()
    {
        return [
            Whoops::class => [self::class,'createMiddleware'], // whoops class instancie un service par l'appel de la function createMiddleware
        ];
    }

    public function getExtensions()
    {
        return [
            MiddlewareListServiceProvider::MIDDLEWARES_QUEUE => [self::class,'updatePriorityQueue']
        ];
    }

    public static function createMiddleware(ContainerInterface $container) : Whoops
    {
        $run = $container->has(Run::class) ? $container->get(Run::class) : null; // si le service Run existe
        $systemFacade = $container->has(SystemFacade::class) ? $container->get(SystemFacade::class) : null; // si system existe

        $catchErrors = $container->has('whoops.catchErrors') ?: true; // si whoops.catchError exist


        $whoops = new Whoops($run, $systemFacade);
        $whoops->catchErrors($catchErrors);

        if ($container->has('whoopsHandlerContainer')) {
            $whoops->handlerContainer($container->get('whoopsHandlerContainer'));
        }

        return $whoops;
    }

    public static function updatePriorityQueue(ContainerInterface $container, \SplPriorityQueue $queue) : \SplPriorityQueue
    {
        $queue->insert($container->get(Whoops::class), MiddlewareOrder::EXCEPTION_EARLY);
        return $queue;
    }
}
