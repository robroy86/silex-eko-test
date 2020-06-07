<?php
use Symfony\Component\HttpKernel\Debug\ExceptionHandler;
use Silex\Provider\FormServiceProvider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// set the error handling
ini_set('display_errors', 1);
error_reporting(-1);

// web/index.php
require_once __DIR__.'/vendor/autoload.php';

require __DIR__.'/config/prod.php';
require __DIR__.'/src/controllers.php';

$app = new Silex\Application();
// set debug mode
$app['debug'] = true;
$app->register(new FormServiceProvider());

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

$app->run();