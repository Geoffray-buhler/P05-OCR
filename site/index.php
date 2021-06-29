<?php

namespace App;

require 'vendor/autoload.php';

include_once 'controlleurs/controlleur.php';

use App\SQLiteConnection;

$pdo = (new SQLiteConnection())->connect();

if ($pdo != null)
    echo 'Connected to the SQLite database successfully!';
else
    echo 'Whoops, could not connect to the SQLite database!';

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