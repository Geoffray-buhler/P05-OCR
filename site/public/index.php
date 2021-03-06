<?php

use Controller\Controller;
use Controller\SessionManager;

require_once '../vendor/autoload.php';

function index(){

    $session = new SessionManager();
    $session = $session->getSession();

    if(isset($_SERVER)){
        $url = filter_var($_SERVER['REQUEST_URI']);
    }

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
        "/admin/comments"=>"gestionCommentaire",
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
        $wherecome = $_SERVER['HTTP_REFERER'];
        if (strpos($wherecome,"admin")) {
            return $control->Deletecomms($idArticle,"/admin/comments");
        }
        return $control->Deletecomms($idArticle,"/articles");
    }
    
    if (strpos($url,'valid/comment')) {
        $idComment = substr($url,15);
        return $control->Validcomms($idComment);
    }

    // page proteger pour les administrateur du site 
    if($url === "/admin" || $url === "/post/create" || $url === "/admin/comments"){
        if ($session['roles'] === 'admin') {
            $newUri = $dictionnaire[$url];
            return $control->$newUri();
        }
        return $control->pages404();
    }
    $newUri = $dictionnaire[$url];
    // Routeur avec le dico ^^
    return $control->$newUri();
}

// call Router function
index();