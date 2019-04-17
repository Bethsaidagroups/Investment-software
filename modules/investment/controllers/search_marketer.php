<?php

/**
 * The API controller for marketers search.
 * The Controller performs the following actions
 * 
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){
    
    //create new database connection object
    $db = new DatabaseConnection();

            $search = array("username" => $username);
            $login = new database\LoginAccess(new database\SQLHandler($db->conn));
            $filters = "id,username,user_type,access,office,meta,last_login";
            $result = $login->select_multiple($filters,$search,null);
            $login->close();
            if(empty($result)){
                http_response_code(400);
                exit();
            }
            else{
                exit();
            }
            echo utilities\JSONHandler::arrayToJSON($result);
}
else{
    //OAuth is not valid exit controller
    echo json_encode($GLOBALS["response"]);
    exit();
}