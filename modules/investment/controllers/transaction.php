<?php

/**
 * The API controller for Transaction related operations performed by Investment staff.
 * The Controller performs the following actions
 * 
 * @action  Add trans deposit, Edit trans deposit, Delete trans deposit, View trans deposit
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){
    
    //create new database connection object
    $db = new DatabaseConnection();

    if($action == "deposit"){
        //Add new  to the system
            $trans = new database\AccountTransactionAccess(new database\SQLHandler($db->conn));
            try{
                    //create trans deposit model to hold new user data and reset some inner properties
                    //print_r($data);
                    $trans = new database\AccountTransactionAccess(new database\SQLHandler($db->conn));
                    $model = new database\models\AccountTransaction($data);
                    $model->set_office($GLOBALS['office']);
                    $model->set_authorizedBy(json_encode(array("initial" => $GLOBALS['username'])));
                    $model->set_date(date("Y-m-d"));
                    $model->set_timestamp(date("Y-m-d H:i:s"));
                    $model->set_amount(abs($model->get_amount()));
                    $model->set_status("completed");
                    $model->set_type("credit");
                    $model->set_category("Savings Deposit");

                        $savings = new database\SavingsAccountAccess(new database\SQLHandler($db->conn));
                        $account_data = $savings->select_single(null,array("account_no" => $model->get_accountNo()));
                        $savings->close();

                        $savings = new database\SavingsAccountAccess(new database\SQLHandler($db->conn),  $account_data["id"]);
                        $savings->column_update(array("balance" => $account_data["balance"] + $model->get_amount()));
                        $savings->close();

                    $trans->add($model); //add new trans deposit to data base
                    $trans->close();
                    //Notify users via SMS
                    $trans_meta = json_decode($model->get_metaData());
                    $balance = number_format($account_data["balance"] + $model->get_amount(), 2);
                    $amt = number_format($model->get_amount(), 2);
                    $msg = "Credit>>>Amt:NGN $amt Acc:" . $model->get_accountNo() . " Chn:" . $model->get_channel() . " Desc:$trans_meta->name Date:" . $model->get_timestamp() . " PMBal:NGN $balance";
                    //echo $msg;
                    //Get users mobile number.
                    $customer = new database\CustomerAccess(new database\SQLHandler($db->conn));
                    $bio_data = $customer->select_single('bio_data',array("account_no" => $model->get_accountNo()));
                    $bio_meta = json_decode($bio_data['bio_data']);
                    if(!empty($bio_meta->mobile1)){
                        $sms = new utilities\SmsService($bio_meta->mobile1, $msg);
                        $sms->send(); //send sms here
                    }
        addLog( ["activity" => "create", "meta" => ["title" => "new savings account deposit","account_no" => $model->get_accountNo(), "by" => $GLOBALS['username']]]);
                    echo '{"success":"The deposit transaction was successful"}';
            }
            catch(Exception $e){
                http_response_code(400);
                //die($e);
                echo '{"error":"Some inportant fields might be missing in the form"}';
                exit();
            }
    }
    elseif($action == "view"){
        //view trans deposit details: Single | Multiple
        if(isset($id)){
            //trans deposit id is set want to view only one trans deposit
            $trans = new database\AccountTransactionAccess(new database\SQLHandler($db->conn), $id);
            $result = $trans->select_single(null);
            $trans->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        //trans deposit id is not set check for next condition
        elseif(isset($key) && isset($value)){
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $cmd = array("order_by" =>"timestamp", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$list_size");
            $search = array($key => $value);
            $trans = new database\AccountTransactionAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $result = $trans->select_multiple($filters,$search,$cmd);
            $trans->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        else{
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $cmd = array("order_by" =>"timestamp", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$list_size");
            $trans = new database\AccountTransactionAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $clause = array("office"=>$GLOBALS["office"]);
            $result = $trans->select_multiple($filters,$clause,$cmd);
            $trans->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        
    }
    elseif($action == "withdraw"){
        //Cash out selected trans deposit
        $trans = new database\AccountTransactionAccess(new database\SQLHandler($db->conn));
            try{
                    //create trans deposit model to hold new user data and reset some inner properties
                    //print_r($data);
                    $trans = new database\AccountTransactionAccess(new database\SQLHandler($db->conn));
                    $model = new database\models\AccountTransaction($data);
                    $model->set_office($GLOBALS['office']);
                    $model->set_authorizedBy(json_encode(array("initial" => $GLOBALS['username'])));
                    $model->set_date(date("Y-m-d"));
                    $model->set_timestamp(date("Y-m-d H:i:s"));
                    $model->set_amount(abs($model->get_amount()));
                    $model->set_metaData('{}');
                    $model->set_status("pending");
                    $model->set_type("debit");

                        $savings = new database\SavingsAccountAccess(new database\SQLHandler($db->conn));
                        $account_data = $savings->select_single(null,array("account_no" => $model->get_accountNo()));
                        $savings->close();
                        if($account_data["balance"] < $model->get_amount()){
                            http_response_code(400);
                            echo '{"error":"Insufficient balance in the chosen savings account"}';
                            exit();
                        }
                        $trans->add($model); //add new trans deposit to data base
                        $trans->close();


        addLog( ["activity" => "create", "meta" => ["title" => "new savings account Withdrawal","account_no" => $model->get_accountNo(), "by" => $GLOBALS['username']]]);
                    echo '{"success":"The Withdrawal transaction was successful: proceed to Accountant for finalization"}';
            }
            catch(Exception $e){
                http_response_code(400);
                //die($e);
                echo '{"error":"Some inportant fields might be missing in the form"}';
                exit();
            }
    }
}
 else{
    //OAuth is not valid exit controller
    echo json_encode($GLOBALS["response"]);
    exit();
}