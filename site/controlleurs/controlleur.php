<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

include 'vendor/autoload.php';

function home ()
{
    try {
        // le dossier ou on trouve les templates
        $loader = new Twig\Loader\FilesystemLoader('template');
    
        // initialiser l'environement Twig
        $twig = new Twig\Environment($loader);
    
        // load template
        $template = $twig->load('pages/index.html.twig');
    
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
        // le dossier ou on trouve les templates
        $loader = new Twig\Loader\FilesystemLoader('template');
    
        // initialiser l'environement Twig
        $twig = new Twig\Environment($loader);
    
        // load template
        $template = $twig->load('pages/contact.html.twig');
    
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
        // le dossier ou on trouve les templates
        $loader = new Twig\Loader\FilesystemLoader('template');
    
        // initialiser l'environement Twig
        $twig = new Twig\Environment($loader);
    
        // load template
        $template = $twig->load('pages/404.html.twig');
    
        // set template variables
        // render template
        echo $template->render(array(
        ));
    
    } catch (Exception $e) {
        die ('ERROR: ' . $e->getMessage());
    }
}

