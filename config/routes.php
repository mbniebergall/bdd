<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Zend\Expressive\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    $app->get('/', App\Handler\HomePageHandler::class, 'home');
    $app->get('/api/ping', App\Handler\PingHandler::class, 'api.ping');

    $app->get('/api/helloworld/:name', App\Api\HelloWorld\HelloWorldAction::class, 'api-hello-world');

    $app->get('/api/adder/:number1/:number2', App\Api\Adder\AdderAction::class, 'api-adder');

    $app->post('/api/passwordchecker', App\Api\PasswordChecker\PasswordCheckerAction::class, 'api-passwordchecker');

    $app->post('/api/degrees_converter', App\Api\DegreesConverter\DegreesConverterAction::class, 'api-degreesconverter');


    $app->post(
        '/api/change',
        [
            App\Api\ChangeGenerator\ChangeGeneratorFilter::class,
            App\Api\ChangeGenerator\ChangeGeneratorAction::class,
        ],
        'api-change'
    );
};
