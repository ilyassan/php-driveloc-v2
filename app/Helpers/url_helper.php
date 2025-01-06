<?php
    // Page redirect
    function redirect($page){
        header('location: '. URLROOT . $page);
        exit();
    }

    function requestPath(){
        return "http://localhost" . $_SERVER['REQUEST_URI'];
    }