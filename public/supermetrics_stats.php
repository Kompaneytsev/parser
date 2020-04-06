<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Saver\Repository\SupermetricsMessageRepository;

$dotEnv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..', '.env.local');
$dotEnv->load();

$messageRepository = new SupermetricsMessageRepository(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS')
);

header('Content-Type: application/json');

echo json_encode([
    'averageCharacterLengthOfAPostMonth' => $messageRepository->getAverageCharacterLengthOfAPostMonth(),
    'longestPostByCharacterLengthMonth' => $messageRepository->getLongestPostByCharacterLengthMonth(),
    'totalPostsSplitByWeek' => $messageRepository->getTotalPostsSplitByWeek(),
    'averageNumberOfPostsPerUserMonth' => $messageRepository->getAverageNumberOfPostsPerUserMonth(),
], JSON_THROW_ON_ERROR, 512);