<?php
class Product{
  
    // database connection and table name
    private $conn;
    private $table_name = "product";
  
    // object properties
    public $id;
    public $name;
    public $soluong;
    public $gia;
    public $avatar;
    public $category;
    public $type;
    public $content;
    public $created_at;
    public $updated_at;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
// read products
function read(){
  
    // select all query
    $query = "SELECT
                t.name as type_name, p.id, p.name, p.soluong, p.gia, p.avatar, p.category, p.type, p.content, p.created_at, p.updated_at
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    type t
                        ON p.type = t.id
            ORDER BY
                p.created_at DESC";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
}
    // create product
function create(){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                id=:id, name=:name, soluong=:soluong, gia=:gia, avatar=:avatar, category=:category, type=:type, content=:content, created_at=:created_at, updated_at=:updated_at";
  
    // prepare query
    $stmt = $this->conn->prepare($query);

        // sanitize
    $this->id = htmlspecialchars(strip_tags($this->id));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->soluong = htmlspecialchars(strip_tags($this->soluong));
    $this->gia=htmlspecialchars(strip_tags($this->gia));
    $this->avatar = htmlspecialchars(strip_tags($this->avatar));
    $this->category = htmlspecialchars(strip_tags($this->category));
    $this->type=htmlspecialchars(strip_tags($this->type));
    $this->content=htmlspecialchars(strip_tags($this->content));
    $this->created_at=htmlspecialchars(strip_tags($this->created_at));
    $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

        // bind values
    $stmt->bindParam(":id", $this->id);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":soluong", $this->soluong);
    $stmt->bindParam(":gia", $this->gia);
    $stmt->bindParam(":avatar", $this->avatar);
    $stmt->bindParam(":category", $this->category);
    $stmt->bindParam(":type", $this->type);
    $stmt->bindParam(":content", $this->content);
    $stmt->bindParam(":created_at", $this->created_at);
    $stmt->bindParam(":updated_at", $this->updated_at);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
      
}
// used when filling up the update product form
function readOne(){
  
    // query to read single record
    $query = "SELECT
                t.name as type_name, p.id, p.name, p.soluong, p.gia, p.avatar, p.category, p.type, p.content, p.created_at, p.updated_at
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    type t
                        ON p.type = t.id
            WHERE
                p.id = ?
            LIMIT
                0,1";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind id of product to be updated
    $stmt->bindParam(1, $this->id);
  
    // execute query
    $stmt->execute();
  
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    // set values to object properties
    $this->name = $row['name'];
    $this->soluong = $row['soluong'];
    $this->gia = $row['gia'];
    $this->avatar = $row['avatar'];
    $this->category = $row['category'];
    $this->type = $row['type'];
    $this->content = $row['content'];
    $this->created_at = $row['created_at'];
    $this->updated_at = $row['updated_at'];
}
// update the product
function update(){
  
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                id = :id,
                name = :name,
                soluong = :soluong,
                gia = :gia,
                avatar = :avatar,
                category = :category,
                type = :type,
                content = :content,
                created_at = :created_at,
                updated_at = :updated_at
            WHERE
                id = :id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);

        // sanitize
    $this->id = htmlspecialchars(strip_tags($this->id));
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->soluong = htmlspecialchars(strip_tags($this->soluong));
    $this->gia = htmlspecialchars(strip_tags($this->gia));
    $this->avatar = htmlspecialchars(strip_tags($this->avatar));
    $this->category = htmlspecialchars(strip_tags($this->category));
    $this->type = htmlspecialchars(strip_tags($this->type));
    $this->content = htmlspecialchars(strip_tags($this->content));
    $this->created_at = htmlspecialchars(strip_tags($this->created_at));
    $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

        // bind new values
    $stmt->bindParam(":id", $this->id);    
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":soluong", $this->soluong);
    $stmt->bindParam(":gia", $this->gia);
    $stmt->bindParam(":avatar", $this->avatar);
    $stmt->bindParam(":category", $this->category);
    $stmt->bindParam(":type", $this->type);
    $stmt->bindParam(":content", $this->content);
    $stmt->bindParam(":created_at", $this->created_at);
    $stmt->bindParam(":updated_at", $this->updated_at);
  
    // execute the query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}
// delete the product
function delete(){
  
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}
// search products
function search($keywords){
  
    // select all query
    $query = "SELECT
                t.name as type_name, p.id, p.name, p.soluong, p.gia, p.avatar, p.category, p.type, p.content, p.created_at, p.updated_at
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    type t
                        ON p.type = t.id
            WHERE
                p.name LIKE ? OR p.content LIKE ? OR t.name LIKE ?
            ORDER BY
                p.created_at DESC";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $keywords=htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";
  
    // bind
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);
    $stmt->bindParam(3, $keywords);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
}
// read products with pagination
public function readPaging($from_record_num, $records_per_page){
  
    // select query
    $query = "SELECT
                t.name as type_name, p.id, p.name, p.soluong, p.gia, p.avatar, p.category, p.type, p.content, p.created_at, p.updated_at
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    type t
                        ON p.type = t.id
            ORDER BY p.created_at DESC
            LIMIT ?, ?";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind variable values
    $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
  
    // execute query
    $stmt->execute();
  
    // return values from database
    return $stmt;
}
// used for paging products
public function count(){
    $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
  
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    return $row['total_rows'];
}
}
?>