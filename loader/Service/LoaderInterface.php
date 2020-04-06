<?php declare(strict_types=1);

namespace Loader\Service;

use Loader\Dto\ProxyDto;
use Loader\Dto\ResponseDto;

interface LoaderInterface
{
    public function setUrl(string $url): LoaderInterface;
    public function setProxy(ProxyDto $proxyDto): LoaderInterface;

    public function request(): ResponseDto;
}