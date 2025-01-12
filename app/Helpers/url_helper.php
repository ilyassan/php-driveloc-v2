<?php
    // Page redirect
    function redirect($page){
        header('location: '. URLROOT . $page);
        exit();
    }

    function requestPath(){
        return "http://localhost" . $_SERVER['REQUEST_URI'];
    }

    function baseUrl(){
        return implode("/", array_slice(explode('/', requestPath()), 0, count(explode('/', requestPath())) - 1));
    }