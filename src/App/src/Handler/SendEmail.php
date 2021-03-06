<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class SendEmail implements RequestHandlerInterface
{
    public function handle(
        ServerRequestInterface $request
    ): ResponseInterface {

        // send an email

        return new JsonResponse(
            ['recipient' => 'abc@example.com']
        );
    }
}
