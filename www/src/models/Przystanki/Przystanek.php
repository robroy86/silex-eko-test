<?php 

namespace Przystanki;

use Doctrine\ORM\Mapping as ORM;

use \Doctrine\ORM\EntityManager;
use \Doctrine\DBAL\Query\QueryBuilder as QB;

/**
 * @Entity
 * @Table(name="eko_przystanki")
 */
/**
 * Przystanek
 *
 * @ORM\Table(name="myDb.eko_przystanki", indexes={@ORM\Index(name="index_przystanki_unique_nazwa", columns={"nazwa"})})
 * @ORM\Entity
 */
class Przystanek {
    /**
     * @id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    /**
     * @nazwa
     */
    private $nazwa;
    /**
     * @adres
     */
    private $adres;
    /**
     * @opis
     * @Column(type="text")
     */
    private $opis;
    /**
     * @zdj1
     */
    private $zdj1;
    /**
     * @zdj2
     */
    private $zdj2;
    /**
     * @zdj3
     */
    private $zdj3;
    /**
     * @reviewed
     * @Column(type="boolean")
     */
    private $reviewed;
    /**
     * @ip
     */
    private $ip;
    /**
     * @browser
     */
    private $browser;
    /**
     * @ip
     * @Column(type="datetime")
     */
    private $data;

    public $entityManager;

    function __construct($p = null) {
        if (is_int($p))
            return $this->getPrzystanekById($p);
        elseif(is_array($p)) {
            $this->save($p);
        } else 
            return FALSE;
        //$this->entityManager = $this->getDoctrine()->getManager();//->getManager('Przystanek');
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
          $this->$property = $value;
        }
    
        return $this;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
          return $this->$property;
        }
    
        return FALSE;
    }

    function getPrzystankiNotReviewed() {
        global $app;

		//$sql = "SELECT * FROM eko_przystanki WHERE id > ?";
        //$przystanki = $app['db']->fetchAll($sql, array((int) "0"));
        $qb = new QB($app['db']);
        $qb->select('*')->from('eko_przystanki');
        $stm = $qb->execute();
        $przystanki = $stm->fetchAll();
        //var_dump($data);
        return $przystanki;
    }

    function getPrzystanekById(int $id) {
        global $app;

        if (!$id)
            return false;

        $qb = new QB($app['db']);
        $qb->select('*')
            ->from('eko_przystanki')
            ->where('id = :id')
            ->setMaxResults(1)
            ->setParameter('id', $id);
        $stm = $qb->execute();
        $przystanek = $stm->fetchAll();
        //var_dump($przystanek);die();
        return $przystanek;
    }

    function setPrzystanekAsReviewedById(int $id) {
        global $app;

        if (!$id)
            return false;

        $qb = new QB($app['db']);
        $qb->update('eko_przystanki')
            ->set('reviewed', '1')
            ->where('id = :id')
            ->setParameter('id', $id);
        $stm = $qb->execute();
        return (int)$stm;
    }

    function save(array $data) {
        global $app;

        foreach ($data as $key => $val) {
            $this->__set($key, $val);
        }
        var_dump($app['orm.em']->getRepository('Przystanek'));
        die();
        $app['orm.em']->persist($this);
        $app['orm.em']->flush();
        die();
        $em = EntityManager::create($app['db'], $config);
        die(' [ Q ] ');
    }
}