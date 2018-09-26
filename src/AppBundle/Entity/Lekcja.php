<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Class Lekcja
 * @package AppBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="lekcje", options={"collate"="utf8_polish_ci", "charset"="utf8"})
 *
 */


class Lekcja
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getIdUcznia()
    {
        return $this->id_ucznia;
    }

    /**
     * @param mixed $id_ucznia
     */
    public function setIdUcznia($id_ucznia)
    {
        $this->id_ucznia = $id_ucznia;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getTytulPiosenki()
    {
        return $this->tytul_piosenki;
    }

    /**
     * @param mixed $tytul_piosenki
     */
    public function setTytulPiosenki($tytul_piosenki)
    {
        $this->tytul_piosenki = $tytul_piosenki;
    }

    /**
     * @return mixed
     */
    public function getTempo()
    {
        return $this->tempo;
    }

    /**
     * @param mixed $tempo
     */
    public function setTempo($tempo)
    {
        $this->tempo = $tempo;
    }

    /**
     * @return mixed
     */
    public function getKomentarz()
    {
        return $this->komentarz;
    }

    /**
     * @param mixed $komentarz
     */
    public function setKomentarz($komentarz)
    {
        $this->komentarz = $komentarz;
    }

    /**
     * @ORM\Column(type="integer");
     */
    protected $id_ucznia;

    /**
     * @ORM\Column(type="date");
     */
    protected $data;
    /**
     * @ORM\Column(type="string");
     */
    protected $tytul_piosenki;
    /**
     * @ORM\Column(type="integer");
     */
    protected $tempo;
    /**
     * @ORM\Column(type="string");
     */
    protected $komentarz;


}