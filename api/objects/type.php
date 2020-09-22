<?php
class Type{
  
    // database connection and table name
    private $conn;
    private $table_name = "type";
  
    // object properties
    public $id;
    public $name;
    public $category;
  
    public function __construct($db){
        $this->conn = $db;
    }
  
    // used by select drop-down list
    public function readAll(){
        //select all data
        $query = "SELECT
                    id, name, category 
                FROM
                    " . $this->table_name . "
                    LEFT JOIN
                        categort c
                            ON t.category = c.id
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
                id, name, category
            FROM
                " . $this->table_name . "
                LEFT JOIN
                    category c
                        ON t.category = c.id
            ORDER BY
                name";
  
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
  
    return $stmt;
}
}
?>
