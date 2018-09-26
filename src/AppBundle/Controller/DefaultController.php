<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/asdasd", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }


    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {

        return $this->render('githut/login.html.twig');
    }



    /**
     * @Route("/checkLogin", name="checkLogin")
     */
    public function checkloginAction()
    {
        $username = $_POST['_username'];
        $haslo = &$_POST['_password'];

        $userType = $_POST['userType'];



        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();


        if ($userType == "uczen") {
            $statement = $connection->prepare("SELECT * FROM uczniowie WHERE login = :login");
            $statement->bindValue('login', $username);
            $statement->execute();

            $results = $statement->fetchAll();
            return $this->render('githut/verifyLogin.html.twig', [
                'results' => $results,
                'haslo' => $haslo,
                'userType' => $userType,

            ]);
        } else if ($userType == "nauczyciel") {
            $statement = $connection->prepare("SELECT * FROM nauczyciele WHERE login = :login");
            $statement->bindValue('login', $username);
            $statement->execute();

            $results = $statement->fetchAll();
            return $this->render('githut/verifyLogin.html.twig', [
                'results' => $results,
                'haslo' => $haslo,
                'userType' => $userType,

            ]);
        } else if ($userType == "admin") {
            $statement = $connection->prepare("SELECT * FROM administratorzy WHERE login = :login");
            $statement->bindValue('login', $username);
            $statement->execute();

            $results = $statement->fetchAll();
            return $this->render('githut/verifyLogin.html.twig', [
                'results' => $results,
                'haslo' => $haslo,
                'userType' => $userType,

            ]);
        }


    }
}
