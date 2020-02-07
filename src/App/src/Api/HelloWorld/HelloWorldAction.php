<?php

namespace App\Api\HelloWorld;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use ZF\ApiProblem\ApiProblem;

class HelloWorldAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getAttribute('name');

        if (strlen($name) <= 1) {
            $response = new ApiProblem(
                422,
                'Name must be at least 2 characters'
            );

            return new JsonResponse(
                $response->toArray(),
                $response->status
            );
        }

        if (preg_match('/[0-9]/', $name)) {
            $response = new ApiProblem(
                422,
                'Name must not include digits'
            );

            return new JsonResponse(
                $response->toArray(),
                $response->status
            );
        }

        return new JsonResponse(
            [
                'greeting' => 'Hello ' . $name,
            ]
        );
    }
}
