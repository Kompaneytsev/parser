<?php declare(strict_types=1);

namespace Saver\Repository;

use DateTime;
use Loader\Dto\ResponseDto as DtoToSave;
use Saver\Dto\ResponseDto as DtoToGet;
use Loader\Entity\Response;
use PDO;

class ResponseRepository extends AbstractRepository
{
    protected const TABLE = 'response';

    public function add(DtoToSave $dto): void
    {
        $data = [
            'url' => $dto->url,
            'code' => $dto->code,
            'body' => $dto->body,
            'status' => Response::STATUS_UNPROCESSED,
        ];
        $sql = sprintf(
            'INSERT INTO %s (url, code, body, status) VALUES (:url, :code, :body, :status)',
            self::TABLE
        );
        $stmt = $this->client->prepare($sql);
        $stmt->execute($data);
    }

    public function getUnprocessed(): ?DtoToGet
    {
        $sql = sprintf(
            'SELECT * FROM %s WHERE status = "%s" and code = 200 LIMIT 1',
            self::TABLE,
            Response::STATUS_UNPROCESSED
        );
        $stmt = $this->client->query($sql);
        $result = null;
        if ($stmt !== false) {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($data !== false) {
                $result = new DtoToGet(
                    (int)$data['id'],
                    $data['url'],
                    (int)$data['code'],
                    $data['body'],
                    $data['status'],
                    DateTime::createFromFormat('Y-m-d H:i:s', $data['created_at']),
                    ($data['updated_at'] !== null) ? DateTime::createFromFormat('Y-m-d H:i:s', $data['updated_at']) : null
                );
            }
        }
        return $result;
    }

    public function setStatusById(int $id, string $status): void
    {
        $data = [
            'status' => $status,
            'id' => $id,
        ];
        $sql = sprintf(
            'UPDATE %s SET status = :status, updated_at = NOW() WHERE id = :id',
            self::TABLE
        );
        $stmt = $this->client->prepare($sql);
        $stmt->execute($data);
    }
}