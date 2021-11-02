<?php

use Controller\Controller;
use Controller\SessionManager;

require_once '../vendor/autoload.php';

function index(){

    $session = new SessionManager();
    $session = $session->getSession();

    $url = filter_var($_SERVER['REQUEST_URI']);

    $dictionnaire = [
        "/"=> "home",
        "/logon" =>  "register",
        "/login" =>  "login",
        "/articles" =>  "articles",
        "/admin" =>  "admin",
        "/profil/modify" => "modify",
        "/profil" =>  "profil",
        "/deconnexion" =>  "deconnexion",
        "/post/create" =>  "newarticles",
        "/lost/password" =>  "PasswordLost",
        "/lost/login"=> "LoginLost",
    ];

    $control = new Controller;

    // function router pour la modification des articles 
    if (strpos($url,'post/modify')) {
        if(preg_match("/\/(\d+)$/",$url,$matches))
        {
            $idArticle = $matches[1];
            return $control->modifyarticles($idArticle);
        }
    }

    // function router pour les article 
    if (strpos($url,'post')) {
        if(preg_match("/\/(\d+)$/",$url,$matches))
        {
            $idArticle = $matches[1];
            return $control->article($idArticle);
        }
    }

    if (strpos($url,'delete/comment')) {
        $idArticle = substr($url,16);
        return $control->Deletecomms($idArticle);
        }

    // page proteger pour les administrateur du site 
    if($url === "/admin" || $url === "/post/create"){
        if ($session['roles'] === 'admin') {
            return $control->$dictionnaire[$url];
        }
        return $control->pages404();
    }
    $newUri = $dictionnaire[$url];
    // Routeur avec le dico ^^
    return $control->$newUri();
}

// call Router function
index();