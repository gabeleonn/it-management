<?php

namespace User;

class Model extends \Core\Model
{

    // PRIVATES & PUBLICS
    private function create($conn)
    {
        $q = "CREATE TABLE IF NOT EXISTS users (
                id INT NOT NULL AUTO_INCREMENT,
                name VARCHAR(50) NOT NULL,
                PRIMARY KEY (id)
            );
        )";
        return $conn->execute($q);
    }

    public function save()
    {
        //Create table if no exists
        $conn = new \Core\Connection();
        $this->create($conn);
        //save the new User
        $q = 'INSERT INTO users (name) VALUES(
                :name
            );
        ';
        $conn->execute($q, $this->as_pdo_array());
    }

    public function update()
    {
        $conn = new \Core\Connection();
        $q = "UPDATE users SET name=:name WHERE id=$this->id;";
        $conn->execute($q, $this->as_pdo_array());
    }

    public function delete()
    {
        $conn = new \Core\Connection();
        $q = "DELETE FROM users WHERE id=$this->id;";
        $conn->execute($q);
    }

    // STATICS
    public static function cleanWhere($string)
    {
        $string = explode(' ', $string);
        return "$string[0] $string[1] '$string[2]'";
    }

    public static function getMany($where = NULL, $orderby = NULL, $limit = NULL)
    {
        $conn = new \Core\Connection();
        $where = isset($where) ? 'WHERE ' . \User\Model::cleanWhere($where) : 'WHERE 1';
        $order = isset($orderby) ? 'ORDER BY ' . "$orderby[0] $orderby[1]" : 'ORDER BY id ASC';
        $limit = isset($limit) ? 'LIMIT ' . $limit : 'LIMIT 100';
        $q = "SELECT * FROM users $where $order $limit;";
        return $conn->execute($q);
    }

    public static function getUserById($id)
    {
        $conn = new \Core\Connection();
        $where = "WHERE id = $id";
        $q = "SELECT * FROM users $where LIMIT 1;";
        $userArray = $conn->execute($q)[0];
        $user = new Model();
        foreach($userArray as $key => $value) {
            $user->$key = $value;
        }
        return $user;
    }
}