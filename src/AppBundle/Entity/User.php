<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 06.11.2017
 * Time: 01:41
 */

namespace AppBundle\Entity;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{


    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }
    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }
}