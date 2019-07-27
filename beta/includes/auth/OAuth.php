<?php
//The OAuth script that Authenticate users session credential
//It uses the Authenticate Class
//Declare a global response array to hold response messages
$GLOBALS["response"] = (object)array("auth" => "none", "access" => "none", "username" => "none", "office" => "none");

$OAuth = array("token" => auth\Session::get("token"), "user_id" => utilities\Session::get("user_id"));

//create new database connection object
$db = new DatabaseConnection();

//Check if OAuth is valid
$state = utilities\Authenticate::isValidAuth($db->conn, $OAuth);

//check through returned state and handle appropriately
if($state === 0){
    //OAuth is completed access variables/token is valid
    http_response_code(200); //access is forbiden
    $GLOBALS["response"]->auth = 'Session is valid';
    $GLOBALS["office"] = auth\Session::get("office");
    $GLOBALS["username"] = auth\Session::get("username");
}
elseif($state === 1){
    //OAuth verication is completed and credential is not valid
    http_response_code(403); //access is forbiden
    $GLOBALS["response"]->auth = 'Invalid session token, please try login again';
}
elseif($state === 2){
    //OAuth verification is completed and credential has expired
    http_response_code(403); //access forbiden
    $GLOBALS["response"]->auth = 'Session expired after 15 minutes of inactivity';
}
else{
    //OAuth verication is completed and credential is not valid
    http_response_code(403); //access is forbiden
    $GLOBALS["response"]->auth = 'Unknown internal error, please try login again';
}


