<?php
// set the error handling
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);
// namespaces
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Silex\Provider\DoctrineServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Validator\Constraints as Assert;
use Silex\Provider\FormServiceProvider;

// autoload
$loader = require_once __DIR__.'/../vendor/autoload.php';
//$loader->add('Przystanki', __DIR__.'/../src/przystanki/');
$loader->add('Geocoding', __DIR__.'/../src/geocoding/');

use Przystanki\Przystanek;
//use Geocoding\Geocoder;

$app = new Silex\Application();

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

require __DIR__.'/../config/prod.php';
require __DIR__.'/../config/db.php';
require __DIR__.'/../src/controllers.php';

$app->run();

