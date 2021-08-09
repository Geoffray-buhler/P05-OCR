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
use Bdd\SQLiteDelete;
use Controller\SessionManager;
use Twig\Loader\FilesystemLoader;

require dirname(__DIR__).'\..\vendor\autoload.php';

class Controller
{

    public $twig;
    public $conn;
    public $post;

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

            if(!empty($_POST)){
                $this->post = $_POST;
            }
    }

    function home ()
    {
        $session = new SessionManager;

        try {
            // load template
            $template = $this->twig->load('pages/index.html.twig');
        
            // set template variables
            // render template
            echo $template->render(array('current'=>'home','session'=>$session->getSession()
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function contact ()
    {
        $session = new SessionManager;
        try {
            // load template
            $template = $this->twig->load('pages/contact.html.twig');
        
            // set template variables
            // render template
            echo $template->render(array('current'=>'contact','session'=>$session->getSession()
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function pages404 ()
    {
        $session = new SessionManager;
        try {
            // load template
            $template = $this->twig->load('pages/404.html.twig');
        
            // set template variables
            // render template
            echo $template->render(array('session'=>$session->getSession()
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
            if (!empty($this->post)) {
                    $cleanarray = (new Security)->cleanInput($this->post);
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
                            $session->setSession('message','succes','Votre compte a bien etais créé');
                            header("Location: /login");
                            exit();
                        }else{
                            //TODO mettre un message d'erreur
                            $session->setSession('message','error','Votre compte a pas etais créé');
                            header("Location: /");
                            exit();
                        }
                    }else{
                        echo $template->render(array('current'=>'logon','error'=>'vous n\'avez pas mis les deux meme mot de passe'
                    ));}
                }else{
                        // set template variables
                        // render template
                        echo $template->render(array('current'=>'logon','session'=>$session->getSession()
                    ));
                };
                   
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function login ()
    {
        $session = new SessionManager;
        try {
            // load template
            $template = $this->twig->load('pages/login.html.twig');
            if (!empty($this->post)) {
                $login = $this->post['login'];
                $password = $this->post['password'];

                $sqlite = new SQLiteGet($this->conn);
                $res = $sqlite->getUser($login);

                $verif = password_verify($password,$res['password']);
                if ($verif == true) {
                    $role = $res['type'];
                    $name = $res['login'];
                    $id = $res['id'];
                    $session->setSession('roles', $role);
                    $session->setSession('login', $name);
                    $session->setSession('id', $id);
                    header("Location: /");
                }
            }

            // set template variables
            // render template
            echo $template->render(array('current'=>'login','session'=>$session->getSession()
            ));
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function deconnexion()
    {
        $session = new SessionManager;
        header("Location: /");

        try{
            $session->closeSession();
        }catch (Exception $e){
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function admin()
    {
        $session = new SessionManager;
        try {   
            // load template
            $template = $this->twig->load('pages/admin.html.twig');
        
            // set template variables
            // render template
            echo $template->render(array('current'=>'admin','session'=>$session->getSession()
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }
    function profil()
    {
        $session = new SessionManager;
        $infos_user = $session->getSession();
        if(isset($_POST["delete"])){
            $sqlite = new SQLiteDelete($this->conn);
            $sqlite->DeleteUser($infos_user['id']);
            header("Location: /");
        }
        elseif (isset($_POST["modify"]))
        {
            var_dump('modify');
        }
        
        try {   
            // load template
            $template = $this->twig->load('pages/profil.html.twig');
        
            // set template variables
            // render template
            echo $template->render(array('current'=>'profil','session'=>$infos_user
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }
}

