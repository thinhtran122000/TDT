<?php
// 'user' object
class Users{

    // database connection and table name
    private $conn;
    private $table_name = "users";

    // object properties
    public $id;
    public $name;
    public $email;
    public $address;
    public $phone;
    public $Account;
    public $password;
    public $avatar;
    public $created_at;
    public $updated_at;

    // constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // create new user record
function create(){

    // insert query
    $query = "INSERT INTO " . $this->table_name . "
        SET
            id=:id, name=:name, email=:email, address=:address, phone=:phone, Account=:Account, password=:password, avatar=:avatar, created_at=:created_at, updated_at=:updated_at";

    // prepare the query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->id = htmlspecialchars(strip_tags($this->id));
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->address = htmlspecialchars(strip_tags($this->address));
    $this->phone = htmlspecialchars(strip_tags($this->phone));
    $this->Account = htmlspecialchars(strip_tags($this->Account));
    $this->password = htmlspecialchars(strip_tags($this->password));
    $this->avatar = htmlspecialchars(strip_tags($this->avatar));
    $this->created_at = htmlspecialchars(strip_tags($this->created_at));
    $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

    // bind the values
    $stmt->bindParam(':id', $this->id);
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':address', $this->address);
    $stmt->bindParam(':phone', $this->phone);
    $stmt->bindParam(':Account', $this->Account);
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $password_hash);
    $stmt->bindParam(':avatar', $this->avatar);
    $stmt->bindParam(':created_at', $this->created_at);
    $stmt->bindParam(':updated_at', $this->updated_at);

    // execute the query, also check if query was successful
    if ($stmt->execute()) {
        return true;
    }

    return false;
}

    // check if given email exist in the database
function emailExists()
    {

        // query to check if email exists
        $query = "SELECT id, name, email, address, phone, Account, password, avatar, created_at, updated_at
            FROM " . $this->table_name . "
            WHERE email = ?
            LIMIT 0,1";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind given email value
        $stmt->bindParam(1, $this->email);

        // execute the query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        // if email exists, assign values to object properties for easy access and use for php sessions
        if ($num > 0) {

            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // assign values to object properties
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->address = $row['address'];
            $this->phone = $row['phone'];
            $this->Account = $row['Account'];
            $this->password = $row['password'];
            $this->avatar = $row['avatar'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];

            // return true because email exists in the database
            return true;
        }

        // return false if email does not exist in the database
        return false;
    }

    // update a user record
    public function update()
    {

        // if password needs to be updated
        $password_set = !empty($this->password) ? ", password = :password" : "";

        // if no posted password, do not update the password
        $query = "UPDATE " . $this->table_name . "
            SET
                firstname = :firstname,
                lastname = :lastname,
                email = :email
                {$password_set}
            WHERE id = :id";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind the values from the form
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);

        // hash the password before saving to database
        if (!empty($this->password)) {
            $this->password = htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }

        // unique ID of record to be edited
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>