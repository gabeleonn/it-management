<?php
require_once('vendor/autoload.php');

$reqParams = [
    'body' => $_POST,
    'query' => $_GET,
    'url' => isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'],
    'method' => $_SERVER['REQUEST_METHOD']
];

$request = new Core\Request($reqParams);
$response = new Core\Response();

$router = new Core\Router($request, $response);

$router->get('/model/{id}/', function ($req, $res) {
    $test = ['name' => 'Hello World!'];
    if($test != null) {
        return $res->status(200)->json($test);
    }
    return $res->status(400)->json();
});

$router->post('/model/{id}/', function ($req, $res) {
    $test = ['POST' => 'Hello World!'];
    if($test != null) {
        return $res->status(200)->json($test);
    }
    return $res->status(400)->json();
});

$router->patch('/model/{id}/', function ($req, $res) {
    $test = ['PATCH' => 'Hello World!'];
    if($test != null) {
        return $res->status(200)->json($test);
    }
    return $res->status(400)->json();
});

$router->delete('/model/{id}/', function ($req, $res) {
    $test = ['DELETE' => 'Hello World!'];
    if($test != null) {
        return $res->status(200)->json($test);
    }
    return $res->status(400)->json();
});

$router->get('/model/{id}/test/{oloko}', function ($req, $res) {
    echo 'okay';
});

$router->listen();
