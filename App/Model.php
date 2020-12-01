<?php 

namespace Core;

class Model
{

    public function to_json()
    {
        $self = [];
        foreach($this as $attribute => $value) {
            $self[$attribute] = $value;
        }
        return ["response" => $self];
    }

    public function from_json($json)
    {
        $json = json_decode($json);
        foreach($json as $attribute => $value) {
            $this->$attribute = $value;
        }
    }
}