<?php declare(strict_types=1);

namespace Parser\Service\Handler;

interface HandlerInterface
{
    public function parse(string $body): void;
}