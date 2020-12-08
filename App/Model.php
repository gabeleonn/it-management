<?php 

namespace Core;

class Model
{
    private $attributes = [];

    public function __set($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
    }

    public function __get($attribute)
    {
        if($attribute == "attributes")
        {
            return $this->attributes;
        }
        return $this->attributes[$attribute];
    }

    public function as_array()
    {
        $output = [];
        foreach($this->attributes as $key => $value) {
            $output[$key] = $value;
        }
        return $output;
    }

    protected function as_pdo_array()
    {
        $output = [];
        foreach($this->attributes as $key => $value) {
            if($key == 'id') {
                continue;
            }
            $output[':' . $key] = $value;
        }
        return $output;
    }
    
    
}