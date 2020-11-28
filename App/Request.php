<?php
namespace Core;

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

    public function clean_params ($url, $params)
    {
        // /^\/model\/([0-9]*)\/$/
        $index = 0;
        $match = [];
        $url = str_replace('*', '', $url);
        $url = str_replace('[', '([', $url);
        $url = str_replace(']', ']*)', $url);
        preg_match($url, $this->url, $match);
        $match = array_values(array_filter($match, 'is_numeric'));
        foreach($params as $key) {
            $this->params[$key] = $match[$index];
            $index++;
        }
    }
}