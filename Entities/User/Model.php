<?php

namespace User;

class Model extends \Core\Model
{
    private function create()
    {
        $q = "CREATE TABLE IF NOT EXISTS users (
                id INT NOT NULL AUTO_INCREMENT,
                name VARCHAR(50) NOT NULL,
                PRIMARY KEY (id)
            );
        )";
        return $this->conn->execute($q);
    }

    public function save()
    {
        //Create table if no exists
        $this->create();
        //save the new User
        $q = 'INSERT INTO users (name) VALUES(
                :name
            );
        ';
        var_dump($this->attributes);
        $this->conn->execute($q, $this->attributes);
    }

    public function getMany($condition = NULL)
    {
        if($condition != NULL) {
            $q = "";
        }
        return $this->conn->execute('SELECT * FROM users;');
    }

    public function getOne($condition)
    {}
}