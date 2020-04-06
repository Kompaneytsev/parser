<?php declare(strict_types=1);

namespace Saver\Repository;

use DateTime;
use PDO;

class SupermetricsMessageRepository extends AbstractRepository
{
    protected const TABLE = 'supermetrics_message';

    public function addMessage(string $externalId, int $userId, string $message, string $type, DateTime $createdTime): void
    {
        $data = [
            'external_id' => $externalId,
            'user_id' => $userId,
            'message' => $message,
            'type' => $type,
            'created_time' => $createdTime->format('Y-m-d H:i:s'),
        ];
        $sql = sprintf(
            'INSERT INTO %s (external_id, user_id, message, type, created_time) VALUES (:external_id, :user_id, :message, :type, :created_time)',
            self::TABLE
        );
        $stmt = $this->client->prepare($sql);
        $stmt->execute($data);
    }

    public function getAverageCharacterLengthOfAPostMonth(): ?array {
        $sql = <<<SQL
SELECT AVG(LENGTH(message)) AS average, CONCAT(YEAR(created_time), '.', MONTH(created_time)) AS month
FROM supermetrics_message
GROUP BY CONCAT(YEAR(created_time), '.', MONTH(created_time))
ORDER BY month;
SQL;
        $stmt = $this->client->query($sql);
        $stat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($stat === false) {
            $result = null;
        } else {
            $result = $stat;
        }
        return $result;
    }

    public function getLongestPostByCharacterLengthMonth(): ?array {
        $sql = <<<SQL
SELECT external_id,
       LENGTH(m.message)                                        AS longest,
       CONCAT(YEAR(m.created_time), '.', MONTH(m.created_time)) AS month
FROM supermetrics_message AS m
         INNER JOIN (SELECT MAX(LENGTH(message))                                 AS longest,
                            CONCAT(YEAR(created_time), '.', MONTH(created_time)) AS month
                     FROM supermetrics_message
                     GROUP BY CONCAT(YEAR(created_time), '.', MONTH(created_time))
                     ORDER BY month) AS sub ON LENGTH(m.message) = sub.longest AND
                                               CONCAT(YEAR(m.created_time), '.', MONTH(m.created_time)) = sub.month
ORDER BY month;
SQL;
        $stmt = $this->client->query($sql);
        $stat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($stat === false) {
            $result = null;
        } else {
            $result = $stat;
        }
        return $result;
    }

    public function getTotalPostsSplitByWeek(): ?array {
        $sql = <<<SQL
SELECT COUNT(*) AS total, CONCAT(YEAR(created_time), '.', WEEKOFYEAR(created_time)) AS week
FROM supermetrics_message
GROUP BY CONCAT(YEAR(created_time), '.', WEEKOFYEAR(created_time))
ORDER BY week;
SQL;
        $stmt = $this->client->query($sql);
        $stat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($stat === false) {
            $result = null;
        } else {
            $result = $stat;
        }
        return $result;
    }

    public function getAverageNumberOfPostsPerUserMonth(): ?array {
        $sql = <<<SQL
SELECT AVG(posts) AS average, user_id, month
FROM (
         SELECT COUNT(*) AS posts, user_id, MONTH(created_time) AS month
         FROM supermetrics_message
         GROUP BY user_id, MONTH(created_time), YEAR(created_time)
         ORDER BY month
    ) AS sub
GROUP BY user_id, month;
SQL;
        $stmt = $this->client->query($sql);
        $stat = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($stat === false) {
            $result = null;
        } else {
            $result = $stat;
        }
        return $result;
    }
}