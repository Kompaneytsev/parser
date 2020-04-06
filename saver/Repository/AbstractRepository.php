<?php declare(strict_types=1);

namespace Saver\Repository;

use PDO;

abstract class AbstractRepository
{
    protected PDO $client;

    public function __construct(string $host, string $db, string $user, string $pass)
    {
        $this->client = new PDO(sprintf('mysql:host=%s;dbname=%s', $host, $db), $user, $pass);
    }
}