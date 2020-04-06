<?php declare(strict_types=1);

namespace Saver\Repository;

class SupermetricsUserRepository extends AbstractRepository
{
    protected const TABLE = 'supermetrics_user';

    public function addUser(string $externalId, string $name): int
    {
        $data = [
            'external_id' => $externalId,
            'name' => $name,
        ];
        $sql = sprintf(
            'INSERT INTO %s (external_id, name) VALUES (:external_id, :name)',
            self::TABLE
        );
        $stmt = $this->client->prepare($sql);
        $stmt->execute($data);
        return (int) $this->client->lastInsertId('id');
    }

    public function getUserIdByExternalId(string $externalId): ?int
    {
        $sql = sprintf(
            'SELECT id FROM %s WHERE external_id = :external_id',
            self::TABLE
        );
        $stmt = $this->client->prepare($sql);
        $stmt->execute(['external_id' => $externalId]);

        $id = $stmt->fetchColumn();
        if ($id === false) {
            $result = null;
        } else {
            $result = (int) $id;
        }
        return $result;
    }
}