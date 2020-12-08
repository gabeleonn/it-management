<?php

require_once('vendor/autoload.php');

$json = file_get_contents('php://input');

$reqParams = [
    'body' => json_decode($json, true),
    'query' => $_GET,
    'url' => isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'],
    'method' => $_SERVER['REQUEST_METHOD'],
    'token' => isset($_SERVER["HTTP_AUTHORIZATION"]) ? $_SERVER["HTTP_AUTHORIZATION"] : NULL
];

$request = new Core\Request($reqParams);
$response = new Core\Response();
