<?php
// configure your app for the production environment
//$app['twig.path'] = array(__DIR__.'/../templates');
//$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');
//twig
use Symfony\Component\Validator\Constraints as Assert;
use Silex\Provider\FormServiceProvider;

use Symfony\Component\Filesystem\Filesystem;

$base_url = "./"; //"http://" . $_SERVER['SERVER_NAME'] . '/web';
$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1',
    'assets.version_format' => '%s?version=%s',
    'assets.named_packages' => array(
        'css' => array('version' => 'v1', 'base_path' => 'css/'),
        'js'  => array('version' => 'v1', 'base_path' => 'js/'),
        'img' => array('base_path' => 'images/'),
    ),
));

$app['debug'] = TRUE;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../templates',
));
$loader = new \Twig\Loader\FilesystemLoader('./../templates');
$twig = new \Twig\Environment($loader);

//load the validator class
$app->register(new Silex\Provider\ValidatorServiceProvider());
//load the form class : ToDO learn how to use it 
$app->register(new FormServiceProvider());

$filesystem = new Filesystem();

try { // https://symfony.com/doc/current/components/filesystem.html
    $filesystem->mkdir(sys_get_temp_dir().'/'.random_int(0, 1000));
    $app['filesystem'] = $filesystem;
} catch (IOExceptionInterface $exception) {
    echo "An error occurred while creating your directory at ".$exception->getPath();
}