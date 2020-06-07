<?php
// Let's register the database connection
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'dbname'   => 'myDb',
        'host'     => '172.18.0.3', // tak zestawione w dockerze, normalnie localhost lub 127.0.0.1
        'user'     => 'user',
        'password' => 'test'
    ),
));