<?php

use App\Debug;
use Controller\Controller;
use Controller\SessionManager;

require_once dirname(__DIR__).'/vendor/autoload.php';

function index(){

    $session = new SessionManager();
    $session = $session->getSession();

    $url = filter_input($_SERVER['REQUEST_URI'],FILTER_DEFAULT);

    // function router pour la modification des articles 
    if (strpos($url,'post/modify')) {
        if(preg_match("/\/(\d+)$/",$url,$matches))
        {
            $idArticle = $matches[1];
            return (new Controller)->modifyarticles($idArticle);
        }
    }

    // function router pour les article 
    if (strpos($url,'post')) {
        if(preg_match("/\/(\d+)$/",$url,$matches))
        {
            $idArticle = $matches[1];
            return (new Controller)->article($idArticle);
        }
    }

    if (strpos($url,'delete/comment')) {
        $idArticle = substr($url,16);
        return (new Controller)->Deletecomms($idArticle);
        }

    switch (parse_url($url)['path'])
    {
        case '/':
            return (new Controller)->home();
        break;
        case '/logon':
            return (new Controller)->register();
        break;
        case '/login':
            return (new Controller)->login();
        break;
        case '/home':
            return (new Controller)->home();
        break;
        case '/articles':
            return (new Controller)->articles();
        break;
        case '/admin':
            if ($session['roles'] === 'admin') {
                return (new Controller)->admin();
            }
            else
            {
                return (new Controller)->pages404();
            }
        break;
        case '/profil/modify':
            return (new Controller)->modify();
        break;
        case '/profil':
            return (new Controller)->profil();
        break;
        case '/deconnexion':
            return (new Controller)->deconnexion();
        break;
        case '/post/create':
            if ($session['roles'] === 'admin') {
                return (new Controller)->newarticles();
            }
            else
            {
                return (new Controller)->pages404();
            }
        break;
        case '/lost/password':
            return (new Controller)->PasswordLost();
        break;
        case '/lost/login':
            return (new Controller)->LoginLost();
        break;
        default:
            return (new Controller)->pages404();
    }
}

// call Router function
index();