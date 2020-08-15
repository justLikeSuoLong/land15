<?php

namespace Land15;

use Closure;
use Land15\Exception\NoIterableQueueException;
use Land15\Exception\ProcessCountException;
use Psr\Http\Server\RequestHandlerInterface;

abstract class Resolve implements RequestHandlerInterface
{
    protected array $queue;

    protected ?Closure $resolve;

    public function __construct(array $queue, Closure $resolve = null)
    {
        if (!is_iterable($queue)) {
            throw new NoIterableQueueException();
        }
        if (count($queue) === 0) {
            throw new ProcessCountException();
        }
        if (is_null($resolve)) {
            $resolve = function ($process) {
                return $process;
            };
        }
        $this->queue = $queue;
        $this->resolve = $resolve;
    }
}