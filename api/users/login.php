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

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$users = new Users($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
//$users->email = $data->email;
$email_exists = $users->emailExists();

// generate json web token
include_once '../config/core.php';
include_once '../libs/php-jwt/src/BeforeValidException.php';
include_once '../libs/php-jwt/src/ExpiredException.php';
include_once '../libs/php-jwt/src/SignatureInvalidException.php';
include_once '../libs/php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

if ($email_exists && password_verify($data->password, $users->password)) {

    $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "iat" => $iat,
        "nbf" => $nbf,
        "data" => array(
            "id" => $users->id,
            "firstname" => $users->firstname,
            "email" => $users->email,
            "address" => $users->address,
            "phone" => $users->phone,
            "Account" => $users->Account,
            "password" => $users->password,
            "avatar" => $users->avatar,
            "create_at" => $users->created_at,
            "updated_at" => $users->updated_at
        )
    );

    // set response code
    http_response_code(200);

    // generate jwt
    $jwt = JWT::encode($token, $key);
    echo json_encode(
        array(
            "message" => "Successful login.",
            "jwt" => $jwt
        )
    );
}
 
// login failed
else{
 
    // set response code
    http_response_code(401);
 
    // tell the user login failed
    echo json_encode(array("message" => "Login failed."));
}
