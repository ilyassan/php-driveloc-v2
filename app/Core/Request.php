<?php

class Request
{
    // Get the request method
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    // Get the request path
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        
        $basePath = '/driveloc/public';
        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        
        return parse_url($path, PHP_URL_PATH);
    }
}