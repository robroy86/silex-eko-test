<?php 

namespace Przystanki;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Query\QueryBuilder as QB;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\ClassMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata as ValidatorMetaData;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

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
     * @var bigint
     * @ORM\Column(type="bigint",length=10,options={"default": 0, "unsigned":true})
     */
    private $id_user = 0;
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
    private $zdj1 = '';
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $zdj2 = '';
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $zdj3 = '';
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
        $metadata->addConstraint(new UniqueEntity([
            'fields' => 'nazwa',
            'message' => 'Ktoś już dodał ten przystanek przed Toba, przykro nam, ale przystanek można unikalnie dodać tylko raz.'
        ]));

        $metadata->addPropertyConstraint('nazwa', new Assert\Length([
            'min' => 2,
            'max' => 255,
            'minMessage' => 'Nazwa musi mieć minimum {{ limit }} znaki',
            'maxMessage' => 'Nazwa nie może być dłuższa niż {{ limit }} znaków',
        ]));
        
        $metadata->addPropertyConstraint('adres', new NotBlank(['message' => 'Adres nie może być pusty']));
        $metadata->addPropertyConstraint('adres', new Assert\Length([
            'min' => 5,
            'max' => 255,
            'minMessage' => 'Adres musi mieć minimum {{ limit }} znaków',
            'maxMessage' => 'Adres nie może być dłuższy niż {{ limit }} znaków',
        ]));

        $metadata->addPropertyConstraint('zdj1', new Assert\Image([
            'minWidth' => 100,
            'maxWidth' => 1600,
            'minHeight' => 100,
            'maxHeight' => 1200,
            'detectCorrupted' => true,
            'corruptedMessage' => 'Wgrany plik zdjęcia 1 jest uszkodzony, wgraj inny lub spróbuj jeszcze raz skopiować z oryginalnego nośnika',
            'mimeTypes' => ['image/png','image/jpeg'],
            'minWidthMessage' => 'Obraz jest za mały ({{ width }}px). Wymagane minimum {{ min_width }}px szerokości',
            'maxWidthMessage' => 'Obraz za duży ({{ width }}px). Dozwolone max {{ max_width }}px szerokości.',
            'minHeightMessage' => 'Obraz jest za mały ({{ width }}px). Wymagane minimum {{ min_width }}px wysokości',
            'maxHeightMessage' => 'Obraz za duży ({{ width }}px). Dozwolone max {{ max_width }}px wysokości.',
            'maxSize'         => '2M',
            'maxSizeMessage'  => 'Plik jest za duży ({{ size }} {{ suffix }}). Maksymalny rozmiar pliku wynosi {{ limit }} {{ suffix }}.',
            'notFoundMessage' => 'Nie znaleziono zdjęcia 1'
        ]));

        $metadata->addPropertyConstraint('zdj2', new Assert\Image([
            'minWidth' => 400,
            'maxWidth' => 1600,
            'minHeight' => 300,
            'maxHeight' => 1200,
            'detectCorrupted' => true,
            'corruptedMessage' => 'Wgrany plik zdjęcia 2 jest uszkodzony, wgraj inny lub spróbuj jeszcze raz skopiować z oryginalnego nośnika',
            'mimeTypes' => ['image/png','image/jpeg'],
            'minWidthMessage' => 'Obraz jest za mały ({{ width }}px). Wymagane minimum {{ min_width }}px szerokości',
            'maxWidthMessage' => 'Obraz za duży ({{ width }}px). Dozwolone max {{ max_width }}px szerokości.',
            'minHeightMessage' => 'Obraz jest za mały ({{ width }}px). Wymagane minimum {{ min_width }}px wysokości',
            'maxHeightMessage' => 'Obraz za duży ({{ width }}px). Dozwolone max {{ max_width }}px wysokości.',
            'maxSize'         => '2M',
            'maxSizeMessage'  => 'Plik jest za duży ({{ size }} {{ suffix }}). Maksymalny rozmiar pliku wynosi {{ limit }} {{ suffix }}.',
            'notFoundMessage' => 'Nie znaleziono zdjęcia 2'
        ]));

        $metadata->addPropertyConstraint('zdj3', new Assert\Image([
            'minWidth' => 400,
            'maxWidth' => 1600,
            'minHeight' => 300,
            'maxHeight' => 1200,
            'detectCorrupted' => true,
            'corruptedMessage' => 'Wgrany plik zdjęcia 3 jest uszkodzony, wgraj inny lub spróbuj jeszcze raz skopiować z oryginalnego nośnika',
            'mimeTypes' => ['image/png','image/jpeg'],
            'minWidthMessage' => 'Obraz jest za mały ({{ width }}px). Wymagane minimum {{ min_width }}px szerokości',
            'maxWidthMessage' => 'Obraz za duży ({{ width }}px). Dozwolone max {{ max_width }}px szerokości.',
            'minHeightMessage' => 'Obraz jest za mały ({{ width }}px). Wymagane minimum {{ min_width }}px wysokości',
            'maxHeightMessage' => 'Obraz za duży ({{ width }}px). Dozwolone max {{ max_width }}px wysokości.',
            'maxSize'         => '2M',
            'maxSizeMessage'  => 'Plik jest za duży ({{ size }} {{ suffix }}). Maksymalny rozmiar pliku wynosi {{ limit }} {{ suffix }}.',
            'notFoundMessage' => 'Nie znaleziono zdjęcia 3'
        ]));

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
    /**
     * This is for Twig only - doesn't suppurt __get magic method
     */
    public function id() {
        return $this->id;
    }
    
    public function nazwa() {
        return $this->nazwa;
    }
    
    public function adres() {
        return $this->adres;
    }
    
    public function opis() {
        return $this->opis;
    }
    
    public function zdj1() {
        return $this->zdj1;
    }
    
    public function zdj2() {
        return $this->zdj2;
    }
    
    public function zdj3() {
        return $this->zdj3;
    }
    
    public function review() {
        return $this->review;
    }
    
    public function ip() {
        return $this->ip;
    }
    
    public function browser() {
        return $this->browser;
    }
    
    public function data() {
        return $this->data;
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

        $app['db.em']->persist($this);
        $app['db.em']->flush();
        return $this->id();
    }

    function saveNewImgFileNames(int $id, array $data) {
        global $app;

        if (!$id)
            return false;

        $qb = new QB($app['db']);
        $qb->update('eko_przystanki');

        if ($data['zdj1']) 
            $qb->set('zdj1', $qb->createNamedParameter($data['zdj1']));
        if ($data['zdj2']) 
            $qb->set('zdj2', $qb->createNamedParameter($data['zdj2']));
        if ($data['zdj3'])
            $qb->set('zdj3', $qb->createNamedParameter($data['zdj3']));

        $qb->where('id = :id')
            ->setParameter('id', $id);
        $stm = $qb->execute();
        return (int)$stm;
    }

    function getImages() {
        $images = [];
        for($i = 1; $i < 4; $i++) {
            $img = $this->__get('zdj' . $i);
            if ($img !== '')
                $images[] = $img;
        }
        return $images;
    }
}