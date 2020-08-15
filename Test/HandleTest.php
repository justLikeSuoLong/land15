<?php

namespace Land15\Test;

use GuzzleHttp\Psr7\ServerRequest;
use Land15\Exception\NoPsrProcessException;
use Land15\Handle;
use Land15\Test\Handle as TestHandle;
use PHPUnit\Framework\TestCase;

class HandleTest extends TestCase
{
    /**
     * 完全符合预期的测试
     * 可以当做使用实力看待
     */
    function testSimplePipeline()
    {
        $handler = new Handle([
            Process1::class,
            Process2::class,
            TestHandle::class
        ]);
        $attributes = $handler->handle(new ServerRequest('GET', '/'));
        $expected = json_encode([
            'process1' => 'pass',
            'process2' => 'pass'
        ]);
        $this->assertEquals($expected, $attributes->getBody()->getContents());
    }

    /**
     * 如果传递了意外的 Handle, 则抛出特定异常
     */
    function testNoExpectedHandle()
    {
        $this->expectException(NoPsrProcessException::class);
        $handler = new Handle([
            Process1::class,
            HandleNaN::class
        ]);
        $handler->handle(new ServerRequest('GET', '/'));
    }

    /**
     * 如果传递了意外的 Middleware, 则抛出特定异常
     */
    function testNoExpectedMiddle()
    {
        $this->expectException(NoPsrProcessException::class);
        $handler = new Handle([
            ProcessNaN::class,
        ]);
        $handler->handle(new ServerRequest('GET', '/'));
    }
}