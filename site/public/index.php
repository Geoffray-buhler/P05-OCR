<?php

use Controller\Controller;
use Controller\Traitement;

require dirname(__DIR__).'\vendor\autoload.php';


$url = $_SERVER['REQUEST_URI'];

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
    default:
        (new Controller)->pages404();
}