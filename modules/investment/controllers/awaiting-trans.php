<?php

/**
 * The API controller for Pending Transction related operations performed by Acountant.
 * The Controller performs the following actions
 * 
 * @action  change transaction statust, View pending transaction
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
            $search = array($key => $value, "status" => "awaiting");
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
            $search = array("status" => "awaiting", "office" => $GLOBALS['office']);
            $result = $login->selectWithClause($filters,$search, 'AND', $cmd);
            $login->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
    }
    elseif($action == "edit"){
        //Edit a selected user
        $trans = new database\AccountTransactionAccess(new database\SQLHandler($db->conn), $id);
        $result = $trans->select_single();
        //check for branch matching
        if(strcasecmp($GLOBALS['office'], $result['office']) !== 0){
            http_response_code(400);
            echo '{"error":"Can not update status of transaction initiated from different office location"}';
            exit();
        }
        if(strcasecmp($result['status'], 'awaiting') == 0){
            if(strcasecmp($data->status, 'confirm') == 0){
                //check if category is savings withdrawal
                if(strcasecmp($result['category'], 'Savings Withdrawal') == 0){
                    //it is debit transaction.
                    $savings = new database\SavingsAccountAccess(new database\SQLHandler($db->conn));
                    $account_data = $savings->select_single(null,array("account_no" => $result['account_no']));
                    $savings->close();
                    if($account_data['balance'] > $result['amount']){
                       //update transaction status to declined
                        $authorizers = json_decode($result['authorized_by'], true);
                        $authorizers['initial'] = $GLOBALS['username'];

                        $trans->column_update(array("status" => "pending"));
                        $trans->column_update(array("authorized_by" => json_encode($authorizers)));
                    }
                    else{
                        http_response_code(400);
                        echo '{"error":"Cannot complete this action: Target account is insufficient"}';
                        exit();
                    }
                }
                elseif(strcasecmp($result['category'], 'Savings Deposit') == 0){
                    $savings = new database\SavingsAccountAccess(new database\SQLHandler($db->conn));
                    $account_data = $savings->select_single(null,array("account_no" => $result['account_no']));
                    $savings->close();
                    
                    $savings = new database\SavingsAccountAccess(new database\SQLHandler($db->conn),  $account_data["id"]);
                        $savings->column_update(array("balance" => $account_data["balance"] + $result['amount']));
                        $savings->close();
                        //Notify users via SMS
                        $trans_meta = json_decode($result['meta_data']);
                        $balance = number_format($account_data["balance"] + $result['amount'], 2);
                        $amt = number_format($result['amount'], 2);
                        $msg = "Credit>>>Amt:NGN $amt Acc:" . $result['account_no'] . " Chn:" . $result['channel'] . " Desc:$trans_meta->name Date:" . $result['timestamp'] . " PMBal:NGN $balance";
                        //Get users mobile number.
                        $customer = new database\CustomerAccess(new database\SQLHandler($db->conn));
                        $bio_data = $customer->select_single('bio_data',array("account_no" => $result['account_no']));
                        $bio_meta = json_decode($bio_data['bio_data']);
                        if(!empty($bio_meta->mobile1)){
                            $sms = new utilities\SmsService($bio_meta->mobile1, $msg);
                            $sms->send(); //send sms here
                        }
                         //update transaction status to completed
                        $authorizers = json_decode($result['authorized_by'], true);
                        $authorizers['final'] = $GLOBALS['username'];

                        $trans->column_update(array("status" => "completed"));
                        $trans->column_update(array("authorized_by" => json_encode($authorizers)));
                }

            }
            elseif(strcasecmp($data->status, 'declined') == 0){
                //update transaction status to declined
                $authorizers = json_decode($result['authorized_by'], true);
                $authorizers['final'] = $GLOBALS['username'];

                $trans->column_update(array("status" => "declined"));
                $trans->column_update(array("authorized_by" => json_encode($authorizers)));
            }
            $trans->close();
            echo '{"success":"Transaction status was updated successfully"}';
            exit();
        }
        else{
            http_response_code(400);
            echo '{"error":"This transaction status cannot be changed"}';
            exit();
        }
        addLog( ["activity" => "update", "meta" => ["title" => "Head of Investment updated transaction","id" => $id]]);
    }
}
 else{
    //OAuth is not valid exit controller
    echo json_encode($GLOBALS["response"]);
    exit();
}