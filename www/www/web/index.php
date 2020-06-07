<?php
// set the error handling
ini_set('display_errors', 1);
error_reporting(-1);

// web/index.php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__.'/../config/prod.php';
require __DIR__.'/../src/controllers.php';

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

$app->run();

