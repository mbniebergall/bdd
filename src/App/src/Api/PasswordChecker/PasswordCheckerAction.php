<?php

namespace App\Api\PasswordChecker;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use ZF\ApiProblem\ApiProblem;

class PasswordCheckerAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        if (empty($data)) {
            $data = json_decode($request->getBody()->getContents(), true);
        }

        if (strlen($data['password']) < 8) {
            $response = new ApiProblem(
                422,
                'password must be at least 8 characters'
            );

            return new JsonResponse(
                $response->toArray(),
                $response->status
            );
        }

        if (in_array($data['password'], $this->getCommonPasswords())) {
            $response = new ApiProblem(
                422,
                'no common passwords'
            );

            return new JsonResponse(
                $response->toArray(),
                $response->status
            );
        }

        if (preg_match('/^[a-zA-Z0-9]+$/', $data['password'])) {
            $response = new ApiProblem(
                422,
                'must have a special character'
            );
// (32°F − 32) × 5/9 = 0°C
            return new JsonResponse(
                $response->toArray(),
                $response->status
            );
        }

//
//        if (preg_match('/[0-9]/', $number1)) {

//        if (!is_numeric($number1) || !is_numeric($number2)) {
//            $response = new ApiProblem(
//                422,
//                'Input must be numeric'
//            );
//
//            return new JsonResponse(
//                $response->toArray(),
//                $response->status
//            );
//        }

        return new JsonResponse(
            ['problem' => 'password must be at least 8 characters'],
            422
        );
    }

    protected function getCommonPasswords(): array
    {
        return [
            'password123',
        ];
    }
}
