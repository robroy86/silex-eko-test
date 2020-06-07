<?php
require_once('../vendor/autoload.php');

// configure your app for the production environment

//$app['twig.path'] = array(__DIR__.'/../templates');
//$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');
//twig
$app['debug'] = TRUE;
Twig_Autoloader::register();
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../templates',
));
$loader = new Twig_Loader_Filesystem('./../templates');
$twig = new Twig_Environment($loader);
$twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) {
    // implement whatever logic you need to determine the asset path
    return './../web/css/';
}));