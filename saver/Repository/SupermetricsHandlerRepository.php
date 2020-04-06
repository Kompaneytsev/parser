<?php declare(strict_types=1);

namespace Saver\Repository;

class SupermetricsHandlerRepository extends AbstractRepository
{
    protected const TABLE = 'supermetrics_handler';

    public function addToken(string $token): void
    {
        $data = [
            'token' => $token,
        ];
        $sql = sprintf(
            'INSERT INTO %s (token) VALUES (:token)',
            self::TABLE
        );
        $stmt = $this->client->prepare($sql);
        $stmt->execute($data);
    }

    public function getToken(): ?string
    {
        $sql = sprintf(
            'SELECT token FROM %s WHERE created_at > (NOW() - INTERVAL 15768 SECOND) LIMIT 1',
            self::TABLE
        );
        $stmt = $this->client->query($sql);
        $token = $stmt->fetchColumn();
        if ($token === false) {
            $result = null;
        } else {
            $result = (string) $token;
        }
        return $result;
    }
}