<?php
require_once('setup.php');

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

$router->authenticate()->get('/model/{id}/test/{oloko}', function ($req, $res) {
    $user = new User\Model();
    $user->test(); 
    return $res->status(200)->json("hello");
});

$router->listen();
