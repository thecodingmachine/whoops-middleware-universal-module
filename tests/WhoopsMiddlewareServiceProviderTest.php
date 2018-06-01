<?php

namespace TheCodingMachine;

use Middlewares\Whoops;
use PHPUnit\Framework\TestCase;
use Simplex\Container;
use TheCodingMachine\WhoopsMiddlewareServiceProvider;
use Whoops\Run;

class WhoopsMiddlewareServiceProviderTest extends TestCase {

    public function testFactory() {
        $container = new Container([new WhoopsMiddlewareServiceProvider()]); // permet d'instancier un container et de lui donner le serviceProvider voulu.
        $middleware = $container->get(Whoops::class); // ajoute dans la variable le service Whoops


        $this->assertInstanceOf(Whoops::class, $middleware); // vérifie si le service et bien dy type voulu
    }
}
