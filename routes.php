<?php
require_once('setup.php');

$router = new Core\Router($request, $response);

// USERS ROUTES

$router->get('/users/', function ($req, $res) {
    //GET MANY with all configs
    // $users = User\Model::getMany(
    //     $where = 'name = mark',
    //     $orderby = ['name', 'ASC'],
    //     $limit = 100
    // );
    $users = User\Model::getMany();
    //GET ONE
    return $res->status(200)->json($users);
});

$router->get('/users/{id}', function ($req, $res)
{   
    // $user = User\Model::getUserById(5);
    //$user->name = 'Leon';
    //$user->update();
    //$user->delete();
    $id = $req->params['id'];
    $user = User\Model::getUserById($id);
    if($user->id == $id) {
        return $res->status(200)->json($user->as_array());
    }
    return $res->status(400)->json();
});

$router->post('/users/', function ($req, $res)
{
    $name = $req->body['name'];
    $newUser = new \User\Model();
    $newUser->name = $name;
    if($newUser->save()) {
        return $res->status(201)->json($newUser->as_array());
    }
    return $res->status(400)->json();
});

$router->patch('/users/{id}', function ($req, $res)
{
    $id = $req->params['id'];
    $body = $req->body;
    $user = \User\Model::getUserById($id);
    if($user != null) {
        $user->name = $body['name'];
        $user->update();
        return $res->status(200)->json($user->as_array());
    }
    return $res->status(400)->json(); 
});

$router->delete('/users/{id}', function ($req, $res)
{
    $id = $req->params['id'];
    $user = \User\Model::getUserById($id);
    if($user != null) {
        $user->delete();
        return $res->status(204)->json();
    }
    return $res->status(400)->json(); 
});


$router->listen();
