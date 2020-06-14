<?php

//AnnotationRegistry::registerLoader([$loader, 'loadClass']);
use Przystanki\Przystanek;

//$a = new Przystanek();
//var_dump($a);
//die();
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

//TO DO make Controller classes

/* just for testing 
$przystanek = new Przystanek(1);
$przystanek->setPrzystanekAsReviewedById(1);
var_dump($przystanek->adres);

if ($przystanek->adres)
    return json_encode(['html' => $app['twig']->render('przystanek.details.html.twig', array('przystanek' => $przystanek))]);
*/

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig', array());
})
->bind('homepage')
;

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

$app->get('/dodaj', function () use ($app) {
    return $app['twig']->render('dodaj.html.twig', array('errors' => []));
});

$app->get('/admin', function () use ($app) {
    $przystanek = new Przystanek();
    //$p = [
    //    'id' => null,
    //    'nazwa' => 'ORM nazwa',
    //    'adres' => 'dasd asdsadasd',
    //    'opis' => ' asd asdasdasdasdas',
    //    'zdj1' => 'zas1',
    //    'zdj2' => 'zas2',
    //    'zdj3' => 'zas3',
    //    'reviewed' => 0,
    //    'ip' => '1.2.3.4',
    //    'browser' => 'headless',
    //    'data' => new DateTime('now')
    //];
    //var_dump($przystanek->save($p));
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
            'zdj1'  => $request->request->get('zdj1'),
            'zdj2'  => $request->request->get('zdj2'),
            'zdj3'  => $request->request->get('zdj3')
        );
    } else {// some default data for when the form is displayed the first time
        $data = array(
            'nazwa' => '',
            'adres' => '',
            'opis'  => '',
            'zdj1'  => '',
            'zdj2'  => '',
            'zdj3'  => ''
        );
    }
    $data['data'] = new DateTime('now');
    $data['reviewed'] = 0;
    //ToDo: check out this, because should be save while using doctrine YES ? MySQL injection possible?
    $data['ip'] = $request->getClientIp();
    $data['browser'] = $request->headers->get('User-Agent');
    // just setup a fresh $przystanek object (remove the example data)
    //var_dump($app['form.factory']);
    //die();
    /*******************************************************
     *  TODO: Use FORM symphony/form mig
     * *****************************************************
    $form = $app['form.factory']->createBuilder(FormType::class, $data/*, ['preserve_empty_strings' => true*//*)
        ->add('nazwa', TextType::class, array(
            'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))))
        ->add('adres', TextType::class, array(
            'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))))
        ->add('opis', TextType::class)
        ->add('zdj1', TextType::class)
        ->add('zdj2', TextType::class)
        ->add('zdj3', TextType::class)
        ->add('wyslij', SubmitType::class, [
            'label' => 'Wyślij propozycję'
        ])
        ->setMethod(Request::METHOD_POST)
        ->getForm();
//var_dump($form->createView());
//die();
    $form->handleRequest($request);
    */
    $przystanek = new Przystanek($data);
    $errors = $app['validator']->validate($przystanek);

    if ($submitted && count($errors) == 0/*$form->isSubmitted() && $form->isValid() <=== TODO */) {
        // $form->getData() holds the submitted values
        // but, the original `$task` variable has also been updated
        //$przystanek = $form->getData();
        //$przystanek->set($data);
        $ans = $przystanek->save($data);
        // ... perform some action, such as saving the task to the database
        // for example, if Task is a Doctrine entity, save it!
        // $entityManager = $this->getDoctrine()->getManager();
        // $entityManager->persist($task);
        // $entityManager->flush();
        //$przystanek->save($przystanek);
        if ($ans)
            return $app['twig']->render('success.html.twig');
        else
            return $app['twig']->render('duplicate.html.twig');
    }/* else {
        //var_dump($form->getErrors(true));
        var_dump($form->isSubmitted());
        die('dupa');
    }
    die(' [ /dodaj] END ');*/
    $err_str = [];
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            //echo $error->getPropertyPath().' '.$error->getMessage()."\n";
            $err_str[] = $error->getMessage();
        }
        return $app['twig']->render('dodaj.html.twig', array('errors' => $err_str));
    }
});

$app->post('/reviewe/reviewed', function (Request $request) use ($app) {
    $id = (int) $request->request->get('id');
    $przystanek = new Przystanek($id);
    $przystanek->setPrzystanekAsReviewedById($id);
    if ($przystanek->nazwa)
        return json_encode(['html' => $app['twig']->render('przystanek.details.html.twig', array('przystanek' => $przystanek))]);
    else
        return json_encode(['html' => false, 'id' => $id]);
});

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
