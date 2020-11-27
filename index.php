<?php

class Route
{
    public $url = '';
    public $methods = [];

    public function __construct($url, $method, $callback)
    {
        $this->url = $url;
        $this->methods[$method] = $callback;
    }

    public function setMethod($method, $callback)
    {
        if(!isset($this->methods[$method])){
            return $this->methods[$method] = $callback;
            var_dump($this->methods);
        }
        return null;
    }
}

class Request
{
    public $url = '';
    public $method = '';
    public $body = [];
    public $params = [];
    public $query = [];

    public function __construct($req)
    {
        $this->url = $this->clean_url($req['url']);
        $this->method = $req['method'];
        $this->query = $req['query'];
        $this->body = $req['body'];
    }

    public function clean_url ($uri)
    {
        $str = substr($uri, -1);
        if($str !== '/') {
            $uri = preg_replace("/$str$/", "$str/", $uri);
        }
        return $uri;
    }

    public function clean_params ($url)
    {
        // /^\/model\/([0-9]*)\/$/
        $match = [];
        $url = str_replace('*', '', $url);
        $url = str_replace('[', '([', $url);
        $url = str_replace(']', ']*)', $url);
        preg_match($url, $this->url, $match);
        $this->params = array_values(array_filter($match, 'is_numeric'));
    }
}

class Response
{
    private $status = 200;

    public function json($json, $message = '')
    {
        $response = [
            'status' => $this->status,
            'data' => $json,
            'message' => $message
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function status($code)
    {
        $this->status = $code;
        return $this;
    }
}

$reqParams = [
    'body' => $_POST,
    'query' => $_GET,
    'url' => isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'],
    'method' => $_SERVER['REQUEST_METHOD']
];

$request = new Request($reqParams);
$response = new Response();

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
            $this->response->render(null);
        }
    }
}

$router = new Router($request, $response);

$router->get('/model/{id}/', function ($req, $res) {
    $test = ['name' => 'Hello World!'];
    $res->status(400)->json($test);
});

$router->get('/model/{id}/test/{test}/', function ($req, $res) {
    echo 'the separeteed';
});

$router->post('/model/{id}', function($req, $res) {
    echo 'actionnnn';
});

$router->get('/model', function($req, $res) {
    echo '/model/';
});

$router->listen();
