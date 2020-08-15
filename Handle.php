<?php

namespace Land15;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Handle extends Resolve
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        reset($this->queue);
        return (new Pipeline($this->queue, $this->resolve))->handle($request);
    }
}