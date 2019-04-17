<?php

/**
 * The API controller for Profile edit related operations performed by all users.
 * The Controller performs the following actions
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){
    
    //create new database connection object
    $db = new DatabaseConnection();

    if($action == 'get'){
        $login = new database\LoginAccess(new database\SQLHandler($db->conn));
        $result = $login->select_single("id,username,user_type,access,office,meta,last_login", array("username" => $username));
        $login->close();
        echo utilities\JSONHandler::arrayToJSON($result);
    }
    elseif($action == 'pwd'){
        $login = new database\LoginAccess(new database\SQLHandler($db->conn), $id);
        $result = $login->select_single();

        if(!empty($data->old) && !empty($data->new)){
            //check if password old is equal to new password
            if(password_verify($data->old,$result["password"])){
                //password is correct change password to new
                $pass_harsh = password_hash($data->new,PASSWORD_DEFAULT);
                $login->column_update(array("password" => $pass_harsh));
                $login->close();

                http_response_code(200);
                echo '{"success":"Password changed successfully!"}';
                exit();
            }
            else{
                http_response_code(400);
                echo '{"error":"Incorrect old password!"}';
                exit();
            }
        }
        else{
            http_response_code(400);
            echo '{"error":"Please fill out the required fields"}';
            exit();
        }
    }
    
}
else{
    //OAuth is not valid exit controller
    echo json_encode($GLOBALS["response"]);
    exit();
}