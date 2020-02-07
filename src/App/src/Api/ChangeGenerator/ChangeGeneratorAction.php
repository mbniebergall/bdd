<?php


namespace App\Api\ChangeGenerator;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class ChangeGeneratorAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getParsedBody();

        return new JsonResponse(
            [
                'change' => number_format($params['tender'] - $params['amount_due'], 2)
            ]
        );
    }
}
