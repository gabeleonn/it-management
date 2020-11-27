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