<?php

namespace Controller;

use Utils\PswGen;
use Bdd\SQLiteGet;
use Bdd\SQLiteSet;
use Controller\Mail;
use Bdd\SQLiteDelete;
use Bdd\SQLiteUpdate;
use Twig\Environment;
use Controller\Security;
use Bdd\SQLiteConnection;
use Bdd\SQLiteCreateTable;
use Controller\SessionManager;
use Twig\Loader\FilesystemLoader;

class Controller
{
    public $twig;
    public $conn;
    public $post;
    public $session;
    public $mail;

    function __construct()
    {
        $this->session = filter_var($_SESSION,FILTER_DEFAULT);

        // le dossier ou on trouve les templates
        $loader = new FilesystemLoader('../src/template');
    
        // initialiser l'environement Twig
        $this->twig = new Environment($loader,[
            'debug' => true,
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
        $template = $this->twig->load('pages/index.html.twig');
        $message = [];

        if(!empty($this->post[0]) && !empty($this->post[1]) && !empty($this->post[2]) && !empty($this->post[3])){
            $this->post["name"] = filter_var($this->post[0],FILTER_DEFAULT); 
            $this->post["subject"] = filter_var($this->post[1],FILTER_DEFAULT);  
            $this->post["email"] = $this->post[2];
            $this->post["body"] = filter_var($this->post[3],FILTER_DEFAULT);
            new Mail($this->post["name"],$this->post["email"],$this->post["body"],$this->post["subject"],'Contact blog !','contact@griffont39.yn.lu',$template,'contact');
            $message = 'Votre message est bien parti';
        };
            echo $template->render(['current'=>'home' , 'session'=>$this->session->getSession(),'succes'=>$message]);
    }

    function articles ()
    {
        $template = $this->twig->load('pages/articles.html.twig');
        $sqlite = new SQLiteGet($this->conn);
        $articles = $sqlite->getAllArticles();
        echo $template->render(['current'=>'articles' , 'session'=>$this->session->getSession() , 'articles'=>$articles
        ]);
    }

    function article($idArticle)
    {
        // load template
        $template = $this->twig->load('pages/post.html.twig');

        $sqlite = new SQLiteGet($this->conn);
        $article = $sqlite->getArticle($idArticle);
        $comments = $sqlite->getAllCommentFromArticle($idArticle);
        if (!empty($this->post)) {
            $cleanarray = (new Security)->cleanInput($this->post);
            $title = $cleanarray[0];
            $body = $cleanarray[1];
            $article_id = $cleanarray[2];
            $users_id = $cleanarray[3];
            $sqlite = new SQLiteSet($this->conn);
            $sqlite->setComment($title,$body,$users_id,$article_id,);
        }
        echo $template->render(array('session'=>$this->session->getSession(),'article' => $article,'comments'=>$comments
        ));
    }

    function pages404 ()
    {

        $template = $this->twig->load('pages/404.html.twig');

        echo $template->render(array('session'=>$this->session->getSession()
        ));
    }

    function register ()
    {

        $template = $this->twig->load('pages/logon.html.twig');
        if (!empty($this->post))
        {
            $cleanarray = (new Security)->cleanInput($this->post);
            $allusers = (new SQLiteGet($this->conn))->getAllUsers();
            $userExiste = null;
            foreach ($allusers as $value) {
                $userExiste = strtolower($value['login']) == strtolower($cleanarray[0]);
            }
            if (!$userExiste) {
                $password= $cleanarray[2];
                $confPassword = $cleanarray[3];
                if ($password === $confPassword) {
                    $login = $cleanarray[0];
                    $email = $cleanarray[1];
                    $cryptedPassword = password_hash($password,PASSWORD_DEFAULT);
                    $sqlite = new SQLiteSet($this->conn);
                    $res = $sqlite->setUser($login,$cryptedPassword,$email);
                    if($res > 0)
                    {
                        $template = $this->twig->load('pages/login.html.twig');
                        echo $template->render(array('current'=>'login','succes'=>'Votre compte a bien etais cr??e '));
                    }
                    else
                    {
                        $template = $this->twig->load('pages/logon.html.twig');
                        echo $template->render(array('current'=>'logon','error'=>'Une erreur ses produite '));
                    }
                }
                else
                {
                    $template = $this->twig->load('pages/logon.html.twig');
                    echo $template->render(array('current'=>'logon','error'=>'Vous n\'avez pas mis les m??me mot de passe !'));   
                }
            }
            else
            {
                $template = $this->twig->load('pages/logon.html.twig');
                echo $template->render(array('current'=>'logon','error'=>'Ce nom de compte est deja utilis?? !'));     
            }
        }
        else
        {
            $template = $this->twig->load('pages/logon.html.twig');
            echo $template->render(array('current'=>'logon'));
        }
    }

    function login ()
    {
        // load template
        $template = $this->twig->load('pages/login.html.twig');
        $thispageInfos = ['current'=>'login','session'=>$this->session->getSession()];
        if (!empty($this->post)) {
            $this->post['login'] = $this->post[0];
            $this->post['password'] = $this->post[1];
            $login = $this->post['login'];
            $password = $this->post['password'];
            $sqlite = new SQLiteGet($this->conn);
            $res = $sqlite->getUser($login);
            if (!empty($res)) {
                $res = $res[0];
                $verif = password_verify($password,$res['password']);
                if ($verif == true) {
                    $role = $res['type'];
                    $name = $res['login'];
                    $iduser = $res['id'];
                    $this->session->setSession('roles', $role);
                    $this->session->setSession('login', $name);
                    $this->session->setSession('id', $iduser);
                    header("Location: /");
                }
            }
            else
            {
                $thispageInfos = ['current'=>'login','session'=>$this->session->getSession(),'error'=>'Ce compte n\'existe pas'];
            }
        }
        echo $template->render(
            $thispageInfos
        );
    }

    function deconnexion()
    {
        $session = new SessionManager;
        header("Location: /");
        $session->closeSession();
    }

    function admin()
    {
        $sqget = new SQLiteGet($this->conn);
        $posts = $sqget->getAllArticles();
        $users = $sqget->getAllUsers();
        if(!empty($this->post)){
        }
        $template = $this->twig->load('pages/admin.html.twig');
        echo $template->render(['current'=>'admin','session'=>$this->session->getSession(),'posts'=>$posts,'users'=>$users
        ]);
    }

    function gestionCommentaire()
    {
        $sqget = new SQLiteGet($this->conn);
        $comments = $sqget->getAllComments();
        if(!empty($this->post)){
        }
        $template = $this->twig->load('pages/comments.html.twig');
        echo $template->render(['current'=>'admin','session'=>$this->session->getSession(),'comments'=>$comments
        ]);
    }

    function PasswordLost()
    {
        $template = $this->twig->load('pages/lost.html.twig');
        $thispageInfos = ['current'=>'mdplost'];
        if(!empty($this->post)){
            $sqget = new SQLiteGet($this->conn);
            $useracc = $sqget->getUserFromLogAndMail($this->post[0],$this->post[1]);
            if($useracc){
                $sqlset = new SQLiteSet($this->conn);
                $psw = (new PswGen)->generation(20);
                $cryptedPassword = password_hash($psw,PASSWORD_DEFAULT);
                $sqlset->updateUser($useracc[0]['id'],$cryptedPassword);
                new Mail($useracc[0]['login'],'contact@griffont39.yn.lu','Votre nouveau mot de passe est : '.$psw,'Oubliez pas de modifier ce Mot de passe lors de votre prochaine connexion !!!','Nouveau mot de passe',$this->post[1],$template,'pswlost');
                $thispageInfos = ['current'=>'mdplost','succes'=>'Vous allez recevoir un mail avec un mot de passe temporaire veuillez le changer s\'il vous plait'];
            }else{
                $thispageInfos = ['current'=>'mdplost','error'=>'Ce compte n\'existe pas '];
            }
        }
        echo $template->render(
            $thispageInfos
        );
    }

    function LoginLost()
    {
        $template = $this->twig->load('pages/lostgin.html.twig');
        $thispageInfos = ['current'=>'mdplost'];
        if(!empty($this->post)){
            $sqget = new SQLiteGet($this->conn);
            $user = $sqget->getUserWithEmail($this->post[0]);
            if ($user) {
                new Mail($user[0]['login'],'contact@griffont39.yn.lu',"Voici votre nom de compte : ".$user[0]['login'],"Votre nom de compte",'Nom de compte',$user[0]["email"],$template,'nomdecompte');
                $thispageInfos = ['current'=>'mdplost','succes'=>'Un email vous a ??t?? envoyer'];
            }
            else
            {
                $thispageInfos = ['current'=>'mdplost','error'=>'Cette adresse email existe pas !'];
            }
        }
        echo $template->render(
            $thispageInfos
        );
    }

    function profil()
    {
        $infos_user = $this->session->getSession();
        if(isset($this->post['delete'])){
            $sqlite = new SQLiteDelete($this->conn);
            $sqlite->DeleteUser($infos_user['id']);
            header("Location: /");
        }
        $template = $this->twig->load('pages/profil.html.twig');
        echo $template->render(array('current'=>'profil','session'=>$infos_user
        ));
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
        $template = $this->twig->load('pages/modify.html.twig');
        echo $template->render(array('current'=>'modify','session'=>$infos_user
        ));
    }

    function newarticles()
    {
        $infos_user = $this->session->getSession();
        if (isset($this->post)){
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
        $template = $this->twig->load('pages/createdArticles.html.twig');
        echo $template->render(array('current'=>'createdArticles','session'=>$infos_user
        ));
    }

    function modifyarticles($idArticle)
    {
        $infos_user = $this->session->getSession();
        $sqlite = new SQLiteGet($this->conn);
        $article = $sqlite->getArticle($idArticle);
        $message = [];
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
            $sqlite->updateArticles($articleTitle,$articleBody,$userId,$name,$idArticle);
            $message ='Votre article est bien poster';
            header("Location: /");
        }
        $template = $this->twig->load('pages/createdArticles.html.twig');
        echo $template->render(array('current'=>'createdArticles','session'=>$infos_user,'article'=>$article,'succes'=>$message
        ));
    }

    function Deletecomms($idComms,$page){
        $sqlite = new SQLiteDelete($this->conn);
        $sqlite->DeleteComment($idComms);
        header("Location: ".$page);
    }

    function Validcomms($idComms){
        $sqlite = new SQLiteUpdate($this->conn);
        $sqlite->ValidComment($idComms);
        header("Location: /admin/comments");
    }
}