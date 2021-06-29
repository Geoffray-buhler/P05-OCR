<?php
include_once 'controlleurs/controlleur.php';
$url = $_SERVER['REQUEST_URI'];
if (parse_url($url)['path'] !== '/') {
    switch (parse_url($url)['path']) {
        case '/contact':
            contact();
        break;
        case '/home':
            home();
        break;
        default:
            pages404();
    }
}else{
    home();
}