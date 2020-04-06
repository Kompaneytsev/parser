<?php declare(strict_types=1);

namespace Loader\Service\Handler;

use Loader\Dto\ResponseDto;

interface HandlerInterface
{
    public function save(string $url): void;
}