<?php

use Silex\Provider\MonologServiceProvider;
use Silex\Provider\WebProfilerServiceProvider;

// include the prod configuration
require __DIR__.'/prod.php';

// enable the debug mode
$app['debug'] = true;

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../var/logs/silex_dev.log',
));

$app->register(new WebProfilerServiceProvider(), array(
    'profiler.cache_dir' => __DIR__.'/../var/cache/profiler',
));

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('../templates');
$twig = new Twig_Environment($loader);
$twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) {
    // implement whatever logic you need to determine the asset path
    return './../web/css/';
}));
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../templates',
));