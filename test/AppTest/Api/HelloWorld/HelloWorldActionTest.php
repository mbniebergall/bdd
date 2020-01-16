<?php

declare(strict_types=1);

namespace App\Api\HelloWorld;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class HelloWorldActionTest extends TestCase
{
    public function testRejectsShortName()
    {
        $requestMock = $this->getMockBuilder(ServerRequestInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAttribute'])
            ->getMockForAbstractClass();

        $requestMock->expects($this->once())
            ->method('getAttribute')
            ->with('name')
            ->willReturn('A');

        $helloWorld = new HelloWorldAction;
        $response = $helloWorld->handle($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame($response->getStatusCode(), 422);
    }
}
