<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Loader\Service\SuitableHandlerService;

$dotEnv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..', '.env.local');
$dotEnv->load();

foreach (range(1, 10) as $page) {
    SuitableHandlerService::download('https://api.supermetrics.com/assignment/posts?page=' . $page);
}
