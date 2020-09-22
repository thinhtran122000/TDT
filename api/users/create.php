<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// files needed to connect to database
include_once '../config/database.php';
include_once '../objects/users.php';
//include_once '../config/core.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$users = new Users($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// create the user
if(
    !empty($users->id) &&
    !empty($users->name) &&
    !empty($users->email) &&
    !empty($users->address) &&
    !empty($users->phone) &&
    !empty($users->Account) &&
    !empty($users->password) &&
    !empty($users->avatar) &&
    !empty($users->created_at) &&
    !empty($users->updated_at)
){
    $users->$id = $data->id;
    $users->name = $data->name;
    $users->email = $data->email;
    $users->address = $data->address;
    $users->phone = $data->phone;
    $users->Account = $data->Account;
    $users->password = $data->password;
    $users->avatar = $data->avatar;
    $users->created_at = $data->date('Y-m-d H:i:s');
    $users->updated_at = $data->date('Y-m-d H:i:s');

    if ($users->create()) {

        // set response code - 201 created
        http_response_code(200);

        // tell the user
        echo json_encode(array("message" => "User was created."));
    }

    // if unable to create the product, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create user."));
    }
}

// tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
}
?>