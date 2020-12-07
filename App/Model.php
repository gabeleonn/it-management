<?php 

namespace Core;

class Model
{
    private $attributes = [];
    private $model = '';
    protected $conn;

    public function __construct($model)
    {
        $this->model = $model;
        $this->conn = new Connection();
    }

    public function to_json()
    {
        $self = [];
        foreach($this->attributes as $attribute => $value) {
            $self[$attribute] = $value;
        }
        return ["response" => $self];
    }

    public function from_json($json)
    {
        $json = json_decode($json);
        foreach($json as $attribute => $value) {
            $this->attributes[$attribute] = $value;
        }
    }

    public function __set($attribute, $value)
    {
        $this->attributes[':' . $attribute] = $value;
    }

    public function __get($attribute)
    {
        if($attribute == "attributes")
        {
            return $this->attributes;
        }
        return $this->attributes[$attribute];
    }
    
}