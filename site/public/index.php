<?php

use Controller\Controller;
use Controller\SessionManager;

require dirname(__DIR__).'\vendor\autoload.php';

function index(){

    $session = new SessionManager();
    $session = $session->getSession();

    $url = $_SERVER['REQUEST_URI'];

    // function router 

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
        default:
            (new Controller)->pages404();
    }
}

// call Router function
index();