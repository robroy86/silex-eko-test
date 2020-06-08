<?php 

namespace Przystanki;

use Doctrine\ORM\Mapping as ORM;

class Przystanek {
    private $id;
    private $nazwa;
    private $adres;
    private $opis;
    private $zdj1;
    private $zdj2;
    private $zdj3;
    private $reviewed;
    private $ip;
    private $browser;
    private $data;

    public $entityManager;

    function __construct(int $id = 0) {
        if ($id)
            die('asdasd');
        //$this->entityManager = $this->getDoctrine()->getManager();//->getManager('Przystanek');
    }

    function getPrzystankiNotReviewed() {
        global $app;
		//$sql = "SELECT * FROM eko_przystanki WHERE id > ?";
        //$przystanki = $app['db']->fetchAll($sql, array((int) "0"));

        $entityManager = $app['orm.em'];
        $qb = new \Doctrine\DBAL\Query\QueryBuilder($app['db']);
        $qb->select('*')->from('eko_przystanki');
        $qb->execute();
        $stm = $qb->execute();
        $data = $stm->fetchAll();
        var_dump($data);
        return $qb;

        return $przystanki;
    }
}