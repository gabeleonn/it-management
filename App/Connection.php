<?php
namespace Core;

class Connection
{
    private $dbname = 'management';
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    public $conn;

    public function __construct()
    {  
        try {
            $this->conn = new \PDO('mysql:host=localhost;dbname='.$this->dbname, $this->user, $this->pass);
        } catch (PDOException $e ) {
            echo $e->getMessage();
        }
        
    }

    private function setParams($stmt, $key, $value)
    {
        $stmt->bindParam($key, $value);
    }

    private function mountQuery($stmt, $params = [])
    {
        foreach( $params as $key => $value ) {
            $this->setParams($stmt, $key, $value);
          }
    }

    public function execute($query, $params = [])
    {
        $stmt = $this->conn->prepare($query);
        $this->mountQuery($stmt, $params);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
