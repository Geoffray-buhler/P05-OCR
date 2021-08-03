<?php

namespace Controller;

class SessionManager 
{

    public $session;

    //TODO Set cookie et Sessionmanager
    function __construct()
    {
        session_start();

        // for ($i=0; $i < count($_SESSION['data']); $i++) { 
        //     array_push($this->session,$_SESSION['data'][$i]);
        // }
    }

    function setSession($name,$entree){
        array_push($this->session,[$name => $entree]);
    }

    function getSession(){
        return $this->session;
    }
}