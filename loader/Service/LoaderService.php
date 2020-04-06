<?php declare(strict_types=1);

namespace Loader\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Loader\Dto\ProxyDto;
use Loader\Dto\ResponseDto;

class LoaderService implements LoaderInterface
{
    protected Client $client;

    protected string $url;
    protected array $query;
    protected string $method;
    protected ?ProxyDto $proxy = null;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param ProxyDto $proxy
     * @return $this
     */
    public function setProxy(ProxyDto $proxy): self
    {
        $this->proxy = $proxy;
        return $this;
    }

    public function request(): ResponseDto
    {
        $proxy = $this->proxy !== null ? $this->proxy->host . ':' . $this->proxy->port : null;
        $request = new Request('GET', $this->url, ['proxy' => $proxy]);
        $result = $this->client->send($request);

        $response = new ResponseDto();
        $response->url = $this->url;
        $response->body = $result->getBody()->getContents();
        $response->code = $result->getStatusCode();
        return $response;
    }
}