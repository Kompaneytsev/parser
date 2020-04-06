<?php declare(strict_types=1);

namespace Loader\Entity;

use DateTime;

class Response
{
    public const STATUS_PROCESSED = 'processed';
    public const STATUS_UNPROCESSED = 'unprocessed';

    public int $id;
    public string $url;
    public int $code;
    public string $body;
    public string $status;
    public DateTime $createdAt;
    public DateTime $updatedAt;
}