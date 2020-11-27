<?php

$method = $_SERVER['REQUEST_METHOD'];
$uri = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'];

class Router
{
    public $routes = [];
    private $request = [];
    private $response = [];

    public function __construct($URI, $HTTP_METHOD)
    {
        $this->request['url'] = $this->clean_uri($URI);
        $this->request['method'] = $HTTP_METHOD ;
    }

    public function clean_uri($uri)
    {
        $str = substr($uri, -1);
        if($str !== '/') {
            $uri = preg_replace("/$str$/", "$str/", $uri);
        }
        return $uri;
    }

    public function get($url, $callback, $method='GET')
    {
        $url = $this->generate_regex($url);
        $this->routes[] = [ 'callback' => $callback, 'url' => $url ];
    }

    public function generate_regex($url)
    {
        $url = rtrim($url);
        $base = '/^~$/';
        if (preg_match('/\/\{.*\}\//', $url)) {
            //  /^\/model\/[0-9]*\/$/
            $url = str_replace('/', '\/', $url);
            if(substr($url, -1) !== '/') {
                $str = substr($url, -1);
                $url = preg_replace("/$str$/", "$str\/", $url);
            }
            $url = preg_replace('/\{.*\}/', '[0-9]*', $url);
            
            return str_replace('~', $url, $base);
        }
        //  /^\/model\/$/
        $url = str_replace('/', '\/', $url);
        if(substr($url, -1) !== '/') {
            $str = substr($url, -1);
            $url = preg_replace("/$str$/", "$str\/", $url);
        }
        return str_replace('~', $url, $base);
    }

    public function listen()
    {
        array_map(function ($route) {
            if(preg_match($route['url'], $this->request['url'])) {
                $this->response['callback'] = $route['callback'];
            }
        }, $this->routes);
        if(isset($this->response['callback'])) {
            $this->response['callback']();
        } else {
            echo '404';
        }
    }
}

$router = new Router($uri, $method);

$router->get('/model/{outro}/', function () {
    echo 'hello';
});

$router->get('/model/{outro}', function() {
    echo 'actionnnn';
});

$router->get('/model', function() {
    echo '/model/';
});

$router->listen();
