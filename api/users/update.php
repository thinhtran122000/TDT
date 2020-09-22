<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required to encode json web token
include_once '../config/core.php';
include_once '../libs/php-jwt/src/BeforeValidException.php';
//include_once '../libs/php-jwtr/src/ExpiredException.php';
include_once '../libs/php-jwt/src/SignatureInvalidException.php';
include_once '../libs/php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

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

// get jwt
$jwt = isset($data->jwt) ? $data->jwt : "";
 
// if jwt is not empty
if($jwt){
 
    // if decode succeed, show user details
    try {
 
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        // set user property values
        $users->id = $decoded->data->id;
        $users->name = $data->name;
        $users->email = $data->email;
        $users->address = $data->address;
        $users->phone = $data->phone;
        $users->Account = $data->Account;
        $users->password = $data->password;
        $users->avatar = $data->avatar;
        $users->created_at = $data->created_at;
        $users->updated_at = $data->updated_at;

        // update the user record
        if ($users->update()) {
            // we need to re-generate jwt because user details might be different
            $token = array(
                "iss" => $iss,
                "aud" => $aud,
                "iat" => $iat,
                "nbf" => $nbf,
                "data" => array(
                    "id" => $users->id,
                    "name" => $users->name,
                    "email" => $users->email,
                    "address" => $users->address,
                    "phone" => $users->phone,
                    "Account" => $users->Account,
                    "password" => $users->password,
                    "avatar" => $users->avatar,
                    "created_at" => $users->created_at,
                    "updated_at" => $users->updated_at
                )
            );
            $jwt = JWT::encode($token, $key);

            // set response code
            http_response_code(200);

            // response in json format
            echo json_encode(
                array(
                    "message" => "User was updated.",
                    "jwt" => $jwt
                )
            );
        }

        // message if unable to update user
        else {
            // set response code
            http_response_code(401);

            // show error message
            echo json_encode(array("message" => "Unable to update user."));
        }
    }

    // if decode fails, it means jwt is invalid
    catch (Exception $e) {

        // set response code
        http_response_code(401);

        // show error message
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
}
 
// show error message if jwt is empty
else{
 
    // set response code
    http_response_code(401);
 
    // tell the user access denied
    echo json_encode(array("message" => "Access denied."));
}
