<?php

namespace Controller;

use App\Debug;

class SessionManager 
{

    public $session = [];
    public $user_info;

    //TODO Set cookie et Sessionmanager
    function __construct()
    {
        session_start();
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
}