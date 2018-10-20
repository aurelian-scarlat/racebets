<?php
/**
 * Created by PhpStorm.
 * User: aurelian
 * Date: 20.10.2018
 * Time: 15:26
 */

return [
    'settings' => [
        'displayErrorDetails' => true,
        'db' => [
            'dsn' => 'sqlite:' . __DIR__ . '/../db.sqlite',
            'attributes' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        ]
    ]
];
