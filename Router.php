<?php

$url = rtrim($url);
        $base = '/^~$/';
        $url = str_replace('/', '\/', $url);
        if(substr($url, -1) !== '/') {
            $str = substr($url, -1);
            $url = preg_replace("/$str$/", "$str\/", $url);
            echo $url . '<br>';
        }
        $url = preg_replace('/\{(.*)\}/', '[0-9]*', $url);