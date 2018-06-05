# Whoops middleware universal module

This package integrates the `middlewares/whoops` library in any [container-interop](https://github.com/container-interop/service-provider) compatible framework/container.

## Installation

```
composer require thecodingmachine/whoops-middleware-universal-module
```

If your container supports autodiscovery by Discovery, there is nothing more to do.
Otherwise, you need to register the [`TheCodingMachine\WhoopsMiddlewareServiceProvider`](src/WhoopsMiddlewareServiceProvider.php) into your container.

Refer to your framework or container's documentation to learn how to register *service providers*.

## Usage

This module registers one service in your container:

- A PSR-15 Middleware under the `Middlewares\Whoops` key.

Moreover, this module registers both keys in the routers list. If you use a compatible service provider (like [stratigility-harmony](https://github.com/thecodingmachine/stratigility-harmony)), the Whoops Middleware handler will be added automatically.

## Expected values / services

This *service provider* expects the following configuration / services to be available:

| Name            | Compulsory | Description                            |
|-----------------|------------|----------------------------------------|
| `Whoops\Run`    | *no*       | An instance of the Whoops Run class. A default one will be provided if the service is not available in the container.  |
| `Whoops\Util\SystemFacade`   | *no*       | A Whoops SystemFacade used to build the Whoops `Run` instance. This is only used if no `Whoops\Run` instance is available in the container.  |
| `whoops.catchErrors`       | *no*       | (bool), defaults to true. Whether Whoops should turn errors into exceptions.  |
| `whoopsHandlerContainer`   | *no*       | A PSR-11 container that contains the mime type as key and the Whoops error handler as value. Useful to use a special default handler for a given mime type.  |


## Provided services

This *service provider* provides the following services:

| Service name                | Description                          |
|-----------------------------|--------------------------------------|
| `Middlewares\Whoops`        | The PSR-15 Whoops middleware |

## Extended services

This *service provider* extends those services:

| Name                        | Compulsory | Description                            |
|-----------------------------|------------|----------------------------------------|
| `MiddlewareListServiceProvider::MIDDLEWARES_QUEUE`              | *no*      | This service providers inserts the CSRF middleware in the middleware queue.                             |

