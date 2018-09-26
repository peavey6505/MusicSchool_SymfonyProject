<?php


namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder;



class GitHutController extends Controller
{



    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {

        return $this->render('githut/login.html.twig');
    }

    /**
     * @Route("/uczniowie1", name="uczniowie")
     */
    public function bazadanychAction(Request $request)
    {
        $uczniowie = $this->getDoctrine()->getRepository('AppBundle:Uczen')->findAll();    //AppBudnle: okresla ze w entity bo jakies namespace a APP to ze w tyn folderze

        dump($uczniowie);

        return $this->render('githut/uczniowie.html.twig', [
            'uczniowie' => $uczniowie
        ]);
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

    /**
     * @Route("/dane", name="dane")
     */
    public function daneAction(Request $request)
    {



            $id = $_POST['id'];


            $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
            $connection = $em->getConnection();
            $statement = $connection->prepare("SELECT * FROM uczniowie WHERE id = :id");
            $statement->bindValue('id', $id);
            $statement->execute();

            $results = $statement->fetchAll();


            $statement = $connection->prepare("SELECT * FROM zdjecia WHERE id_ucznia = :id");
            $statement->bindValue('id', $id);
            $statement->execute();
            $zdjecia = $statement->fetch();


            $db = mysqli_connect("localhost", "root", "", "uczniowie");
            $sql = "SELECT * FROM zdjecia WHERE id_ucznia = $id";
            $sth = $db->query($sql);
            $result = mysqli_fetch_array($sth);

            $result = base64_encode($result['zdjecie']);
            //  echo '<img src="data:image/jpeg;base64,'.base64_encode( $result['image'] ).'"/>';


            return $this->render('githut/dane.html.twig', [
                'results' => $results,
                'foto' => $result,

            ]);


    }



    /**
     * @Route("/daneNauczyciela", name="daneNauczyciela")
     */
    public function daneNauczycielaAction(Request $request)
    {



        $id = $_POST['id'];


        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT * FROM nauczyciele n
                INNER JOIN instrumenty i ON 
                i.id = n.id_instrumentu AND n.id = :id");
        $statement->bindValue('id', $id);
        $statement->execute();

        $results = $statement->fetchAll();


        return $this->render('githut/daneNauczyciela.html.twig', [
            'results' => $results,

        ]);


    }

    /**
     * @Route("/profilUcznia", name="profilUcznia")
     */
    public function profilUczniaAction(Request $request)
    {

        if(isset($_POST['id'])) {
            {
                $id = $_POST['id'];


                $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
                $connection = $em->getConnection();
                $statement = $connection->prepare("
                         SELECT * from aktualnosci
                         ORDER BY data DESC
                           ");
                $statement->execute();
                $aktualnosci = $statement->fetchAll();


                return $this->render('githut/profilUcznia.html.twig', [
                    'id' => $id,
                    'aktualnosci' => $aktualnosci
                ]);
            }
        }
        else
               return $this->render('githut/login.html.twig');

    }


    /**
     * @Route("/profilAdmina", name="profilAdmina")
     */
    public function profilAdminaAction(Request $request)
    {

        if(isset($_POST['id'])) {
            {
                $id = $_POST['id'];




                return $this->render('githut/profilAdmina.html.twig', [
                    'id' => $id
                ]);
            }
        }
        else
            return $this->render('githut/login.html.twig');

    }
    /**
     * @Route("/profilNauczyciela", name="profilNauczyciela")
     */
    public function profilNauczycielaAction(Request $request)
    {

        if(isset($_POST['id'])) {
            {
                $id = $_POST['id'];


                $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
                $connection = $em->getConnection();
                $statement = $connection->prepare("
                         SELECT * from aktualnosci
                         ORDER BY data DESC
                           ");
                $statement->execute();
                $aktualnosci = $statement->fetchAll();


                return $this->render('githut/profilNauczyciela.html.twig', [
                    'id' => $id,
                    'aktualnosci' => $aktualnosci
                ]);
            }
        }
        else
            return $this->render('githut/login.html.twig');

    }



    /**
     * @Route("/edytujDane", name="edytujDane")
     */
    public function edytujDaneAction(Request $request)
    {


        $id = $_POST['id'];

        return $this->render('githut/edytujDane.html.twig', [
            'id' => $id,
        ]);
    }


    /**
     * @Route("/edytujEmail", name="edytujEmail")
     */
    public function edytujEmailAction(Request $request)
    {


        $id = $_POST['id'];
        if (isset($_POST["email"]) && !empty($_POST["email"])) {
            $email = $_POST['email'];

            $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
            $connection = $em->getConnection();
            $statement = $connection->prepare("
          UPDATE uczniowie
          SET email = :email
          WHERE id = :id");
            $statement->bindValue('id', $id);
            $statement->bindValue('email', $email);
            $statement->execute();
        }
        return $this->render('githut/edytujDane.html.twig', [
            'id' => $id,
        ]);
    }

    /**
     * @Route("/edytujTelefon", name="edytujTelefon")
     */
    public function edytujTelefonAction(Request $request)
    {


        $id = $_POST['id'];
        $telefon = $_POST['telefon'];

        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $statement = $connection->prepare("
          UPDATE uczniowie
          SET telefon = :telefon
          WHERE id = :id");
        $statement->bindValue('id', $id);
        $statement->bindValue('telefon', $telefon);
        $statement->execute();

        return $this->render('githut/edytujDane.html.twig', [
            'id' => $id,
        ]);
    }

    /**
     * @Route("/terminarz", name="terminarz")
     */
    public function terminarzAction(Request $request)
    {

        $id = $_POST['id'];

        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $statement = $connection->prepare("
        SELECT l.dzien, l.godzina, i.instrument, CONCAT(n.imie, ' ',n.nazwisko) as imienazwisko
 
        FROM terminy_lekcji l INNER JOIN instrumenty i 
        ON l.id_instrumentu = i.id AND l.id_ucznia = :id 
        INNER JOIN nauczyciele n 
        ON l.id_nauczyciela = n.id");
        $statement->bindValue('id', $id);
        $statement->execute();
        $results = $statement->fetchAll();


        return $this->render('githut/terminarz.html.twig', [
            'id' => $id,
            'results' => $results
        ]);
    }


    /**
     * @Route("/terminarzNauczyciela", name="terminarzNauczyciela")
     */
    public function terminarzNauczycielaAction(Request $request)
    {

        $id = $_POST['id'];

        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT l.dzien, l.godzina, i.instrument, CONCAT(u.imie, ' ',u.nazwisko) as imienazwisko
 
        FROM terminy_lekcji l
        		
        INNER JOIN uczniowie u 
        
        		ON l.id_ucznia = u.id AND l.id_nauczyciela = 2
        INNER JOIN instrumenty i 
        		ON i.id = l.id_instrumentu");
        $statement->bindValue('id', $id);
        $statement->execute();
        $results = $statement->fetchAll();


        return $this->render('githut/terminarzNauczyciela1.html.twig', [
            'id' => $id,
            'results' => $results
        ]);
    }
    /**
     * @Route("/lekcje", name="lekcje")
     */
    public function lekcjeAction(Request $request)
    {
        $id = $_POST['id'];


        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $statement = $connection->prepare("
          SELECT l.data, p.tytul, p.wykonawca, l.tempo, l.komentarz, i.instrument
          FROM lekcje l INNER JOIN piosenki p 
          ON l.id_piosenki = p.id
          AND id_ucznia = :id
          INNER JOIN instrumenty i
          ON i.id = l.id_instrumentu
          ORDER BY data DESC");
        $statement->bindValue('id', $id);
        $statement->execute();

        $results = $statement->fetchAll();
        return $this->render('githut/lekcje.html.twig', [
            'id' => $id,
            'results' => $results
        ]);
    }


    /**
     * @Route("/dodajZadanie", name="dodajZadanie")
     */
    public function dodajZadanieAction(Request $request)
    {
        $id = $_POST['id'];
        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();


        if(isset($_POST['id_instrumentu'])&&$_POST['id_piosenki']
        &&$_POST['id_instrumentu'])
        {

            $id_ucznia = $_POST['id_ucznia'];
            $id_piosenki = $_POST['id_piosenki'];
            $id_instrumentu = $_POST['id_instrumentu'];



            $statement = $connection->prepare("
            INSERT INTO zadania(id_ucznia, id_piosenki, id_instrumentu, id_nauczyciela,
            zaliczone) VALUES (:id_ucznia,:id_piosenki,:id_instrumentu,:id_nauczyciela,0)");
              $statement->bindValue('id_nauczyciela', $id);
              $statement->bindValue('id_piosenki', $id_piosenki);
            $statement->bindValue('id_instrumentu', $id_instrumentu);
            $statement->bindValue('id_ucznia', $id_ucznia);
            $statement->execute();
        }



        $statement = $connection->prepare("
          SELECT u.imie, u.nazwisko, u.id FROM uczniowie u");

        $statement->execute();

        $uczniowie = $statement->fetchAll();



        $statement = $connection->prepare("
          SELECT * FROM piosenki");
        $statement->execute();
        $piosenki= $statement->fetchAll();

        $statement = $connection->prepare("
          SELECT * FROM instrumenty");
        $statement->execute();
        $instrumenty= $statement->fetchAll();

        $results = $statement->fetchAll();
        return $this->render('githut/dodajZadanie.html.twig', [
            'id' => $id,
            'uczniowie'=>$uczniowie,
            'piosenki'=>$piosenki,
            'instrumenty'=>$instrumenty
        ]);
    }






    /**
     * @Route("/dodajUcznia", name="dodajUcznia")
     */
    public function dodajUczniaAction(Request $request,UserPasswordEncoderInterface $encoder )
    {
        $id = $_POST['id'];
        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $komunikat = "";

        if(isset($_POST['imie'])
            &&(isset($_POST['nazwisko']))
            &&(isset($_POST['data_urodzenia']))
            &&(isset($_POST['miasto']))
            &&(isset($_POST['telefon']))
            &&(isset($_POST['email']))) {


            $komunikat = "chuj";
            $imie = $_POST['imie'];
            $nazwisko = $_POST['nazwisko'];
            $data_urodzenia = $_POST['data_urodzenia'];
            $miasto = $_POST['miasto'];
            $email = $_POST['email'];
            $telefon = $_POST['telefon'];
            $login = $_POST['login'];
            $haslo = $_POST['haslo'];
            $potwierdzHaslo = $_POST['potwierdzHaslo'];



            if($potwierdzHaslo == $haslo) {

               // $user = new User();



                $statement = $connection->prepare("
            INSERT INTO uczniowie(imie,nazwisko,data_urodzenia,miasto,email,telefon,login,haslo)
             VALUES (:imie,:nazwisko,:data_urodzenia,:miasto,:email,:telefon,:login,:haslo)");


                $statement->bindValue('nazwisko', $nazwisko);
                $statement->bindValue('imie', $imie);
                $statement->bindValue('data_urodzenia', $data_urodzenia);
                $statement->bindValue('miasto', $miasto);
                $statement->bindValue('email', $email);
                $statement->bindValue('login', $login);
                $statement->bindValue('haslo', $haslo);
                $statement->bindValue('telefon', $telefon);
                $statement->execute();

                $komunikat = "Dodano ucznia";
            }
            else{
                $komunikat = "Hasla nie sa takie same";
            }
            }



        return $this->render('githut/dodajUcznia.html.twig', [
            'id' => $id,
            'komunikat' => $komunikat
        ]);


    }




    /**
     * @Route("/dodajNauczyciela", name="dodajNauczyciela")
     */
    public function dodajNauczycielaAction(Request $request)
    {
        $id = $_POST['id'];
        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $komunikat = "";


        $statement = $connection->prepare("
          SELECT * FROM instrumenty");
        $statement->execute();
        $instrumenty= $statement->fetchAll();

        if(isset($_POST['imie'])
            &&(isset($_POST['nazwisko']))
            )
            {

            $komunikat = "chuj";
            $imie = $_POST['imie'];
            $nazwisko = $_POST['nazwisko'];
            $login = $_POST['login'];
            $haslo = $_POST['haslo'];
            $potwierdzHaslo = $_POST['potwierdzHaslo'];
             $id_instrumentu = $_POST['id_instrumentu'];



            if($potwierdzHaslo == $haslo) {

                if($id_instrumentu=="1")
                    $statement = $connection->prepare("
            INSERT INTO nauczyciele(imie,nazwisko,login,haslo,id_instrumentu)
             VALUES (:imie,:nazwisko,:login,:haslo,1)");

                if($id_instrumentu=="2")
                    $statement = $connection->prepare("
            INSERT INTO nauczyciele(imie,nazwisko,login,haslo,id_instrumentu)
             VALUES (:imie,:nazwisko,:login,:haslo,2)");

                if($id_instrumentu=="3")
                    $statement = $connection->prepare("
            INSERT INTO nauczyciele(imie,nazwisko,login,haslo,id_instrumentu)
             VALUES (:imie,:nazwisko,:login,:haslo,3)");

                if($id_instrumentu=="4")
                    $statement = $connection->prepare("
            INSERT INTO nauczyciele(imie,nazwisko,login,haslo,id_instrumentu)
             VALUES (:imie,:nazwisko,:login,:haslo,4)");


                if($id_instrumentu=="5")
                    $statement = $connection->prepare("
            INSERT INTO nauczyciele(imie,nazwisko,login,haslo,id_instrumentu)
             VALUES (:imie,:nazwisko,:login,:haslo,5)");

                if($id_instrumentu=="6")
                    $statement = $connection->prepare("
            INSERT INTO nauczyciele(imie,nazwisko,login,haslo,id_instrumentu)
             VALUES (:imie,:nazwisko,:login,:haslo,6)");


                    $statement->bindValue('nazwisko', $nazwisko);
                    $statement->bindValue('imie', $imie);
                    $statement->bindValue('login', $login);
                    $statement->bindValue('haslo', $haslo);





                    $statement->execute();

                    $komunikat = "Dodano nauczyciela";

            }
            else{
                $komunikat = "Hasla nie sa takie same";
            }
        }

        return $this->render('githut/dodajNauczyciela.html.twig', [
            'id' => $id,
            'komunikat' => $komunikat,
            'instrumenty' => $instrumenty
        ]) ;
    }




    /**
     * @Route("/zarzadzajForum", name="zarzadzajForum")
     */
    public function zarzadzajForumAction(Request $request)
    {
        $id = $_POST['id'];



        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $statement = $connection->prepare("
          SELECT * 
          FROM tematy");

        $statement->execute();
        $tematy = $statement->fetchAll();

        return $this->render('githut/zarzadzajForum.html.twig', [
            'id' => $id,
            'tematy' => $tematy
        ]);



        return $this->render('githut/zarzadzajForum.html.twig', [
            'id' => $id,
        ]) ;
    }

    /**
     * @Route("/dodajLekcje", name="dodajLekcje")
     */
    public function lekcjeNauczycielaAction(Request $request)
    {
        $id = $_POST['id'];


        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();

        if(isset( $_POST['id_ucznia']) &&isset( $_POST['id_piosenki']) &&
        isset( $_POST['id_instrumentu']) && isset( $_POST['komentarz']) &&
            isset($_POST['tempo']))

        {
            $id_ucznia = $_POST['id_ucznia'];
            $id_piosenki = $_POST['id_piosenki'];
            $id_instrumentu = $_POST['id_instrumentu'];
            $komentarz = $_POST['komentarz'];
            $tempo = $_POST['tempo'];

            $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
            $connection = $em->getConnection();
            $statement = $connection->prepare("
          INSERT INTO lekcje(id_ucznia,id_piosenki,id_instrumentu,komentarz,tempo,data)
          VALUES(:id_ucznia,:id_piosenki,:id_instrumentu,:komentarz,:tempo,NOW())
          ");

            $statement->bindValue('id_ucznia', $id_ucznia);
            $statement->bindValue('id_piosenki', $id_piosenki);
            $statement->bindValue('id_instrumentu', $id_instrumentu);
            $statement->bindValue('komentarz', $komentarz);
            $statement->bindValue('tempo', $tempo);
            $statement->execute();
        }

        $statement = $connection->prepare("
          SELECT u.imie, u.nazwisko, u.id FROM uczniowie u");

        $statement->execute();

        $uczniowie = $statement->fetchAll();



        $statement = $connection->prepare("
          SELECT * FROM piosenki");
        $statement->execute();
        $piosenki= $statement->fetchAll();

        $statement = $connection->prepare("
          SELECT * FROM instrumenty");
        $statement->execute();
        $instrumenty= $statement->fetchAll();

        return $this->render('githut/dodajLekcje.html.twig', [
            'id' => $id,
            'uczniowie'=>$uczniowie,
            'instrumenty'=>$instrumenty,
            'piosenki'=> $piosenki

        ]);
    }



    /**
     * @Route("/bazaUtworow", name="bazaUtworow")
     */
    public function bazaUtworowAction(Request $request)
    {
        $id = $_POST['id'];



        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();

        if(isset( $_POST['wykonawca']) &&isset( $_POST['tytul'])&&isset( $_POST['akordy']))
        {
            $wykonawca = $_POST['wykonawca'];
            $tytul =  $_POST['tytul'];
            $akordy =  $_POST['akordy'];

            $statement = $connection->prepare("
          INSERT INTO piosenki(wykonawca,tytul,akordy)
          VALUES (:wykonawca, :tytul, :akordy)");

            $statement->bindValue('wykonawca',$wykonawca);
            $statement->bindValue('tytul', $tytul);
            $statement->bindValue('akordy', $akordy);
            $statement->execute();
        }


        if(isset($_POST['filtr']))
        {
            $filtr=$_POST['filtr'];


                $statement = $connection->prepare("
               SELECT * FROM piosenki WHERE wykonawca = :filtr ");

                $statement->bindValue('filtr', $filtr);
                $statement->execute();
                $piosenki = $statement->fetchAll();


            return $this->render('githut/bazaUtworow.html.twig', [
                'id' => $id,
                'piosenki' => $piosenki

            ]);

        }


        if(isset($_POST['filtrPiosenka']))
        {
            $filtrPiosenka=$_POST['filtrPiosenka'];


            $statement = $connection->prepare("
               SELECT * FROM piosenki WHERE tytul = :filtrPiosenka ");

            $statement->bindValue('filtrPiosenka', $filtrPiosenka);
            $statement->execute();
            $piosenki = $statement->fetchAll();


            return $this->render('githut/bazaUtworow.html.twig', [
                'id' => $id,
                'piosenki' => $piosenki

            ]);

        }

            $statement = $connection->prepare("
          SELECT * 
          FROM piosenki");


            $statement->execute();
            $piosenki = $statement->fetchAll();

            return $this->render('githut/bazaUtworow.html.twig', [
                'id' => $id,
                'piosenki' => $piosenki

            ]);

    }

    /**
     * @Route("/forum", name="forum")
     */
    public function forumAction(Request $request)
    {
        $id = $_POST['id'];


        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $statement = $connection->prepare("
          SELECT * 
          FROM tematy");

        $statement->execute();
        $tematy = $statement->fetchAll();

        return $this->render('githut/forum.html.twig', [
            'id' => $id,
            'tematy' => $tematy
        ]);
    }


    /**
     * @Route("/usunUcznia", name="usunUcznia")
     */
    public function usunUczniaAction(Request $request)
    {
        $id = $_POST['id'];
        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();

        $komunikat = "";

        if(isset($_POST['idDoUsuniecia']))
        {
            $idDoUsuniecia = intval($_POST['idDoUsuniecia']);
            $statement = $connection->prepare("
            DELETE FROM uczniowie 
            WHERE id = :idDoUsuniecia");

            $statement->bindValue('idDoUsuniecia', $idDoUsuniecia);
            $statement->execute();
            $komunikat = "Usunięto ucznia";
        }



        $statement = $connection->prepare("
          SELECT * 
          FROM uczniowie
          ORDER BY nazwisko DESC");
        $statement->execute();
        $uczniowie = $statement->fetchAll();

        if(isset($_POST['filtrNazwisko']) )
        {

            if(!isset($_POST['wczyscFiltr'])) {
                $filtr = $_POST['filtrNazwisko'];


                $statement = $connection->prepare("
          SELECT * 
          FROM uczniowie
          WHERE nazwisko = :filtr");
                $statement->bindValue('filtr', $filtr);
                $statement->execute();
                $uczniowie = $statement->fetchAll();

            }
        }

        return $this->render('githut/usunUcznia.html.twig', [
            'id' => $id,
            'komunikat' => $komunikat,
            'uczniowie' => $uczniowie
        ]);
    }




    /**
     * @Route("/usunTemat", name="usunTemat")
     */
    public function usunTematAction(Request $request)
    {
        $id = $_POST['id'];
        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();

        $komunikat = "";

        if(isset($_POST['idDoUsuniecia']))
        {
            $idDoUsuniecia = intval($_POST['idDoUsuniecia']);

            $statement = $connection->prepare("
            DELETE FROM posty
            WHERE id_tematu = :idDoUsuniecia");

            $statement->bindValue('idDoUsuniecia', $idDoUsuniecia);
            $statement->execute();



            $statement = $connection->prepare("
            DELETE FROM tematy 
            WHERE id = :idDoUsuniecia");

            $statement->bindValue('idDoUsuniecia', $idDoUsuniecia);
            $statement->execute();
            $komunikat = "Usunięto temat";
        }



        $statement = $connection->prepare("
          SELECT * 
          FROM tematy
         ");
        $statement->execute();
        $tematy = $statement->fetchAll();

        return $this->render('githut/usunTemat.html.twig', [
            'id' => $id,
            'komunikat' => $komunikat,
            'tematy' => $tematy

        ]);
    }


    /**
     * @Route("/usunNauczyciela", name="usunNauczyciela")
     */
    public function usunNauczycielaAction(Request $request)
    {
        $id = $_POST['id'];
        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();

        $komunikat = "";

        if(isset($_POST['idDoUsuniecia']))
        {
            $idDoUsuniecia = intval($_POST['idDoUsuniecia']);
            $statement = $connection->prepare("
            DELETE FROM nauczyciele 
            WHERE id = :idDoUsuniecia");

            $statement->bindValue('idDoUsuniecia', $idDoUsuniecia);
            $statement->execute();
            $komunikat = "Usunięto nauczyciela";
        }



        $statement = $connection->prepare("
          SELECT * 
          FROM nauczyciele
          ORDER BY nazwisko DESC");
        $statement->execute();
        $nauczyciele = $statement->fetchAll();

        if(isset($_POST['filtrNazwisko']) )
        {

            if(!isset($_POST['wczyscFiltr'])) {
                $filtr = $_POST['filtrNazwisko'];


                $statement = $connection->prepare("
          SELECT * 
          FROM nauczyciele
          WHERE nazwisko = :filtr");
                $statement->bindValue('filtr', $filtr);
                $statement->execute();
                $nauczyciele = $statement->fetchAll();

            }
        }

        return $this->render('githut/usunNauczyciela.html.twig', [
            'id' => $id,
            'komunikat' => $komunikat,
            'nauczyciele' => $nauczyciele
        ]);
    }


    /**
     * @Route("/wyswietlLekcjeNauczyciela", name="wyswietlLekcjeNauczyciela")
     */
    public function wyswietlLekcjeNauczycielaAction(Request $request)
    {
        $id = $_POST['id'];

        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $lekcje = NULL;


        if(isset($_POST['id_ucznia']))
        {
            $id_ucznia = $_POST['id_ucznia'];

            $statement = $connection->prepare("
          SELECT l.data, p.tytul, p.wykonawca, l.tempo, l.komentarz, i.instrument
          FROM lekcje l INNER JOIN piosenki p 
          ON l.id_piosenki = p.id
          AND id_ucznia = :id_ucznia
          INNER JOIN instrumenty i
          ON i.id = l.id_instrumentu
          ORDER BY data DESC ");

            $statement->bindValue('id_ucznia', $id_ucznia);
            $statement->execute();
            $lekcje = $statement->fetchAll();

        }


        $statement = $connection->prepare("
          SELECT * 
          FROM uczniowie");

        $statement->execute();
        $uczniowie = $statement->fetchAll();

        return $this->render('githut/wyswietlLekcjeNauczyciela.html.twig', [
            'id' => $id,
            'uczniowie'=>$uczniowie,
            'lekcje'=>$lekcje

        ]);
    }
    /**
     * @Route("/dodajTemat", name="dodajTemat")
     */
    public function dodajTematAction(Request $request)
    {
        $id = $_POST['id'];
        $temat = $_POST['temat'];



        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();



        $statement = $connection->prepare("
          INSERT INTO tematy (temat)
          VALUES (:temat)");
        $statement->bindValue('temat', $temat);
        $statement->execute();


        $statement = $connection->prepare("
          SELECT * 
          FROM tematy");
        $statement->execute();
        $tematy = $statement->fetchAll();





        return $this->render('githut/forum.html.twig', [
            'id' => $id,
            'tematy' => $tematy
        ]);
    }

    /**
     * @Route("/dodajAktualnosci", name="dodajAktualnosci")
     */
    public function dodajAktualnosciAction(Request $request)
    {
        $id = $_POST['id'];
        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();

        if(isset($_POST['post']))
        {
            $post = $_POST['post'];
            $statement = $connection->prepare("
          INSERT INTO aktualnosci (data,post)
          VALUES (NOW(),:post)");
            $statement->bindValue('post',$post);
            $statement->execute();
        }



        $statement = $connection->prepare("
          SELECT * 
          FROM aktualnosci
          ORDER BY data DESC ");
        $statement->execute();
        $aktualnosci = $statement->fetchAll();





        return $this->render('githut/dodajAktualnosci.html.twig', [
            'id' => $id,
            'aktualnosci' => $aktualnosci
        ]);
    }


    /**
     * @Route("/posty", name="posty")
     */
    public function postyAction(Request $request)
    {
        $id = $_POST['id'];
        $id_tematu = $_POST['id_tematu'];


        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        if(isset($_POST['post']))
        {
            $post = $_POST['post'];

            $statement = $connection->prepare("
          INSERT INTO posty ( id_tematu, id_ucznia,post, data)
          VALUES ( :id_tematu,:id ,:post, NOW()) ");
            $statement->bindValue('post', $post);
            $statement->bindValue('id', $id);
            $statement->bindValue('id_tematu', $id_tematu);
            $statement->execute();


//            return $this->render('githut/posty.html.twig');
        }


        $statement = $connection->prepare("
          SELECT post, id_ucznia, data
          FROM posty p
          WHERE p.id_tematu = :id_tematu
          ORDER BY data DESC");
        $statement->bindValue('id_tematu', $id_tematu);
        $statement->execute();
        $posty = $statement->fetchAll();

        $statement = $connection->prepare("
          SELECT temat 
          FROM tematy t
          WHERE t.id = :id_tematu");
        $statement->bindValue('id_tematu', $id_tematu);
        $statement->execute();
        $tematy = $statement->fetchAll();

        $statement = $connection->prepare("
          SELECT id, login 
          FROM uczniowie
          ");
        $statement->execute();
        $uczniowie = $statement->fetchAll();







        return $this->render('githut/posty.html.twig', [
            'id' => $id,
            'id_tematu' =>$id_tematu,
            'posty' => $posty,
            'tematy' => $tematy,
            'uczniowie' => $uczniowie
        ]);
    }



    /**
     * @Route("/usunPost", name="usunPost")
     */
    public function usunPostAction(Request $request)
    {
        $id = $_POST['id'];
        $id_tematu = $_POST['id_tematu'];


        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        if(isset($_POST['idDoUsuniecia']))
        {
            $idDoUsuniecia = $_POST['idDoUsuniecia'];

            $statement = $connection->prepare("
          DELETE FROM posty
          WHERE id = :idDoUsuniecia");
            $statement->bindValue('idDoUsuniecia', $idDoUsuniecia);
           $statement->execute();


//            return $this->render('githut/posty.html.twig');
        }


        $statement = $connection->prepare("
          SELECT post, id_ucznia, data, id
          FROM posty p
          WHERE p.id_tematu = :id_tematu
          ORDER BY data DESC");
        $statement->bindValue('id_tematu', $id_tematu);
        $statement->execute();
        $posty = $statement->fetchAll();

        $statement = $connection->prepare("
          SELECT temat 
          FROM tematy t
          WHERE t.id = :id_tematu");
        $statement->bindValue('id_tematu', $id_tematu);
        $statement->execute();
        $tematy = $statement->fetchAll();

        $statement = $connection->prepare("
          SELECT id, login 
          FROM uczniowie
          ");
        $statement->execute();
        $uczniowie = $statement->fetchAll();







        return $this->render('githut/usunPost.html.twig', [
            'id' => $id,
            'id_tematu' =>$id_tematu,
            'posty' => $posty,
            'tematy' => $tematy,
            'uczniowie' => $uczniowie
        ]);
    }
    /**
     * @Route("/dodajPost", name="dodajPost")
     */
    public function dodajPostAction(Request $request)
    {
        $id = $_POST['id'];
        $id_tematu = $_POST['id_tematu'];

        $posty = $_POST['posty'];
        $uczniowie = $_POST['uczniowie'];
        $tematy = $_POST['tematy'];

        $post = $_POST['post'];


        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
               $statement = $connection->prepare("
          INSERT INTO posty ('id', 'id_tematu', 'id_ucznia','post' 'data')
          VALUES ('', '1', '1','HAKUNA MATATA', '1996-22-01') ");



        
        
        $statement->bindValue('id_tematu', $id_tematu);
        $statement->bindValue('id', $id);
        $statement->bindValue('post', $post);
        $statement->execute();

         return $this->render('githut/posty.html.twig', [
             'id' => $id,
             'id_tematu' =>id_tematu,
             'posty' => $posty,
             'tematy' => $tematy,
             'uczniowie' => $uczniowie,

         ]);
    }


    /**
     * @Route("/zadania", name="zadania")
     */
    public function zadaniatAction(Request $request)
    {
        $id = $_POST['id'];



        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $connection = $em->getConnection();
        $statement = $connection->prepare("
          SELECT z.id, i.instrument, p.tytul, CONCAT(n.imie,' ',n.nazwisko) AS imieNazwiskoNauczyciela, z.zaliczone
          FROM zadania z 
           INNER JOIN instrumenty i
            
            ON i.id = z.id_instrumentu AND z.zaliczone = 1 AND z.id_ucznia = :id
          INNER JOIN piosenki p 
              ON p.id = z.id_piosenki
        INNER JOIN nauczyciele n
              ON z.id_nauczyciela = n.id");
        $statement->bindValue('id', $id);
        $statement->execute();
        $zadaniaZaliczone = $statement->fetchAll();


        $statement = $connection->prepare("
           SELECT z.id, i.instrument, p.tytul, CONCAT(n.imie,' ',n.nazwisko) AS imieNazwiskoNauczyciela, z.zaliczone
          FROM zadania z 
           INNER JOIN instrumenty i
            
            ON i.id = z.id_instrumentu AND z.zaliczone = 0  AND z.id_ucznia = :id
          INNER JOIN piosenki p 
              ON p.id = z.id_piosenki
          INNER JOIN nauczyciele n
              ON z.id_nauczyciela = n.id");
        $statement->bindValue('id', $id);
        $statement->execute();
        $zadaniaNiealiczone = $statement->fetchAll();

        return $this->render('githut/zadania.html.twig',[
            'id'=> $id,
            'zadaniaZaliczone' => $zadaniaZaliczone,
            'zadaniaNieZaliczone' => $zadaniaNiealiczone

        ]);


    }


    /**
     * @Route("/wiadomoscDoNauczyciela", name="wiadomoscDoNauczyciela")
     */
    public function wiadomoscDoNauczycielaAction(Request $request)
    {
        $id = $_POST['id'];


        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $komunikat = NULL;

        if(isset($_POST['wiadomosc']))
        {
            $id_nauczyciela=intval($_POST['id_nauczyciela']);
            $wiadomosc = $_POST['wiadomosc'];
            $komunikat = NULL;

            $statement = $connection->prepare("
            INSERT INTO wiadomosci_nauczyciela(id_ucznia,id_nauczyciela,wiadomosc,data)
            VALUES (:id, :id_nauczyciela,:wiadomosc, NOW())
          ");
            $statement->bindValue('id', $id);
            $statement->bindValue('id_nauczyciela', $id_nauczyciela);
            $statement->bindValue('wiadomosc', $wiadomosc);
            $statement->execute();
            $komunikat = "Wiadomosc została wysłana";
        }



        $statement = $connection->prepare("
           SELECT CONCAT(n.imie,' ', n.nazwisko) as imieNaziwskoNauczyciela, n.id, i.instrument
           FROM nauczyciele n
           INNER JOIN instrumenty i
              ON n.id_instrumentu = i.id");
        $statement->bindValue('id', $id);
        $statement->execute();
        $nauczyciele = $statement->fetchAll();

        return $this->render('githut/wiadomoscDoNauczyciela.html.twig',[
            'id'=> $id,
            'nauczyciele'=>$nauczyciele,
            'komunikat' => $komunikat

        ]);


    }






    /**
     * @Route("/wiadomoscDoUcznia", name="wiadomoscDoUcznia")
     */
    public function wiadomoscDoUczniaAction(Request $request)
    {
        $id = $_POST['id'];


        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $komunikat = NULL;

        if(isset($_POST['wiadomosc']))
        {
            $id_ucznia=intval($_POST['id_ucznia']);
            $wiadomosc = $_POST['wiadomosc'];
            $komunikat = NULL;

            $statement = $connection->prepare("
            INSERT INTO wiadomosci_ucznia(id_ucznia,id_nauczyciela,wiadomosc,data)
            VALUES (:id_ucznia, :id,:wiadomosc, NOW())
          ");
            $statement->bindValue('id', $id);
            $statement->bindValue('id_ucznia', $id_ucznia);
            $statement->bindValue('wiadomosc', $wiadomosc);
            $statement->execute();
            $komunikat = "Wiadomosc została wysłana";
        }



        $statement = $connection->prepare("
           SELECT CONCAT(u.imie,' ', u.nazwisko) as imieNaziwskoUcznia, u.id
           FROM uczniowie u
     ");
        $statement->bindValue('id', $id);
        $statement->execute();
        $uczniowie = $statement->fetchAll();

        return $this->render('githut/wiadomoscDoUcznia.html.twig',[
            'id'=> $id,
            'uczniowie'=>$uczniowie,
            'komunikat' => $komunikat

        ]);


    }
    /**
     * @Route("/wiadomosciUcznia", name="wiadomosciUcznia")
     */

    public function wiadomosciUczniaAction(Request $request)
    {
        $id = $_POST['id'];


        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $statement = $connection->prepare("
           SELECT CONCAT(n.imie, ' ', n.nazwisko) as imieNazwiskoNauczyciela, w.wiadomosc,w.data
           FROM wiadomosci_ucznia w 
           INNER JOIN nauczyciele n 
              ON w.id_ucznia= :id AND w.id_nauczyciela = n.id");
        $statement->bindValue('id', $id);
        $statement->execute();
        $wiadomosci = $statement->fetchAll();


        return $this->render('githut/wiadomosciUcznia.html.twig', [
            'id' => $id,
            'wiadomosci' => $wiadomosci


        ]);
    }

        /**
         * @Route("/wiadomosciNauczyciela", name="wiadomosciNauczyciela")
         */
        public function wiadomosciNauczycielaAction(Request $request)
    {
        $id = $_POST['id'];


        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $statement = $connection->prepare("
           SELECT CONCAT(u.imie, ' ', u.nazwisko) as imieNazwiskoUcznia, w.wiadomosc,w.data
           FROM wiadomosci_nauczyciela w 
           INNER JOIN uczniowie u
              ON w.id_nauczyciela= :id AND w.id_ucznia = u.id
              ORDER BY data DESC ");
        $statement->bindValue('id', $id);
        $statement->execute();
        $wiadomosci = $statement->fetchAll();





        return $this->render('githut/wiadomosciNauczyciela.html.twig',[
            'id'=> $id,
            'wiadomosci' =>$wiadomosci


        ]);


    }




    /**
     * @Route("/egzaminy", name="egzaminy")
     */

    public function egzaminyAction(Request $request)
    {
        $id = $_POST['id'];


        $em = $this->getDoctrine()->getManager(); // ...or getEntityManager() prior to Symfony 2.1
        $connection = $em->getConnection();
        $statement = $connection->prepare("
           SELECT CONCAT(n.imie, ' ', n.nazwisko) as imieNazwiskoNauczyciela, i.instrument, e.stopien,e.ocena, e.data
           FROM egzaminy e
           INNER JOIN nauczyciele n
              ON n.id = e.id_nauczyciela AND e.id_ucznia = :id AND e.zaliczony = 1
            INNER JOIN instrumenty i 
              ON e.id_instrumentu = i.id");
        $statement->bindValue('id', $id);
        $statement->execute();
        $egzaminyZaliczone = $statement->fetchAll();

        $statement = $connection->prepare("
           SELECT CONCAT(n.imie, ' ', n.nazwisko) as imieNazwiskoNauczyciela, i.instrument, e.stopien,e.ocena, e.data
           FROM egzaminy e
           INNER JOIN nauczyciele n
              ON n.id = e.id_nauczyciela AND e.id_ucznia = :id AND e.zaliczony = 0
            INNER JOIN instrumenty i 
              ON e.id_instrumentu = i.id");
        $statement->bindValue('id', $id);
        $statement->execute();
        $egzaminyNieZaliczone = $statement->fetchAll();





        return $this->render('githut/egzaminy.html.twig',[
            'id'=> $id,
            'egzaminyZaliczone' =>$egzaminyZaliczone,
            'egzaminyNieZaliczone' =>$egzaminyZaliczone


        ]);


    }
}