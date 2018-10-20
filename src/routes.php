<?php

use App\Controllers\UserController;
use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', function (Request $request, Response $response) {
    return $response->withJson(['spoon' => false]); // there is no spoon
});

$app->post('/users', UserController::class . ':create');
$app->patch('/users/{id}', UserController::class . ':update');

