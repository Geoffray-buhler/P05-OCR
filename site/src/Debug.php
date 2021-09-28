<?php

namespace App;

class Debug
{
    function vardump($enter){
        if (is_array($enter)) {
            foreach ($enter as $value) {
                echo '<pre style="background-color: #000000; color:#ffff10; padding:1rem;">';
                var_dump($value);
                echo '</pre>';
            }
        }
        else
        {
            echo '<pre style="background-color: #000000; color:#ffff10; padding:1rem;">';
            var_dump($enter);
            echo '</pre>';
        }
    }
}