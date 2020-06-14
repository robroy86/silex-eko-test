<?php

$dbParams = array(
  'driver'   => 'pdo_mysql',
  'dbname'   => 'myDb',
  'host'     => '172.18.0.3', // this is my docker IP change to 'localhost' or '127.0.0.1'
  'user'     => 'user',
  'password' => 'test'
);

// Let's register the database connection - this will be later accessible from: $app['db.options']
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => $dbParams
));

use Silex\Provider\DoctrineServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\Mapping\Driver\PHPDriver;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Pimple\Container;

$baseDir = __DIR__ . '/../';


$app['db.builder'] = $app->factory(function () use ($app) {
  return new \Doctrine\DBAL\Query\QueryBuilder($app['db']);
});

$app->register(new DoctrineServiceProvider, array(
  'db.options' => $dbParams
));

/*
$driver = new PHPDriver($baseDir . 'src/models/Przystanki/');
$app["orm.em"]->getConfiguration()->setMetadataDriverImpl($driver);
*/

/*
$app->register(new DoctrineOrmServiceProvider, [
  'orm.proxies_dir'             => $baseDir . '../cache/doctrine/proxies/',
  'orm.auto_generate_proxies'   => $app['debug'],
  'orm.default_cache'           => 'array',
  'orm.em.options'              => [
    'mappings' => [
      [
        'type'                         => 'php',//'annotation',
        'namespace'                    => 'Przystanki\\Przystanek\\',
        'path'                         => $baseDir . 'src/models/Przystanki/',
        'use_simple_annotation_reader' => false,
      ],
    ],
  ]
]);
*/

$entitiesPath = array(__DIR__.'/../src/models/Przystanki');
$config = Setup::createAnnotationMetadataConfiguration($entitiesPath, $app['debug'], null, null, false);
$entityManager = EntityManager::create($dbParams, $config);
$app['db.em'] = $entityManager;