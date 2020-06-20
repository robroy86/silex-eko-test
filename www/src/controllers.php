<?php

use Przystanki\Przystanek;

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

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\String\Slugger\AsciiSlugger;

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig', array());
})->bind('homepage');

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

$app->get('/dodaj', function (Request $request) use ($app) {
    $data = [
        'nazwa' => '',
        'adres' => '',
        'opis'  => '',
        'zdj1'  => '',
        'zdj2'  => '',
        'zdj3'  => ''
    ];

    return $app['twig']->render('dodaj.html.twig', array('errors' => [], 'data' => $data));
});

$app->get('/admin', function () use ($app) {
    if (0) { // just some testing
        $przystanek = new Przystanek(7);
        var_dump($przystanek->getImages());
        die();
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
    }
    $przystanek = new Przystanek();
    $przystanki = $przystanek->getPrzystankiNotReviewed();//Przystanek::
    $js = ['admin.js'];
    $app['twig']->addGlobal('js', $js);
    //$app['twig']->addExtension($js);
    return $app['twig']->render('admin.html.twig', array('przystanki' => $przystanki));
});

$app->post('/dodaj', function (Request $request) use ($app) {
    // rerwrite form data if post did not happen
    $sbmt = $request->request->get('nazwa');
    $submitted = $request->request->get('wyslij');
    if ($submitted !== NULL && $submitted === 'wyślij') {
        $data = array(
            'nazwa' => $request->request->get('nazwa'),
            'adres' => $request->request->get('adres'),
            'opis'  => $request->request->get('opis'),
            'zdj1'  => ( !empty($request->files->get('zdj1')) ? $request->files->get('zdj1') : ''),
            'zdj2'  => ( !empty($request->files->get('zdj2')) ? $request->files->get('zdj2') : ''),
            'zdj3'  => ( !empty($request->files->get('zdj3')) ? $request->files->get('zdj3') : '')
        );
    } else {// some default data for when the form is displayed the first time
        $data = array(
            'nazwa' => ( $request->request->get('nazwa') ? $request->request->get('nazwa')  : ''),
            'adres' => ( $request->request->get('adres') ? $request->request->get('adres')  : ''),
            'opis'  => ( $request->request->get('opis')  ? $request->request->get('opis')   : ''),
            'zdj1'  => '',
            'zdj2'  => '',
            'zdj3'  => '',
        );
    }

    $data['data'] = new DateTime('now');
    $data['reviewed'] = 0;
    //ToDo: check out this, because should be save while using doctri ne YES ? MySQL injection possible?
    $data['ip'] = $request->getClientIp();
    $data['browser'] = $request->headers->get('User-Agent');
    /*******************************************************
     *  TODO: Use FORM symphony/form $app['form.factory']->createBuilder
     * ****************************************************/
    $przystanek = new Przystanek($data);
    $errors = $app['validator']->validate($przystanek);

    if ($submitted && count($errors) == 0/*$form->isSubmitted() && $form->isValid() <=== TODO */) {
        // $form->getData() <- ToDO
        // This seems like a little dirty hack ... (before I master the $form component linked with the entity) get the file names only without file object (other wise the validator screems 'File not found')
        if ($data['zdj1']) $data['zdj1']->getClientOriginalName();
        if ($data['zdj2']) $data['zdj2']->getClientOriginalName();
        if ($data['zdj3']) $data['zdj3']->getClientOriginalName();
        $id = $przystanek->save($data);
        if ($id) {
            $file_main_upload_directory = __DIR__.'/../web/images/';
            if (!$app['filesystem']->exists($file_main_upload_directory)) {
                $app['filesystem']->mkdir($file_main_upload_directory);
            }
            if (!$app['filesystem']->exists($file_main_upload_directory)) // Check & Create the upload directory if it does not exists
                $app['filesystem']->mkdir($file_main_upload_directory, 0755);
              $files = [];
            $files[] = $request->files->get('zdj1');
            $files[] = $request->files->get('zdj2');
            $files[] = $request->files->get('zdj3');
            $file_names = [];
            if (count(array_filter($files,function($a) {return $a==null;}))!==3) {// If there is something uploaded...
                $i = 1;
                foreach ($files as &$file) {
                    $file_names["zdj$i"] = '';
                    if ($file) {
                        if (!$app['filesystem']->exists($file_main_upload_directory . $id)) // Create the destination directory ToDO: learn the slug tool
                            $app['filesystem']->mkdir($file_main_upload_directory . $id, 0755);
                        $file_extension = $file->guessExtension();
                        $file->move($file_main_upload_directory . $id, $file->getClientOriginalName());
                        $slugger = new AsciiSlugger();//safety reasons, don't trust user input
                        $slug_file_name = $slugger->slug($file->getClientOriginalName());
                        $slug_file_name = $slug_file_name . '-' . uniqid() . '.' . $file_extension;
                        $app['filesystem']->rename($file_main_upload_directory . $id . DIRECTORY_SEPARATOR . $file->getClientOriginalName(), $file_main_upload_directory . $id . DIRECTORY_SEPARATOR . $slug_file_name);
                        $file_names["zdj$i"] = $slug_file_name;
                    }
                    $i++;
                }
                $przystanek->saveNewImgFileNames($id, $file_names);
            }   
            return $app['twig']->render('success.html.twig');
        } else
            return $app['twig']->render('duplicate.html.twig');
    }

    $err_str = [];
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            //echo $error->getPropertyPath().' '.$error->getMessage()."\n";
            $err_str[] = $error->getMessage();
        }
        return $app['twig']->render('dodaj.html.twig', ['errors' => $err_str, 'data' => $data]);
    }
});

$app->post('/admin/details', function (Request $request) use ($app) {
    $id = (int) $request->request->get('id');
    $przystanek = new Przystanek($id);
    $przystanek->setPrzystanekAsReviewedById($id);
    $images = $przystanek->getImages();
    if (!count($images))
        $images = false;

    if ($przystanek->nazwa)
        return $app->json(['html' => $app['twig']->render('przystanek.details.html.twig', array('przystanek' => $przystanek, 'id' => $id, 'images' => $images))]);
    else
        return $app->json(json_encode(['html' => false, 'id' => $id]));
});
/* This is for production only - it messes up the debuging completly leaving you with an Internal Server Error (nothing more, nothing less)
$app->error(function (\Exception $e, Request $request, $code) use ($app) {
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