<?php

namespace TheCodingMachine;

use Middlewares\Whoops;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Simplex\Container;
use TheCodingMachine\WhoopsMiddlewareServiceProvider;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Run;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest;

class WhoopsMiddlewareServiceProviderTest extends TestCase {

    public function testFactory() {
        $container = new Container([new WhoopsMiddlewareServiceProvider()]); // permet d'instancier un container et de lui donner le serviceProvider voulu.

        $middleware = $container->get(Whoops::class); // ajoute dans la variable le service Whoops
        /* @var $middleware Whoops::class */
        $this->assertInstanceOf(Whoops::class, $middleware); // vérifie si le service et bien dy type voulu

        $response = $middleware->process(new ServerRequest(), new class implements RequestHandlerInterface {

            /**
             * Handle the request and return a response.
             */
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                throw new \Exception("boom");
            }
        });

        $this->assertSame(500, $response->getStatusCode());
        $this->assertContains('Exception: boom in file ', (string) $response->getBody());
    }

    public function testFactoryWithCustomRun() {
        $container = new Container([new WhoopsMiddlewareServiceProvider()]);

        $container->set(Run::class, function() {
            $whoops = new Run();
            $whoops->pushHandler(new JsonResponseHandler());
            return $whoops;
        });

        $middleware = $container->get(Whoops::class);
        /* @var $middleware Whoops::class */
        $this->assertInstanceOf(Whoops::class, $middleware);

        $response = $middleware->process(new ServerRequest(), new class implements RequestHandlerInterface {

            /**
             * Handle the request and return a response.
             */
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                throw new \Exception("boom");
            }
        });

        $this->assertSame(500, $response->getStatusCode());
        $this->assertContains('{"error":{"type":"Exception","message":"boom"', (string) $response->getBody());
    }

    public function testFactoryWithNoErrorCatch() {
        $container = new Container([new WhoopsMiddlewareServiceProvider()]);

        $container->set('whoops.catchErrors', function() {
            return false;
        });

        $middleware = $container->get(Whoops::class);
        /* @var $middleware Whoops::class */
        $this->assertInstanceOf(Whoops::class, $middleware);

        $response = $middleware->process(new ServerRequest(), new class implements RequestHandlerInterface {

            /**
             * Handle the request and return a response.
             */
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                trigger_error('boom', E_USER_WARNING);
                return new JsonResponse('foo');
            }
        });

        echo (string) $response->getBody();
        $this->assertSame(200, $response->getStatusCode());
    }

}
