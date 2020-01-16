<?php


namespace App\Api\ChangeGenerator;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use ZF\ApiProblem\ApiProblem;

class ChangeGeneratorFilter implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();
        if (empty($data)) {
            $data = json_decode($request->getBody()->getContents(), true);
        }
        if (empty($data)) {
            $data = $request->getQueryParams();
        }

        if (empty($data)) {
            $apiProblem = new ApiProblem(422, 'Missing required request body');

            return new JsonResponse(
                $apiProblem->toArray(),
                $apiProblem->status
            );
        }

        if (   !array_key_exists('tender', $data)
            || !is_numeric($data['tender'])
        ) {
            $apiProblem = new ApiProblem(422, 'Missing required numeric parameter: tender');

            return new JsonResponse(
                $apiProblem->toArray(),
                $apiProblem->status
            );
        }

        if ($data['tender'] < 0) {
            $apiProblem = new ApiProblem(422, 'Field tender must be positive');

            return new JsonResponse(
                $apiProblem->toArray(),
                $apiProblem->status
            );
        }

        if (   !array_key_exists('amount_due', $data)
            || !is_numeric($data['amount_due'])
        ) {
            $apiProblem = new ApiProblem(422, 'Missing required numeric parameter: amount_due');

            return new JsonResponse(
                $apiProblem->toArray(),
                $apiProblem->status
            );
        }

        if ($data['amount_due'] < 0) {
            $apiProblem = new ApiProblem(422, 'Field amount_due must be positive');

            return new JsonResponse(
                $apiProblem->toArray(),
                $apiProblem->status
            );
        }

        return $handler->handle($request->withParsedBody($data));
    }
}