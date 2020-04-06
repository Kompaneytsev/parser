<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Loader\Entity\Response;
use Parser\Service\HandlerSwitchService;
use Saver\Repository\ResponseRepository;

$dotEnv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..', '.env.local');
$dotEnv->load();

$responseRepository = new ResponseRepository(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS')
);

foreach (range(1, 10) as $page) {
    $response = $responseRepository->getUnprocessed();
    if ($response === null) {
        continue;
    }
    $responseRepository->setStatusById($response->id, Response::STATUS_PROCESSED);
    HandlerSwitchService::switchByUrl($response->url)->parse($response->body);
}
