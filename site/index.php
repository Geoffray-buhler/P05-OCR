<?php
include_once 'controlleurs/controlleur.php';
$url = $_SERVER['REQUEST_URI'];
if (parse_url($url)['path'] !== '/') {
    switch (parse_url($url)) {
        case '/contact':
            contact();
        break;
        case '/home':
            home();
        break;
        case '/*':
            pages404();
        break;
    }
}else{
    home();
}