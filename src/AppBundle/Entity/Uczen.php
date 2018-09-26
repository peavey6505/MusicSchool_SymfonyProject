<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Class Uczen
 * @package AppBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="uczniowie")
 *
 */


class Uczen
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
    public function getImie()
    {
        return $this->imie;
    }

    /**
     * @param mixed $imie
     */
    public function setImie($imie)
    {
        $this->imie = $imie;
    }

    /**
     * @return mixed
     */
    public function getNazwisko()
    {
        return $this->nazwisko;
    }

    /**
     * @param mixed $nazwisko
     */
    public function setNazwisko($nazwisko)
    {
        $this->nazwisko = $nazwisko;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @ORM\Column(type="string");
     */
    protected $imie;

    /**
     * @ORM\Column(type="string");
     */
    protected $nazwisko;

    /**
     * @ORM\Column(type="string");
     */
    protected $login;

    /**
     * @ORM\Column(type="string");
     */
    protected $haslo;

    /**
     * @return mixed
     */
    public function getHaslo()
    {
        return $this->haslo;
    }

    /**
     * @param mixed $haslo
     */
    public function setHaslo($haslo)
    {
        $this->haslo = $haslo;
    }
    /**
     * @ORM\Column(type="string");
     */
    protected $data_urodzenia;

    /**
     * @return mixed
     */
    public function getDataUrodzenia()
    {
        return $this->data_urodzenia;
    }

    /**
     * @param mixed $data_urodzenia
     */
    public function setDataUrodzenia($data_urodzenia)
    {
        $this->data_urodzenia = $data_urodzenia;
    }

    /**
     * @return mixed
     */
    public function getMiasto()
    {
        return $this->miasto;
    }

    /**
     * @param mixed $miasto
     */
    public function setMiasto($miasto)
    {
        $this->miasto = $miasto;
    }
    /**
     * @ORM\Column(type="string");
     */
    protected $miasto;



}