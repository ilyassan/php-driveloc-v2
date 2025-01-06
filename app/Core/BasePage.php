<?php
    class BasePage
    {

        public function __construct()
        {
            if(!isLoggedIn()){
                redirect("login");
            }
        }

        function render($path, $data = []){
            if ($path == "/") {
                $path = "/index";
            }
            if ($path[0] != "/") {
                $path = "/" . $path;
            }

            if (isLoggedIn()) {
                if (user()->isAdmin()) {
                    $path = APPROOT . "View/admin" . $path . ".php";
                }else{
                    $path = APPROOT . "View/client" . $path . ".php";
                }
            }else{
                $path = APPROOT . "View" . $path . ".php";
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