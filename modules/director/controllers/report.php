<?php

/**
 * The API controller for Transction Report related operations performed by Acountant.
 * The Controller performs the following actions
 * 
 * @action  retrieve transaction summary for all transacations View all transaction
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){
    //echo $key;
    //die();
    //create new database connection object
    $db = new DatabaseConnection();

    $in_date = explode('HH', $timeframe);
    $from_date = $in_date[0];
    $to_date = $in_date[1];
    $filter_array = null;
    if($unit == "invest"){
        if($filter == 'all'){
            $query = "SELECT COUNT(*) AS total_trans, SUM(amount) AS total_amount FROM `ls_account_transactions` WHERE office = :office AND `date` BETWEEN '$from_date' AND '$to_date'";
            $filter_array =  array('office' => $office);
        }
        else{
            if($filter == 'category'){
                $categories = ['Savings Deposit', 'Savings Withdrawal', 'Loan Payout', 'Loan Excess', 'Invoice Payment', 'Fixed Deposit', 'Savings Invoice Payment', 'Target Savings'];
                $key = $categories[$key];
            }
            $filter_array =  array('office'=> $office,'filter' => $key);
            $query = "SELECT COUNT(*) AS total_trans, SUM(amount) AS total_amount FROM `ls_account_transactions` WHERE office = :office AND $filter = :filter AND `date` BETWEEN '$from_date' AND '$to_date'";
        }
        $db_handle = new database\SQLHandler($db->conn);
        $result = $db_handle->rawQuery($query, $filter_array);
        $db_handle->close();
        echo utilities\JSONHandler::arrayToJSON($result);
    }
    elseif($unit == 'eng'){

    }
    elseif($unit == 'estate'){

    }
}
else{
   //OAuth is not valid exit controller
   echo json_encode($GLOBALS["response"]);
   exit();
}