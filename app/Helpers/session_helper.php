<?php
    session_start();

    // Flash message helper
    function flash($name = '', $message = ''){
        if(!empty($name)){

            if(!empty($message) && empty($_SESSION[$name])){
                $_SESSION[$name] = $message;

            }elseif(empty($message) && !empty($_SESSION[$name])){

                $message = $_SESSION[$name];
                unset($_SESSION[$name]);
                
                return $message;
            }

        }
    }

    function isLoggedIn(){
        if(isset($_SESSION['user_id'])){
            return true;
        }
        return false;
    }