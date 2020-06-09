<?php
// Let's register the database connection
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'dbname'   => 'myDb',
        'host'     => '172.18.0.2', // this is my docker IP change to 'localhost' or '127.0.0.1'
        'user'     => 'user',
        'password' => 'test'
    ),
));

use Silex\Provider\DoctrineServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Doctrine\Common\Annotations\AnnotationRegistry;

$baseDir = __DIR__ . '/../';

$app->register(new DoctrineOrmServiceProvider, [
    'orm.proxies_dir'             => $baseDir . 'src/models/Przystanki',
    'orm.auto_generate_proxies'   => $app['debug'],
    'orm.em.options'              => [
      'mappings' => [
        [
          'type'                         => 'annotation',
          'namespace'                    => 'Przystanki\\Przystanek\\',
          'path'                         => $baseDir . 'src/models/Przystanki/',
          'use_simple_annotation_reader' => false,
        ],
      ],
    ]
]);

$app['db.builder'] = $app->factory(function () use ($app) {
  return new \Doctrine\DBAL\Query\QueryBuilder($app['db']);
});

/*
$driver = new PHPDriver($baseDir . 'src/models/Przystanki');
$app['orm.em']->getConfiguration()->setMetadataDriverImpl($driver);
*/