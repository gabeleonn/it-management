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
        $url = explode('{', $url);
        $url = array_values(array_filter($url, function($item) {
            return strpos($item, '}');
        }));
        $params = [];
        foreach($url as $uri) {
            $params[] = explode('}', $uri); 
        }

        $params = array_values(array_filter($params, function($item) {
            return !strpos($item, '/'); // continua aqui
        }));
        
        echo '<pre>';    
        var_dump($params);
        echo '</pre>';    
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