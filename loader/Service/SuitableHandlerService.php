<?php declare(strict_types=1);

namespace Loader\Service;

use DomainException;
use GuzzleHttp\Client;
use Loader\Service\Handler\SupermetricsHandler;
use Saver\Repository\ResponseRepository;
use Saver\Repository\SupermetricsHandlerRepository;

/**
 * Depends on task we can easily add new handlers and rules for their inside download()
 */
class SuitableHandlerService
{
    public static function download(string $url): void
    {
        if (strpos($url, 'https://api.supermetrics.com/assignment/') !== false) {
            (new SupermetricsHandler(
                new Client(),
                new LoaderService(new Client()),
                new SupermetricsHandlerRepository(
                    getenv('DB_HOST'),
                    getenv('DB_NAME'),
                    getenv('DB_USER'),
                    getenv('DB_PASS')
                ),
                new ResponseRepository(
                    getenv('DB_HOST'),
                    getenv('DB_NAME'),
                    getenv('DB_USER'),
                    getenv('DB_PASS')
                )
            ))->save($url);
        } else{
            throw new DomainException('Cannot find any handler for this url ' . $url);
        }
    }
}