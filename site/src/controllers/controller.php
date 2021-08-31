<?php

namespace Controller;

use App\Debug;

use Exception;
use Bdd\SQLiteGet;
use Bdd\SQLiteSet;
use Bdd\SQLiteDelete;
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
    public $post;
    public $session;
    public $mail;

    function __construct()
    {
        $this->session = $_SESSION;

        // le dossier ou on trouve les templates
        $loader = new FilesystemLoader('../src/template');
    
        // initialiser l'environement Twig
        $this->twig = new Environment($loader,[
            'debug' => true,
            // ...
        ]);
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());

        $this->conn = (new SQLiteConnection())->connect();

        $sqlite = new SQLiteCreateTable($this->conn);

        // create new tables
        $sqlite->createTables();

        $this->session = new SessionManager;

        if(!empty($_POST)){
            $this->post = (new Security)->cleanInput($_POST);
        }
    }

    function home ()
    {
        try {
            // load template
            $template = $this->twig->load('pages/index.html.twig');

            if(!empty($this->post[0]) && !empty($this->post[1]) && !empty($this->post[2]) && !empty($this->post[3])){
                $this->post["name"] = $this->post[0]; 
                $this->post["subject"] = $this->post[1];  
                $this->post["email"] = $this->post[2]; 
                $this->post["body"] = $this->post[3];
                if (filter_var($this->post['email'], FILTER_VALIDATE_EMAIL)) {
                    new sendMail($this->post["name"],$this->post["email"],'griffont.rf@gmail.com',$this->post["body"],$this->post["subject"]);
                };
            };
                echo $template->render(['current'=>'home' , 'session'=>$this->session->getSession()
                ]);
            
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function articles ()
    {
        try {
            // load template
            $template = $this->twig->load('pages/articles.html.twig');
            // getAllArticle
            $sqlite = new SQLiteGet($this->conn);
            $articles = $sqlite->getAllArticles();
            // set template variables
            // render template
            echo $template->render(['current'=>'articles' , 'session'=>$this->session->getSession() , 'articles'=>$articles
            ]);
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function article($id)
    {
        try {
            // load template
            $template = $this->twig->load('pages/post.html.twig');

            $sqlite = new SQLiteGet($this->conn);
            $article = $sqlite->getArticle($id);
            $comments = $sqlite->getAllCommentFromArticle($id);
            if (!empty($this->post)) {
                $cleanarray = (new Security)->cleanInput($this->post);
                $title = $cleanarray[0];
                $body = $cleanarray[1];
                $article_id = $cleanarray[2];
                $users_id = $cleanarray[3];
                $sqlite = new SQLiteSet($this->conn);
                $sqlite->setComment($title,$body,$users_id,$article_id);
            }
            // set template variables
            // render template
            echo $template->render(array('session'=>$this->session->getSession(),'article' => $article,'comments'=>$comments
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
            echo $template->render(array('session'=>$this->session->getSession()
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function register ()
    {
        try {
            // load template
            $template = $this->twig->load('pages/logon.html.twig');
            if (!empty($this->post)) {
                    $is_ok=0;
                    $cleanarray = (new Security)->cleanInput($this->post);
                    $allusers = (new SQLiteGet($this->conn))->getAllUsers();
                    if ($allusers) {
                        for ($i=0; $i <count($allusers) ; $i++) {
                            var_dump($allusers[$i],$cleanarray[0]);
                            die;
                            if ($allusers[$i] === $cleanarray[0]) {
                                $is_ok=0;
                            }else{
                                $is_ok=1;
                            }
                        }
                    }else{
                        $is_ok=1;
                    }
                if ($is_ok) {
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
                            $this->session->setSession('succes','Votre compte a bien etais créé');
                            header("Location: /login");
                            exit();
                        }else{
                            //TODO mettre un message d'erreur
                            $this->session->setSession('error','Votre compte a pas etais créé');
                            header("Location: /");
                            exit();
                        }
                    }else{
                        echo $template->render(array('current'=>'logon','error'=>'vous n\'avez pas mis les deux meme mot de passe'));}
                }else{
                    echo  $template->render(array('current'=>'logon','error'=>'le nom de compte existe deja'));}
            }else{
                    // set template variables
                    // render template
                    echo $template->render(array('current'=>'logon','session'=>$this->session->getSession()
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
            if (!empty($this->post)) {
                $this->post['login'] = $this->post[0];
                $this->post['password'] = $this->post[1];
                $login = $this->post['login'];
                $password = $this->post['password'];
                $sqlite = new SQLiteGet($this->conn);
                $res = $sqlite->getUser($login);
                $res = $res[0];
                if (!empty($res)) {
                    $verif = password_verify($password,$res['password']);
                    if ($verif == true) {
                        $role = $res['type'];
                        $name = $res['login'];
                        $id = $res['id'];
                        $this->session->setSession('roles', $role);
                        $this->session->setSession('login', $name);
                        $this->session->setSession('id', $id);
                        header("Location: /");
                    }
                }else{
                    echo "ce compte existe pas !!!";
                }
            }

            // set template variables
            // render template
            echo $template->render(array('current'=>'login','session'=>$this->session->getSession()
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
        $sqget = new SQLiteGet($this->conn);
        $posts = $sqget->getAllArticles();
        $users = $sqget->getAllUsers();
        if(!empty($this->post)){

        }
        try {   
            // load template
            $template = $this->twig->load('pages/admin.html.twig');
        
            // set template variables
            // render template
            echo $template->render(['current'=>'admin','session'=>$this->session->getSession(),'posts'=>$posts,'users'=>$users
            ]);
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function PasswordLost()
    {
        try {
            if(!empty($this->post)){
                (new Debug)->vardump($this->post);
            }
            // load template
            $template = $this->twig->load('pages/lost.html.twig');
        
            // set template variables
            // render template
            echo $template->render(array('current'=>'mdplost'
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function LoginLost()
    {
        try {
            if(!empty($this->post)){
                (new Debug)->vardump($this->post);
            }
            // load template
            $template = $this->twig->load('pages/lostgin.html.twig');
        
            // set template variables
            // render template
            echo $template->render(array('current'=>'mdplost'
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function profil()
    {
        $infos_user = $this->session->getSession();
        if(isset($this->post['delete'])){
            $sqlite = new SQLiteDelete($this->conn);
            $sqlite->DeleteUser($infos_user['id']);
            header("Location: /");
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

    function modify()
    {
        $infos_user = $this->session->getSession();
        $sqlite = new SQLiteGet($this->conn);
        $user_info_bdd = $sqlite->getUser($infos_user['login']);

        if(isset($this->post["changepassword"])){
           $verif = password_verify($this->post['old_password'],$user_info_bdd['password']);
           if($verif === true){
            $password= $this->post["password"];
            $confPassword = $this->post["password_rec"];
            if ($password === $confPassword) {
                if ($infos_user['id'] === $user_info_bdd['id']) {
                    $password = password_hash($password,PASSWORD_DEFAULT);
                    $sqlite = new SQLiteSet($this->conn);
                    $user_info_bdd = $sqlite->updateUser($infos_user['id'],$password);
                    $this->session->setSession('succes','Votre mot de passe a bien etais changer');
                    header("Location: /profil");
                }
            }
           }
        }
        
        try {   
            // load template
            $template = $this->twig->load('pages/modify.html.twig');
        
            // set template variables
            // render template
            echo $template->render(array('current'=>'modify','session'=>$infos_user
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function newarticles()
    {
        $infos_user = $this->session->getSession();
        if (isset($this->post)) {
            if(isset($_FILES['file'])){
                $tmpName = $_FILES['file']['tmp_name'];
                $name = $this->session->session['login'].$this->session->session['id'].$_FILES['file']['name'];
                move_uploaded_file($tmpName, './uploads/'.$name);
            }
            $articleTitle = $this->post[0];
            $articleBody = $this->post[1];
            $userId = $this->session->session['id'];
            $sqlite = new SQLiteSet($this->conn);
            $sqlite->setArticles($articleTitle,$articleBody,$userId,$name);
            $this->session->setSession('succes','Votre article est bien poster');
            header("Location: /");
        }
        try {   
            // load template
            $template = $this->twig->load('pages/createdArticles.html.twig');
        
            // set template variables
            // render template
            echo $template->render(array('current'=>'createdArticles','session'=>$infos_user
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function Deletecomms($id){
        // load template
        $template = $this->twig->load('pages/articles.html.twig');

        $sqlite = new SQLiteGet($this->conn);
        $articles = $sqlite->getAllArticles();

        $sqlite = new SQLiteDelete($this->conn);
        $res = $sqlite->DeleteComment($id);
        header("Location: /articles");
    }
}