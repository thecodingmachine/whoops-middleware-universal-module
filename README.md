# Whoops middleware universal module

This package integrates the franzliedke/whoops-middleware in any [container-interop](https://github.com/container-interop/service-provider) compatible framework/container.

## Installation

```
composer require thecodingmachine/whoops-middleware-universal-module
```

If your container supports autodiscovery by Discovery, there is nothing more to do.
Otherwise, you need to register the [`TheCodingMachine\WhoopsMiddlewareServiceProvider`](src/WhoopsMiddlewareServiceProvider.php) into your container.

Refer to your framework or container's documentation to learn how to register *service providers*.

## Usage

This module registers one service in your container:

- A PSR-7 Middleware under the `Franzl\Middleware\Whoops\Middleware` key.

Moreover, this module registers both keys in the routers list. If you use a compatible service provider (like [stratigility-harmony](https://github.com/thecodingmachine/stratigility-harmony)), the Whoops Middleware handler will be added automatically.
