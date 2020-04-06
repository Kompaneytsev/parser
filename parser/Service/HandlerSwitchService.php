<?php declare(strict_types=1);

namespace Parser\Service;

use DomainException;
use Parser\Service\Handler\HandlerInterface;
use Parser\Service\Handler\SupermetricsHandler;
use Saver\Repository\SupermetricsMessageRepository;
use Saver\Repository\SupermetricsUserRepository;

/**
 * Depends on task we can easily add new handlers and rules for their inside parse()
 */
class HandlerSwitchService
{
    public static function switchByUrl(string $url): HandlerInterface
    {
        if (strpos($url, 'https://api.supermetrics.com/assignment/') !== false) {
            return new SupermetricsHandler(new SupermetricsMessageRepository(
                getenv('DB_HOST'),
                getenv('DB_NAME'),
                getenv('DB_USER'),
                getenv('DB_PASS')
            ), new SupermetricsUserRepository(
                getenv('DB_HOST'),
                getenv('DB_NAME'),
                getenv('DB_USER'),
                getenv('DB_PASS')
            ));
        }
        throw new DomainException('Cannot find any handler for this url');
    }
}