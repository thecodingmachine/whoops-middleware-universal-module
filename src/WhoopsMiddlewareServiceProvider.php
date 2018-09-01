<?php

namespace TheCodingMachine;

use Middlewares\Whoops;
use Psr\Container\ContainerInterface;
use TheCodingMachine\Funky\Annotations\Factory;
use TheCodingMachine\Funky\ServiceProvider;
use Whoops\Run;
use Whoops\Util\SystemFacade;
use TheCodingMachine\Funky\Annotations\Tag;

class WhoopsMiddlewareServiceProvider extends ServiceProvider
{
    /**
     * @Factory(tags={@Tag(name=MiddlewareListServiceProvider::MIDDLEWARES_QUEUE, priority=MiddlewareOrder::EXCEPTION_EARLY)})
     */
    public static function createMiddleware(ContainerInterface $container) : Whoops
    {
        $run = $container->has(Run::class) ? $container->get(Run::class) : null;
        $systemFacade = $container->has(SystemFacade::class) ? $container->get(SystemFacade::class) : null;

        $catchErrors = $container->has('whoops.catchErrors') ? $container->get('whoops.catchErrors') : true;


        $whoops = new Whoops($run, $systemFacade);
        $whoops->catchErrors($catchErrors);

        if ($container->has('whoopsHandlerContainer')) {
            $whoops->handlerContainer($container->get('whoopsHandlerContainer'));
        }

        return $whoops;
    }
}
