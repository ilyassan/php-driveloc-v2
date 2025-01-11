<?php
    class BasePage
    {
        function render($path, $data = []){
            if ($path == "/") {
                $path = "/index";
            }
            if ($path[0] != "/") {
                $path = "/" . $path;
            }

            if ($path == "/signup" || $path == "/login") {
                $path = APPROOT . "View/auth" . $path . ".php";
            }
            else if (isLoggedIn() && user()->isAdmin()) {
                $path = APPROOT . "View/admin" . $path . ".php";
            }else{
                $path = APPROOT . "View/client" . $path . ".php";
            }

            $role = "client";
            if (isLoggedIn() && user()->isAdmin()) {
                $role = "admin";
            }
            
            if (file_exists($path)) {
                extract($data);
                require_once APPROOT . "View/". $role ."/components/header.php";
                require_once $path;
                require_once APPROOT . "View/". $role ."/components/footer.php";
            } else {
                http_response_code(404);
                echo "404 Not Found";
            }
        }
    }