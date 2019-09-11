<?php
/**
 * The login file that initiates an active session for the users
 * It automatically redirects to users appropriate module after a successful login
 */

//include the general required file
require_once 'modules/required.php';

//create new database connection object
$db = new DatabaseConnection();

$data = (object)$_POST; //retrieve post inputs
if(empty($data->username) && empty($data->password)){
    echo '{"fail":"Username or Password cannot be empty"}';
    exit();
}

$login = new database\LoginAccess(new database\SQLHandler($db->conn));
$user = $login->select_single(null,array("username" => $data->username));
$login->close();
//check if username exist
if(!empty($user)){
    if(password_verify($data->password,$user["password"])){
        //check if the system is awake
        /**
        if($user["user_type"] != 2){
            $day = strftime("%A",time());
            if((strcasecmp($day, 'saturday') == 0) || (strcasecmp($day, 'sunday') == 0)){
                echo '{"fail":"It is weekend, Bethsaida System will be back 7:00am on Monday"}';
                exit();
            }

            $date = date('Y-m-d H:i:s');
            $date = strtotime($date);
            $hour = date('H', $date);
            if($hour < 7 || $hour > 18){
                echo '{"fail":"Bethsaida system is on lock down. reopens at 7:00am and closes at 7:00pm on working days"}';
                exit();
            }
        }
        */
        //check if user ia not restricted
        if(!$user["access"]){
            echo '{"fail":"Your user account is restricted, contact the administrator"}';
            exit();
        }
        //clear every session with this user_id from database
        $session = new database\SessionAccess(new database\SQLHandler($db->conn));
        $session->delete(array("user_id" => $user['id']));
        $session->close();
        //At this point login is successful: create a session for the user
        $token = utilities\CryptoLib::randomHex(64);
        $time = date("Y-m-d H:i:s");
        $OAuth = array("id" => "",
                       "token" => $token,
                       "last_access" => $time,
                       "user_agent" => $_SERVER['HTTP_USER_AGENT'],
                       "ip_address" => $_SERVER['REMOTE_ADDR'],
                       "user_id" => $user['id'],
                       "timestamp" => $time
                    );
        $session = new database\SessionAccess(new database\SQLHandler($db->conn));
        $session->add(new database\models\Session($OAuth));
        $session->close();

        //update last login
        $login = new database\LoginAccess(new database\SQLHandler($db->conn), $user['id']);
        $login->updateLastLogin($time);
        $login->close();

        //create new session
        $session_auth = ["token" => $token,
                         "user_id" => $user['id'],
                         "username" => $user['username'],
                         "office"   => $user['office'],
                         "user_type"=> $user['user_type']
                        ];
        utilities\Session::set($session_auth);

        //get user redirection module
        $type = new database\UsersTypeAccess(new database\SQLHandler($db->conn), $user["user_type"]);
        $type_name = $type->select_single("name");
        $major_link = "http://localhost/laser/beta/app";
        if(strcasecmp($type_name['name'], 'administrator') === 0){
            $module = "administrator";
            $major_link = "http://localhost/laser/modules";
        }
        elseif(strcasecmp($type_name['name'], 'accountant') === 0){
            $module = "Central Management Unit";
        }
        elseif(strcasecmp($type_name['name'], 'managing director') === 0){
            $module = "Central Management Unit";
        }
        elseif(strcasecmp($type_name['name'], 'engineering') === 0){
            $module = "engineering";
        }
        elseif(strcasecmp($type_name['name'], 'estate') === 0){
            $module = "estate";
        }
        elseif(strcasecmp($type_name['name'], 'investment') === 0){
            $module = "Branch Manager";
        }
        elseif(strcasecmp($type_name['name'], 'secretary') === 0){
            $module = "Branch Secretary";
        }
        else{
            $module = "none";
        }
        $office = new database\OfficeAccess(new database\SQLHandler($db->conn), $user['office']);
        $location = $office->select_single();
        $response = [
                     "success" => "Login successful, Loading Workspace...",
                     "module" => $module,
                     "username" => $user["username"],
                     "office" => $location["location"] .', '.$location["description"],
                     "link"=> $major_link,
                     "app_version"=>'2.1.2'
                    ];
        echo json_encode($response);
        exit();
    }
    else{
        echo '{"fail":"Incorrect username or password"}';
        exit();
    }
}
else{
    echo '{"fail":"Username does not exist, please contact admin for enrolement"}';
    exit();
}
