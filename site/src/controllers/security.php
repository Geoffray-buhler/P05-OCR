<?php

namespace Controller;

use App\Debug;

class Security 
{
    //clean the user's input
    function cleanInput($enter)
    {
        $filtered = [];
        if (!empty($enter)) {
            foreach ($enter as $value) {
                array_push($filtered, htmlspecialchars($value));
            }
        }
        return $filtered;
    }
}