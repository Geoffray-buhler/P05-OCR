<?php

use Controller\Controller;

require dirname(__DIR__).'\vendor\autoload.php';

function index(){

    $url = $_SERVER['REQUEST_URI'];

    // function router 

    switch (parse_url($url)['path'])
    {
        case '/':
            (new Controller)->home();
        break;
        case '/contact':
            (new Controller)->contact();
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
        case '/admin':
            (new Controller)->admin();
        break;
        case '/profil':
            (new Controller)->profil();
        break;
        case '/deconnexion':
            (new Controller)->deconnexion();
        break;
        default:
            (new Controller)->pages404();
    }
}

// call Router function
index();