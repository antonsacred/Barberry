<?php

namespace Barberry;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation;

class ApplicationTest extends TestCase
{
    public function testCanRun(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/Asd98';

        $app = new Application(new Config());
        $response = $app->run();

        self::assertInstanceOf(HttpFoundation\Response::class, $response);
        self::assertEquals('application/json', $response->headers->get('Content-type'));
    }

    public function testCanGetResources(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $app = new Application(new Config());
        self::assertInstanceOf(Resources::class, $app->resources());
    }

    public function testNullPostCaused400BadRequest(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $app = new Application(new Config());

        $response = $app->run();

        self::assertSame(400, $response->getStatusCode());
        self::assertEquals('{}', $response->getContent());
    }

    public function testOnEmptyGetRespond404NotFound(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '';

        $app = new Application(new Config());

        $response = $app->run();

        self::assertSame(404, $response->getStatusCode());
        self::assertEquals('{}', $response->getContent());
    }
}
