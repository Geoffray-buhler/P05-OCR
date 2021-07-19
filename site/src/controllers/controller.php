<?php

namespace Controller;

use Exception;
use Bdd\SQLiteCreateTable;
use Bdd\SQLiteConnection;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require dirname(__DIR__).'\..\vendor\autoload.php';

class Controller
{

    public $twig;

    function __construct()
    {
            // le dossier ou on trouve les templates
            $loader = new FilesystemLoader('../src/template');
        
            // initialiser l'environement Twig
            $this->twig = new Environment($loader);

            $sqlite = new SQLiteCreateTable((new SQLiteConnection())->connect());

            // create new tables
            $sqlite->createTables();
    }

    function home ()
    {
        try {
            // load template
            $template = $this->twig->load('pages/index.html.twig');
        
            // set template variables
            // render template
            echo $template->render(array('current'=>'home'
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function contact ()
    {
        try {
            // load template
            $template = $this->twig->load('pages/contact.html.twig');
        
            // set template variables
            // render template
            echo $template->render(array('current'=>'contact'
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function pages404 ()
    {
        try {
            // load template
            $template = $this->twig->load('pages/404.html.twig');
        
            // set template variables
            // render template
            echo $template->render(array(
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function register ()
    {
        try {

            if (!empty($_POST)) {
                var_dump($_POST);
            }

            // load template
            $template = $this->twig->load('pages/logon.html.twig');

            // set template variables
            // render template
            echo $template->render(array('current'=>'logon'
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function login ()
    {
        try {

            if (!empty($_POST)) {
                var_dump($_POST);
            }
            
            // load template
            $template = $this->twig->load('pages/login.html.twig');

            // set template variables
            // render template
            echo $template->render(array('current'=>'login'
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    function admin()
    {
        try {   
            // load template
            $template = $this->twig->load('pages/admin.html.twig');
        
            // set template variables
            // render template
            echo $template->render(array('current'=>'admin'
            ));
        
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }
}

