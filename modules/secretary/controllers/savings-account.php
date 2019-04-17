<?php

/**
 * The API controller for savings account related operations performed by Investment staff.
 * The Controller performs the following actions
 * 
 * @action  Edit saving-account, View Users
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){
    
    //create new database connection object
    $db = new DatabaseConnection();

    if($action == "view"){
        //view savings details: Single | Multiple
        if(isset($id)){
            //savings id is set want to view only one savings
            $savings = new database\SavingsAccountAccess(new database\SQLHandler($db->conn), $id);
            $result = $savings->select_single(null);
            $savings->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        //savings id is not set check for next condition
        elseif(isset($key) && isset($value)){
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $stop = ($list_size * abs($page));
            $cmd = array("order_by" =>"id", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$stop");
            $search = array($key => $value);
            $savings = new database\SavingsAccountAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $result = $savings->select_multiple($filters,$search,$cmd);
            $savings->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        else{
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $stop = ($list_size * abs($page));
            $cmd = array("order_by" =>"id", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$stop");
            $savings = new database\SavingsAccountAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $clause = array("office"=>$GLOBALS["office"]);
            $result = $savings->select_multiple($filters,$clause,$cmd);
            $savings->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
    }
    elseif($action == "edit"){
        //Edit a selected user
        $savings = new database\SavingsAccountAccess(new database\SQLHandler($db->conn), $id);
        $savings->update( new database\models\SavingsAccount($data));
        $savings->close();

        addLog( ["activity" => "update", "meta" => ["title" => "user updated savings account","user_id" => $id]]);
    }
}
 else{
    //OAuth is not valid exit controller
    echo json_encode($GLOBALS["response"]);
    exit();
}