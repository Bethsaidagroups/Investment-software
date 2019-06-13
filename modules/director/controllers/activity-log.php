




<?php

/**
 * The API controller for Activity log fetch  by administrator.
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){
    
    //create new database connection object
    $db = new DatabaseConnection();

if($action == "view"){
        //view users details: Single | Multiple
        if(isset($id)){
            //user id is set want to view only one user
            $log = new database\ActivityLogAccess(new database\SQLHandler($db->conn), $id);
            $result = $log->select_single();
            $log->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        //user id is not set check for next condition
        elseif(isset($key) && isset($value)){
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $cmd = array("order_by" =>"timestamp", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$list_size");
            $search = array($key => $value);
            $log = new database\ActivityLogAccess(new database\SQLHandler($db->conn));
            $result = $log->select_multiple(null,$search,$cmd);
            $log->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        else{
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $cmd = array("order_by" =>"timestamp", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$list_size");
            $log = new database\ActivityLogAccess(new database\SQLHandler($db->conn));
            $result = $log->select_multiple(null,null,$cmd);
            $log->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
    }
}
else{
   //OAuth is not valid exit controller
   echo json_encode($GLOBALS["response"]);
   exit();
}