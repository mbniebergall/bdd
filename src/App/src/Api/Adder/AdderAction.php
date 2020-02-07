<?php

namespace App\Api\Adder;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use ZF\ApiProblem\ApiProblem;

class AdderAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $number1 = $request->getAttribute('number1');
        $number2 = $request->getAttribute('number2');

//        if (strlen($name) <= 1) {
//            $response = new ApiProblem(
//                422,
//                'Name must be at least 2 characters'
//            );
//
//            return new JsonResponse(
//                $response->toArray(),
//                $response->status
//            );
//        }
//
//        if (preg_match('/[0-9]/', $number1)) {

        if (!is_numeric($number1) || !is_numeric($number2)) {
            $response = new ApiProblem(
                422,
                'Input must be numeric'
            );

            return new JsonResponse(
                $response->toArray(),
                $response->status
            );
        }

        return new JsonResponse(
            ['sum' => $number1 + $number2]
        );
    }
}
