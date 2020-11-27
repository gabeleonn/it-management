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
        $url = $this->generate_regex($url);
        $this->addRoute($url, $method, $callback);
    }

    public function post($url, $callback, $method='POST')
    {
        $url = $this->generate_regex($url);
        $this->addRoute($url, $method, $callback);
    }

    public function addRoute($url, $method ,$callback)
    {
        if(isset($this->routes[$url])) {
            return $this->routes[$url]->setMethod($method, $callback);
        }
        
        return $this->routes[$url] = new Route($url, $method, $callback);
    }

    public function generate_regex($url)
    {
        $url = rtrim($url);
        $base = '/^~$/';
        $url = str_replace('/', '\/', $url);
        if(substr($url, -1) !== '/') {
            $str = substr($url, -1);
            $url = preg_replace("/$str$/", "$str\/", $url);
        }
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

    public function listen()
    {
        $notFound = True;
        foreach($this->routes as $route) {
            $method = $this->request->method;
            if(preg_match($route->url, $this->request->url)) {
                $this->request->clean_params($route->url);
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