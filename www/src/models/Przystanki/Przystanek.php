<?php 

namespace Przystanki;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Query\QueryBuilder as QB;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\ClassMetadata;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;


/** 
 * @ORM\Entity()
 * @ORM\Table(name="eko_przystanki", indexes={@ORM\Index(name="index_adres", columns={"adres"})},
 *     uniqueConstraints={
 *     @ORM\UniqueConstraint(
 *         name="index_przystanki_unique_nazwa",
 *         columns={"nazwa"}
 *     )
 * })
 */

class Przystanek {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(type="string",unique=true)
     */
    private $nazwa;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $adres;
    /**
     * @var text
     * @ORM\Column(type="text")
     */
    private $opis;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $zdj1;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $zdj2;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $zdj3;
    /**
     * @var string
     * @ORM\Column(type="boolean")
     */
    private $reviewed;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $ip;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $browser;
    /**
     * @var datetime
     * @ORM\Column(type="datetime")
     */
    private $data;

    //public $entityManager;

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
        $app['db.em']->persist($this);
        $app['db.em']->flush();
        return true;
    }
}