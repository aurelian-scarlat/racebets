<?php

use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Services\ContainerRegistrationService;
use Psr\Container\ContainerInterface;
use Slim\App;

$settings = require __DIR__ . '/config.php';
$app = new App($settings);

$container = $app->getContainer();
$container['db'] = function (ContainerInterface $container) {
    $settings = $container->get('settings')['db'];
    $pdo = new PDO($settings['dsn']);
    foreach ($settings['attributes'] as $attribute => $setting){
        $pdo->setAttribute($attribute, $setting);
    }
    return $pdo;
};

// Manually register the services
ContainerRegistrationService::register($container, UserRepository::class);
ContainerRegistrationService::register($container, TransactionRepository::class);

require __DIR__ . '/routes.php';

$app->run();
