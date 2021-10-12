<?php

namespace Controller;

use App\Debug;

class SessionManager 
{

    public $session = [];
    public $user_info;

    function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            $this->startSession();
        }
        $this->initvalue();
        $this->id = session_create_id();
    }

    private function initvalue(){
        $this->id = "";
        $this->user_info =[];
        $this->messages = '';
    }

    function setSession($name,$entree){
        $_SESSION[$name] = $entree;
    }

    function getSession(){
        $this->session = $_SESSION;
        return $this->session;
    }

    function closeSession(){
        session_destroy();
    }

    function startSession(){
        session_start();
    }
}