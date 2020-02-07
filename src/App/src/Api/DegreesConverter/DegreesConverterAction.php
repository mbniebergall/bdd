<?php


namespace App\Api\DegreesConverter;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use ZF\ApiProblem\ApiProblem;

class DegreesConverterAction implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        if (empty($data)) {
            $data = json_decode($request->getBody()->getContents(), true);
        }

        if (!preg_match('/^[0-9]+$/', $data['temperature'])) {
            $response = new ApiProblem(
                422, 'it must be a number'
            );

// (0°C × 9/5) + 32 = 32°F

            return new JsonResponse(
                $response->toArray(), $response->status
            );
        }
        $converted = ($data['temperature'] * 9/5) + 32;
        return new JsonResponse(['degrees' => $converted]);
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
            ['problem' => 'password must be at least 8 characters'], 422
        );
    }

    protected function getCommonPasswords(): array
    {
        return ['password123',];
    }
}
