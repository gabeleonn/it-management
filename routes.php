<?php
require_once('setup.php');

$router = new Core\Router($request, $response);

$router->get('/user/', function ($req, $res) {
    //GET MANY with all configs
    $users = User\Model::getMany(
        $where = 'name = mark',
        $orderby = ['name', 'ASC'],
        $limit = 100
    );
    // GET MANY with no configs
    $users = User\Model::getMany();
    //GET ONE
    $user = User\Model::getUserById(5);
    //$user->name = 'Leon';
    //$user->update();
    //$user->delete();
    return $res->status(200)->json($user->as_array());
});

$router->listen();
