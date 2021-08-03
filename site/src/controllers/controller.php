<?php

namespace Controller;

use App\Debug;

use Exception;
use Bdd\SQLiteGet;
use Bdd\SQLiteSet;
use Twig\Environment;
use Controller\Security;
use Bdd\SQLiteConnection;
use Bdd\SQLiteCreateTable;
use Controller\SessionManager;
use Twig\Loader\FilesystemLoader;

require dirname(__DIR__).'\..\vendor\autoload.php';

class Controller
{

    public $twig;
    public $conn;

    function __construct()
    {
            // le dossier ou on trouve les templates
            $loader = new FilesystemLoader('../src/template');
        
            // initialiser l'environement Twig
            $this->twig = new Environment($loader);

            $this->conn = (new SQLiteConnection())->connect();

            $sqlite = new SQLiteCreateTable($this->conn);

            // create new tables
            $sqlite->createTables();
    }

    function home ()
    {
        try {
            // load template
            $template = $this->twig->load('pages/index.html.twig');
        
            // set template variables
            // render template
            echo $template->render(array('current'=>'home'
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function contact ()
    {
        try {
            // load template
            $template = $this->twig->load('pages/contact.html.twig');
        
            // set template variables
            // render template
            echo $template->render(array('current'=>'contact'
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function pages404 ()
    {
        try {
            // load template
            $template = $this->twig->load('pages/404.html.twig');
        
            // set template variables
            // render template
            echo $template->render(array(
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function register ()
    {
        $session = new SessionManager;
        try {
            // load template
            $template = $this->twig->load('pages/logon.html.twig');
            $post = $_POST;
            if (!empty($post)) {
                    $cleanarray = (new Security)->cleanInput($post);
                    $password= $cleanarray[2];
                    $confPassword = $cleanarray[3];
                    if ($password === $confPassword) {
                        $login = $cleanarray[0];
                        $email = $cleanarray[1];
                        $cryptedPassword = password_hash($password,PASSWORD_DEFAULT);
                        $sqlite = new SQLiteSet($this->conn);
                        $res = $sqlite->setUser($login,$cryptedPassword,$email);
                        if($res > 0){
                            //TODO mettre un message de reussite
                            $session->setSession('message','Votre compte a bien etais créé');
                            header("Location: /login");
                            exit();
                        }else{
                            //TODO mettre un message d'erreur
                            $session->setSession('error','Votre compte a pas etais créé');
                            header("Location: /");
                            exit();
                        }
                    }else{
                        echo $template->render(array('current'=>'logon','error'=>'vous n\'avez pas mis les deux meme mot de passe'
                    ));}
                }else{
                        // set template variables
                        // render template
                        echo $template->render(array('current'=>'logon'
                    ));
                };
                   
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function login ()
    {
        try {
            // load template
            $template = $this->twig->load('pages/login.html.twig');
            $post = $_POST;
            (new Debug)->vardump($post);
            if (!empty($post)) {
                $cleanarray = (new Security)->cleanInput($post);
                (new Debug)->vardump($cleanarray);
                $sqlite = new SQLiteGet($this->conn);
                $res = $sqlite->getUser($cleanarray[0],$cleanarray[2],$cleanarray[1]);
            }

            // set template variables
            // render template
            echo $template->render(array('current'=>'login'
            ));
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function admin()
    {
        try {   
            // load template
            $template = $this->twig->load('pages/admin.html.twig');
        
            // set template variables
            // render template
            echo $template->render(array('current'=>'admin'
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }
}

