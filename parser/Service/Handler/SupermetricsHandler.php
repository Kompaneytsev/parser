<?php declare(strict_types=1);

namespace Parser\Service\Handler;

use DateTime;
use Saver\Repository\SupermetricsMessageRepository;
use Saver\Repository\SupermetricsUserRepository;

class SupermetricsHandler implements HandlerInterface
{
    protected SupermetricsMessageRepository $messageRepository;
    protected SupermetricsUserRepository $userRepository;

    public function __construct(SupermetricsMessageRepository $messageRepository, SupermetricsUserRepository $userRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->userRepository = $userRepository;
    }

    public function parse(string $body): void
    {
        $body = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        $posts = $body['data']['posts'] ?? [];
        foreach ($posts as $post) {
            $userId = $this->userRepository->getUserIdByExternalId($post['from_id']);
            if ($userId === null) {
                $userId = $this->userRepository->addUser($post['from_id'], $post['from_name']);
            }
            $this->messageRepository->addMessage($post['id'], $userId, $post['message'], $post['type'], DateTime::createFromFormat(DateTime::ATOM, $post['created_time']));
        }
    }
}