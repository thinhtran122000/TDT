<?php
class Category{
  
    // database connection and table name
    private $conn;
    private $table_name = "category";
  
    // object properties
    public $id;
    public $name;
    public $created_at;
    public $updated_at;
  
    public function __construct($db){
        $this->conn = $db;
    }
  
    // used by select drop-down list
    public function readAll(){
        //select all data
        $query = "SELECT
                    id, name, created_at, updated_at
                FROM
                    " . $this->table_name . "
                ORDER BY
                    name";
  
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
  
        return $stmt;
    }
    // used by select drop-down list
public function read(){
  
    //select all data
    $query = "SELECT
                id, name, created_at, updated_at
            FROM
                " . $this->table_name . "
            ORDER BY
                name";
  
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
  
    return $stmt;
}
}
?>
