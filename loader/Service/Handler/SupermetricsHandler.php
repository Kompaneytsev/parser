<?php declare(strict_types=1);

namespace Loader\Service\Handler;

use GuzzleHttp\Client;
use Loader\Service\LoaderService;
use Saver\Repository\ResponseRepository;
use Saver\Repository\SupermetricsHandlerRepository;

class SupermetricsHandler implements HandlerInterface
{
    protected const REGISTER_URL = 'https://api.supermetrics.com/assignment/register';

    protected Client $client;
    protected LoaderService $loaderService;
    protected SupermetricsHandlerRepository $handlerRepository;
    protected ResponseRepository $responseRepository;

    public function __construct(Client $client,
                                LoaderService $loaderService,
                                SupermetricsHandlerRepository $handlerRepository,
                                ResponseRepository $responseRepository)
    {
        $this->client = $client;
        $this->loaderService = $loaderService;
        $this->handlerRepository = $handlerRepository;
        $this->responseRepository = $responseRepository;
    }

    public function save(string $url): void
    {
        $queryParams = $this->getUrlQuery($url);
        $queryParams['sl_token'] = $this->getToken();
        $url = $this->getUrlWithoutQuery($url) . '?' . http_build_query($queryParams);
        $response = $this->loaderService
            ->setUrl($url)
            ->request();
        $this->responseRepository->add($response);
    }

    protected function getToken(): string
    {
        $token = $this->handlerRepository->getToken();

        if ($token === null) {
            $response = $this->client->request('POST', self::REGISTER_URL, [
                'form_params' => [
                    'name' => getenv('SUPERMETRICS_NAME'),
                    'client_id' => getenv('SUPERMETRICS_CLIENT_ID'),
                    'email' => getenv('SUPERMETRICS_EMAIL'),
                ]
            ]);
            $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            $token = $data['data']['sl_token'] ?? '';
            $this->handlerRepository->addToken($token);
        }

        return $token;
    }

    protected function getUrlWithoutQuery(string $url): string
    {
        $parsed_url = parse_url($url);
        $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host = $parsed_url['host'] ?? '';
        $path = $parsed_url['path'] ?? '';
        return "$scheme$host$path";
    }

    protected function getUrlQuery(string $url): array
    {
        $query = parse_url($url, PHP_URL_QUERY);
        parse_str($query, $params);
        return $params;
    }
}