<?php 

namespace Przystanki;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Query\QueryBuilder as QB;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\ClassMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata as ValidatorMetaData;
use Symfony\Component\Validator\Constraints as Assert;

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
            return $this->getPrzystanekById($p, true);
        elseif(is_array($p)) {
            return $this->set($p);
        } else 
            return FALSE;
        //$this->entityManager = $this->getDoctrine()->getManager();//->getManager('Przystanek');
    }

    public static function loadValidatorMetadata(ValidatorMetaData $metadata) //ToDo how does this work?
    {
        $metadata->addPropertyConstraint('nazwa', new Assert\NotBlank(['message' => 'Nazwa nie może być pusta']));
        $metadata->addPropertyConstraint('adres', new Assert\NotBlank(['message' => 'Adres nie może być pusty']));
        /* TODO why is this not working as Expected (dbg: Internal Server Error it the only message)
        $metadata->addPropertyConstraint('nazwa', new Assert\Length([
            'min' => 2,
            'max' => 255,
            'minMessage' => 'Nazwa musi mieć minimum 2 znaki',
            'maxMessage' => 'Nazwa nie może być dłuższa niż 255 znaków',
            'allowEmptyString' => false,
        ]));
        
        $metadata->addPropertyConstraint('adres', new Assert\Length([
            'min' => 5,
            'max' => 255,
            'minMessage' => 'Adres musi mieć minimum 5 znaków',
            'maxMessage' => 'Adres nie może być dłuższa niż 255 znaków',
            'allowEmptyString' => false,
        ]));
        */
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

    function getPrzystanekById(int $id, $load = false) {
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
        if ($load)
            $this->set($przystanek[0]);

        return $przystanek[0];
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

    function set(array $data) {
        global $app;

        foreach ($data as $key => $val) {
            $this->__set($key, $val);
        }
        return true;
    }

    function save(array $data) {
        global $app;

       //foreach ($data as $key => $val) {
       //    $this->__set($key, $val);
       //}
        $app['db.em']->persist($this);
        $app['db.em']->flush();
        return true;
    }
}