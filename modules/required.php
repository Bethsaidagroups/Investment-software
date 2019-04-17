<?php
//The required Scripts contains general required script for all operations

//include the configuration file
include_once $_SERVER['DOCUMENT_ROOT'] . "/laser/config.php";

//Define autolad function
//Autoload Function defintion
spl_autoload_register(function($className) {

    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    include_once ROOT_URL . '/include/' . $className . '.php';

});
//Declare a global response array to hold response messages
$GLOBALS["response"] = (object)array("auth" => "none", "access" => "none", "username" => "none", "office" => "none");

//Initialize Session Hnadler
utilities\Session::init();

//activitiy log function for add new activity
function addLog($data){
    $db =  new DatabaseConnection();
    $data["id"] = "";
    $data["user_id"] = utilities\Session::get("user_id");
    $data["username"] = utilities\Session::get("username");
    $data["date"] = date("Y-m-d");
    $data["timestamp"] = date("Y-m-d H:i:s");
    $model = new database\models\ActivityLog($data);
    $activity_log = new database\ActivityLogAccess(new database\SQLHandler($db->conn));
    $activity_log->add($model);
    $activity_log->close();
}

?>