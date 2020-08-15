<?php

namespace Land15;

use Land15\Exception\NoPsrProcessException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Pipeline extends Resolve
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $unit = current($this->queue);
        $process = call_user_func($this->resolve, $unit);
        next($this->queue);
        if (is_string($process) && class_exists($process)) {
            $process = new $process;
        }
        if ($process instanceof MiddlewareInterface) {
            return $process->process($request, $this);
        }
        if ($process instanceof RequestHandlerInterface) {
            return $process->handle($request);
        }
        throw new NoPsrProcessException();
    }
}