<?php

class Router
{
    private $routes = [];

    public function add($method, $path, $callback, $roles)
    {
        $this->routes[] = compact('method', 'path', 'callback', "roles");
    }

    public function dispatch($request)
    {
        if ($request->getMethod() === 'POST' && !validateCsrfToken()) {
            redirect('');
        }

        foreach ($this->routes as $route) {
            $isRouteAcceptParam = strpos($route['path'], "/{id}") != false;
            $param = null;
            if ($isRouteAcceptParam) {
                $route['path'] = str_replace('/{id}', '', $route['path']);
                $param = str_replace($route['path'], '', $request->getPath());
            }

            $requestPath = $param ? str_replace($param, '', $request->getPath()) : $request->getPath();
            if ($route['method'] === $request->getMethod() &&
                $route['path'] === $requestPath) {


                if (isLoggedIn()) {
                    // If the user role matched one of the route roles
                    if (! in_array(user()->getRoleName(), $route['roles'])) {
                        continue;
                    }
                }
                else{
                    if (! in_array("visitor", $route['roles'])) {
                        redirect("login");
                    }
                }

                
                if (is_callable($route['callback'])) {
                    return call_user_func($route['callback']);
                }
                
                if (is_string($route['callback'])) {
                    [$page, $action] = explode('@', $route['callback']);
                    
                    // Require the page file
                    $pageFile = __DIR__ . '/../Pages/' . $page . '.php';
            
                    if (file_exists($pageFile)) {
                        require_once $pageFile;
                        
                        // Instantiate the page and call the action
                        $pageInstance = new $page();
                        return $param != null ? $pageInstance->$action(str_replace("/", "", $param)) : $pageInstance->$action();
                    } else {
                        http_response_code(500);
                        echo "Page file not found: $pageFile";
                        return;
                    }
                }
            }
        }

        redirect('');
    }
}