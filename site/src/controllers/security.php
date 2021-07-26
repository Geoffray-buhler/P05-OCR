<?php

namespace Controller;

class Security 
{
    //clean the user's input
    function cleanInput($enter)
    {
        $filtered = [];
        if (!empty($enter)) {
            foreach ($enter as $key => $value) {
                array_push($filtered,filter_var($value, FILTER_SANITIZE_STRING));
            }
        }
        return $filtered;
    }
}