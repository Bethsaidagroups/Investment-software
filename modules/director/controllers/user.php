<?php

/**
 * The API controller for User related operations performed by administrator.
 * The Controller performs the following actions
 * 
 * @action  Add User, Edit User, Delete User, View Users
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){
    
    //create new database connection object
    $db = new DatabaseConnection();

    if($action == "add"){
        //Add new user to the system
            $login = new database\LoginAccess(new database\SQLHandler($db->conn));
            //check if username already exist in the database
            if(!empty($data->username) && !empty($data->username) && !empty($data->office) && !empty($data->user_type) && !empty($data->meta)){
                //check if a director already exist
                $result = $login->select_single(null, array("user_type" => 2));
                if(!empty($result) && ($data->user_type == 2)){
                    http_response_code(400);
                    echo '{"error":"Cannot add more than one Managing Director"}';
                    exit();
                }
                //die($data->user_type);
                $result = $login->select_single(null, array("username" => $data->username));
                if(empty($result)){
                    //create Login model to hold new user data and reset some inner properties
                    $model = new database\models\Login($data);
                    $model->set_password(password_hash($model->get_password(),PASSWORD_DEFAULT));
                    $model->set_lastLogin(date("Y-m-d H:i:s"));
                    $model->set_access("0");
                    //now add it to logins relation
                    $login = new database\LoginAccess(new database\SQLHandler($db->conn));
                    $login->add($model);
                    $login->close();

                    addLog( ["activity" => "create", "meta" => ["title" => "new user added","username" => $data->username]]);
                    echo '{"success":"User account created successfully"}';
                }
                else{
                    http_response_code(400);
                    echo '{"error":"username already exist"}';
                    exit();
                }
            }
            else{
                http_response_code(400);
                echo '{"error":"Provide data to all the fields"}';
                exit();
            }
    }
    elseif($action == "view"){
        //view users details: Single | Multiple
        if(isset($user_id)){
            //user id is set want to view only one user
            $login = new database\LoginAccess(new database\SQLHandler($db->conn), $user_id);
            $result = $login->select_single("id,username,user_type,access,office,meta,last_login");
            $login->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        //user id is not set check for next condition
        elseif(isset($key) && isset($value)){
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $cmd = array("order_by" =>"id", "order_in"=>"ASC", "limit_start"=>"$start", "limit_stop"=>"$list_size");
            $search = array($key => $value);
            $login = new database\LoginAccess(new database\SQLHandler($db->conn));
            $filters = "id,username,user_type,access,office,meta,last_login";
            $result = $login->select_multiple($filters,$search,$cmd);
            $login->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        else{
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $cmd = array("order_by" =>"id", "order_in"=>"ASC", "limit_start"=>"$start", "limit_stop"=>"$list_size");
            $login = new database\LoginAccess(new database\SQLHandler($db->conn));
            $filters = "id,username,user_type,access,office,meta,last_login";
            $result = $login->select_multiple($filters,null,$cmd);
            $login->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
    }
    elseif($action == "get"){
        $type_inst = new database\UsersTypeAccess(new database\SQLHandler($db->conn));
        $types = $type_inst->select_multiple();
        $type_inst->close();
        
        $office_inst = new database\OfficeAccess(new database\SQLHandler($db->conn));
        $offices = $office_inst->select_multiple();
        $office_inst->close();

        $res = array("types" => null, "offices" => null);
        for($i=0; $i<count($types); $i++){
             $res["types"][$i]["id"] = $types[$i]["id"];
             $res["types"][$i]["name"] = $types[$i]["name"];
        }
        for($i=0; $i<count($offices); $i++){
             $res["offices"][$i]["id"] = $offices[$i]["id"];
             $res["offices"][$i]["location"] = $offices[$i]["location"];
             $res["offices"][$i]["type"] = $offices[$i]["type"];
             $res["offices"][$i]["description"] = $offices[$i]["description"];
        }
        echo json_encode($res);
    }
    elseif($action == "upload"){
        //Upload profile image
        try {
			$dir = ROOT_URL ."/contents/images/users/";
			$final_name = $dir .$username. ".jpg";
			move_uploaded_file($_FILES["file"]["tmp_name"], $final_name);
		}
		catch(Exception $e){
            http_response_code(400);
            echo '{"error":"This error occurred: '.$e.'"}';
            exit();
		}
        
    }
    elseif($action == "edit"){
        $login = new database\LoginAccess(new database\SQLHandler($db->conn), $user_id);
        $result = $login->select_single();
        if(strcasecmp(MODULE, 'Managing Director') == 0){
            //current user is a managing director
            if($result["user_type"] == 2){
                //user want to edit directors account
                if($data->access == 1){
                    //check if director maintains his position
                    if($data->user_type == 2){
                        $login = new database\LoginAccess(new database\SQLHandler($db->conn), $user_id);
                        $login->update( new database\models\Login($data));
                        $login->close();
                    }
                    else{
                        http_response_code(400);
                        echo '{"error":"Cannot change user type for Director account"}';
                        exit();
                    }
                }
                else{
                    http_response_code(400);
                    echo '{"error":"Cannot restrict Director account"}';
                    exit();
                }
            }
            else{
                //user wants to edit other users
                if($data->user_type == 2){
                    http_response_code(400);
                    echo '{"error":"Cannot change user to Managing Director account"}';
                    exit();
                }
                $login = new database\LoginAccess(new database\SQLHandler($db->conn), $user_id);
                $login->update( new database\models\Login($data));
                $login->close();
            }
        }
        else{
            //current user is not a managing director
            if($result["user_type"] == 2){
                //user want to edit directors account
                if($data->access == 1){
                    //user can only change status to open
                    $login->column_update(array("access" => "1"));
                    echo '{"success":"last update was successful"}';
                }
                else{
                    http_response_code(400);
                    echo '{"error":"Cannot restrict Director account"}';
                    exit();
                }
            }
            else{
                //user wants to edit other users
                if($data->user_type == 2){
                    http_response_code(400);
                    echo '{"error":"Cannot change user to Managing Director account"}';
                    exit();
                }
                $login = new database\LoginAccess(new database\SQLHandler($db->conn), $user_id);
                $login->update( new database\models\Login($data));
                $login->close();
            }
        }

        addLog( ["activity" => "update", "meta" => ["title" => "user updated","user_id" => $user_id]]);
    }
    elseif($action == "reset"){
        $login = new database\LoginAccess(new database\SQLHandler($db->conn), $user_id);
        $result = $login->select_single();
        if($result["user_type"] == 2){
            http_response_code(400);
            echo '{"error":"Cannot reset Director account password"}';
            exit();
        }
        //reset users to default password
        $rnd_pwd = utilities\CryptoLib::randomString(10);
        $pass_harsh = password_hash($rnd_pwd,PASSWORD_DEFAULT);
        $login = new database\LoginAccess(new database\SQLHandler($db->conn), $user_id);
        $login->column_update(array("password" => $pass_harsh));

        addLog( ["activity" => "reset", "meta" => ["title" => "user password reset","user_id" => $user_id]]);

        http_response_code(200);
        echo '{"success":"Password has been reset. New password is: ' .$rnd_pwd. '"}';
        exit();
    }
    elseif($action == "delete"){
        //delete a seleted user
        $login = new database\LoginAccess(new database\SQLHandler($db->conn), $user_id);
        $result = $login->select_single();
        if($result["user_type"] == 2){
            http_response_code(400);
            echo '{"error":"Cannot Delete Managing Director account"}';
            exit();
        }
        $login = new database\LoginAccess(new database\SQLHandler($db->conn), $user_id);
        $login->delete();
        $login->close();

        addLog( ["activity" => "delete", "meta" => ["title" => "user updated","user_id" => $user_id]]);
    }
}
 else{
    //OAuth is not valid exit controller
    echo json_encode($GLOBALS["response"]);
    exit();
}