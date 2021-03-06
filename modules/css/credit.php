<?php

/**
 * The API controller for Credit Transction related operations performed by Acountant.
 * The Controller performs the following actions
 * 
 * @action  retrieve transaction summary for Credit transacations View all debit transaction transaction
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){
    
    //create new database connection object
    $db = new DatabaseConnection();

    if($action == "view"){
        //view details: Single | Multiple
        if(isset($id)){
            //id is set want to view only one user
            $login = new database\AccountTransactionAccess(new database\SQLHandler($db->conn), $id);
            $result = $login->select_single();
            $login->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        //id is not set check for next condition
        elseif(isset($key) && isset($value)){
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $stop = ($list_size * abs($page));
            $cmd = array("order_by" =>"timestamp", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$stop");
            $search = array($key => $value, "type" => "credit", "status" => "completed");
            $login = new database\AccountTransactionAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $result = $login->selectWithClause($filters,$search, 'AND', $cmd);
            $login->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        else{
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $stop = ($list_size * abs($page));
            $cmd = array("order_by" =>"timestamp", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$stop");
            $login = new database\AccountTransactionAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $search = array("type" => "credit", "office" => $GLOBALS['office'], "status" => "completed");
            $result = $login->selectWithClause($filters,$search, 'AND', $cmd);
            $login->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
    }
}
else{
   //OAuth is not valid exit controller
   echo json_encode($GLOBALS["response"]);
   exit();
}