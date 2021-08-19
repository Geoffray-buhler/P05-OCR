<?php

use App\Debug;
use Controller\Controller;
use Controller\SessionManager;

require dirname(__DIR__).'\vendor\autoload.php';

function index(){

    $session = new SessionManager();
    $session = $session->getSession();

    $url = $_SERVER['REQUEST_URI'];

    // function router 

    //TODO regex 

    if (strpos($url,'/article/')) {
        if(preg_match("/\/(\d+)$/",$url,$matches))
        {
            $lastPartUri = $matches[1];
            (new Debug)->vardump($lastPartUri);
            die();
        }
    else
        {
            //Your URL didn't match.  This may or may not be a bad thing.
        }
    }

    switch (parse_url($url)['path'])
    {
        case '/':
            (new Controller)->home();
        break;
        case '/logon':
            (new Controller)->register();
        break;
        case '/login':
            (new Controller)->login();
        break;
        case '/home':
            (new Controller)->home();
        break;
        case '/articles':
            (new Controller)->articles();
        break;
        case '/admin':
            if ($session['roles'] === 'admin') {
                (new Controller)->admin();
            }else
            {
                (new Controller)->pages404();
            }
        break;
        case '/profil/modify':
            (new Controller)->modify();
        break;
        case '/profil':
            (new Controller)->profil();
        break;
        case '/deconnexion':
            (new Controller)->deconnexion();
        break;
        case '/post/create':
            if ($session['roles'] === 'admin') {
                (new Controller)->newarticles();
            }
            else
            {
                (new Controller)->pages404();
            }
        break;
        case '/lost/password':
            (new Controller)->PasswordLost();
        break;
        case '/lost/login':
            (new Controller)->LoginLost();
        break;
        default:
            (new Controller)->pages404();
    }
}



// call Router function
index();