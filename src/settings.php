<?php
return [
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false, // allows the web server to set the content length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
        ],

        // Database to access
        'db' => [
            'dsn' => 'sqlite',
            'database' => __DIR__ . '/include/blog.db',
        ],
    ],
];
