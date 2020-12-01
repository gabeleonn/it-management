<?php
namespace Core;

class Route
{
    public $url = '';
    public $methods = [];
    public $params = [];

    public function __construct($url, $method, $callback, $params)
    {
        $this->url = $url;
        $this->methods[$method] = $callback;
        $this->params = $params;
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