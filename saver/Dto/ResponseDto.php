<?php declare(strict_types=1);

namespace Saver\Dto;

use DateTime;

class ResponseDto
{
    public int $id;
    public string $url;
    public int $code;
    public string $body;
    public string $status;
    public DateTime $createdAt;
    public ?DateTime $updatedAt;

    public function __construct(int $id, string $url, int $code, string $body, string $status, DateTime $createdAt, ?DateTime $updatedAt)
    {
        $this->id = $id;
        $this->url = $url;
        $this->code = $code;
        $this->body = $body;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
}