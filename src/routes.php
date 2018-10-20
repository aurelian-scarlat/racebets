<?php

use App\Controllers\DepositController;
use App\Controllers\UserController;
use App\Controllers\WithdrawalController;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', function (Request $request, Response $response) {
    return $response->withJson(['spoon' => false]); // there is no spoon
});

$app->post('/users', UserController::class . ':create');
$app->patch('/users/{id}', UserController::class . ':update');

$app->post('/deposits', DepositController::class . ':create');
$app->post('/withdrawals', WithdrawalController::class . ':create');