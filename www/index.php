<?php
use Symfony\Component\HttpKernel\Debug\ErrorHandler;
use Symfony\Component\HttpKernel\Debug\ExceptionHandler;
use Silex\Provider\FormServiceProvider;

require __DIR__.'/../config/prod.php';
require __DIR__.'/../src/controllers.php';

// set the error handling
ini_set('display_errors', 1);
error_reporting(-1);
ErrorHandler::register();
if ('cli' !== php_sapi_name()) {
  ExceptionHandler::register();
}

// web/index.php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
// set debug mode
$app['debug'] = true;
$app->register(new FormServiceProvider());

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

$app->run();