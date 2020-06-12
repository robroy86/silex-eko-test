<?php

//AnnotationRegistry::registerLoader([$loader, 'loadClass']);
use Przystanki\Przystanek;
//$a = new Przystanek();
//var_dump($a);
//die();

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
    $przystanek = new Przystanek();
        $p = [
            'id' => null,
            'nazwa' => 'ORM nazwa',
            'adres' => 'dasd asdsadasd',
            'opis' => ' asd asdasdasdasdas',
            'zdj1' => 'zas1',
            'zdj2' => 'zas2',
            'zdj3' => 'zas3',
            'reviewed' => 0,
            'ip' => '1.2.3.4',
            'browser' => 'headless',
            'data' => new DateTime('now')
        ];
        var_dump($przystanek->save($p));
    $przystanki = $przystanek->getPrzystankiNotReviewed();//Przystanek::
    $js = ['admin.js'];
    $app['twig']->addGlobal('js', $js);
    //$app['twig']->addExtension($js);
    return $app['twig']->render('admin.html.twig', array('przystanki' => $przystanki));
});

/*
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
*/