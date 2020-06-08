<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig', array());
})
->bind('homepage')
;

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

$app->get('/dodaj', function () use ($app) {
    return $app['twig']->render('dodaj.html.twig', array());
});

$app->get('/admin', function () use ($app) {
    $sql = "SELECT * FROM eko_przystanki WHERE id > ?";
    $przystanki = $app['db']->fetchAll($sql, array((int) "0"));
    return $app['twig']->render('admin.html.twig', array('przystanki' => $przystanki));
});


$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
		echo "Pow Pow " . __FILE__;
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});