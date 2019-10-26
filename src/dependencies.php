<?php
// DIC configuration

$container = $app->getContainer();

// csrf
$container['csrf'] = function($c) {
    $guard = new \Slim\Csrf\Guard;
    $guard->setPersistentTokenMode(true);
    return $guard;
};

// view renderer
$container['view'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    $view = new \Slim\Views\Twig($settings['template_path'], [
        'debug' => true
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUri()
    ));
    // allows the use of {{ dump() }}
    $view->addExtension(new \Twig_Extension_Debug());
    return $view;
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// database
$container['db'] = function($c) {
    $db = $c->get('settings')['db'];
    $pdo = new PDO($db['dsn'] . ':' . $db['database']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};
