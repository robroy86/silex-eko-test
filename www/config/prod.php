<?php

// configure your app for the production environment

//$app['twig.path'] = array(__DIR__.'/../templates');
//$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');
//twig
use Symfony\Component\Validator\Constraints as Assert;
use Silex\Provider\FormServiceProvider;

$base_url = "./"; //"http://" . $_SERVER['SERVER_NAME'] . '/web';
$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1',
    'assets.version_format' => '%s?version=%s',
    'assets.named_packages' => array(
        'css' => array('version' => 'v1', 'base_path' => './css/'),
        'js'  => array('version' => 'v1', 'base_path' => 'js/'),
     //'images' => array('base_urls' => array('https://img.example.com')),
    ),
));

$app['debug'] = TRUE;
//Twig_Autoloader::register();
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../templates',
));
$loader = new \Twig\Loader\FilesystemLoader('./../templates');
$twig = new \Twig\Environment($loader);

//load the validator class
$app->register(new Silex\Provider\ValidatorServiceProvider());
//load the form class
$app->register(new FormServiceProvider());
