<?php

/**
 * The API controller for Transction Quick Search related operations performed by Acountant.
 * The Controller performs the following actions
 * 
 * @action  retrieve transaction summary for debit transacations View all debit transaction transaction
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){
    
    //create new database connection object
    $db = new DatabaseConnection();

    if($action == "view"){
    
        if(isset($key) && isset($value)){
            if($key == 'date'){
                $list_size = 15;
                $start = ($list_size * abs($page)) - $list_size;
                $stop = ($list_size * abs($page));

                $in_date = explode('HH', $value);
                $from_date = $in_date[0];
                $to_date = $in_date[1];
                $db_handle = new database\SQLHandler($db->conn);
                $query = "SELECT * FROM `ls_account_transactions` WHERE `date` BETWEEN '$from_date' AND '$to_date' ORDER BY `timestamp` DESC LIMIT $start, $stop";
                $result = $db_handle->rawQuery($query);
                $db_handle->close();
                echo utilities\JSONHandler::arrayToJSON($result);
            }
            elseif($key == 'category'){
                $categories = ['Savings Deposit', 'Savings Withdrawal', 'Loan Payout', 'Loan Excess', 'Invoice Payment', 'Fixed Deposit', 'Savings Invoice Payment', 'Target Savings'];
                $list_size = 15;
                $start = ($list_size * abs($page)) - $list_size;
                $stop = ($list_size * abs($page));
                $cmd = array("order_by" =>"timestamp", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$stop");
                $search = array($key => $categories[$value]);
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
                $search = array($key => $value);
                $login = new database\AccountTransactionAccess(new database\SQLHandler($db->conn));
                $filters = null;
                $result = $login->selectWithClause($filters,$search, 'AND', $cmd);
                $login->close();
                echo utilities\JSONHandler::arrayToJSON($result);
            }
        }
        else{
    
        }
    }
}
else{
   //OAuth is not valid exit controller
   echo json_encode($GLOBALS["response"]);
   exit();
}