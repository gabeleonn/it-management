<?php
namespace Core;

class Router
{
    public $routes = [];
    private $request = [];
    private $response = [];

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($url, $callback, $method='GET')
    {
        $this->addRoute($url, $method, $callback);
    }

    public function post($url, $callback, $method='POST')
    {
        $this->addRoute($url, $method, $callback);
    }

    public function patch($url, $callback, $method='PATCH')
    {
        $this->addRoute($url, $method, $callback);
    }

    public function delete($url, $callback, $method='DELETE')
    {
        $this->addRoute($url, $method, $callback);
    }

    public function addRoute($url, $method ,$callback)
    {
        if(substr($url, -1) !== '/') {
            $str = substr($url, -1);
            $url = preg_replace("/$str$/", "$str/", $url);
        }
        $params = $this->generate_params($url);
        $url = $this->generate_regex($url);
        if(isset($this->routes[$url])) {
            return $this->routes[$url]->setMethod($method, $callback);
        }
        
        return $this->routes[$url] = new Route($url, $method, $callback, $params);
    }

    public function generate_params($url)
    {
        $matches = [];
        $url = str_replace('{', '-', $url);
        $url = str_replace('}', '-', $url);
        preg_match_all('/\-(.*?)\-/', $url, $matches);
        return $matches[1];  
    }

    public function generate_regex($url)
    {
        $url = rtrim($url);
        $base = '/^~$/';
        $url = str_replace('/', '\/', $url);
        $url = preg_replace('/\{(\w*)\}/', '[0-9]*', $url);
        return str_replace('~', $url, $base);
    }

    public function set_route($callback)
    {
        if(isset($callback)){
            $callback($this->request, $this->response);
        } else {
            echo '404';
        }
    }

    public function authenticate()
    {
        return $this;
        // if($this->request->token != NULL) {
        //     return $this;
        // } else {
        //     $this->set_route(null);
        // }
    }

    public function listen()
    {
        $notFound = True;
        foreach($this->routes as $route) {
            $method = $this->request->method;
            if(preg_match($route->url, $this->request->url)) {
                $this->request->clean_params($route->url, $route->params);
                $this->set_route($route->methods[$method]);
                $notFound = False;
                break;
            }
        }
        if($notFound) {
            $this->set_route(null);
        }
    }
}