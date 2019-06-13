<?php

/**
 * The API controller for Fixed Deposit related operations performed by Investment staff.
 * The Controller performs the following actions
 * 
 * @action  Add fixed deposit, Edit Fixed deposit, Delete Fixed deposit, View Fixed deposit
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){
    
    //create new database connection object
    $db = new DatabaseConnection();

    if($action == "add"){
        //Add new user to the system
            $fixed = new database\FixedDepositAccess(new database\SQLHandler($db->conn));
            //check if username already exist in the database
            try{
                    //create fixed deposit model to hold new user data and reset some inner properties
                    //print_r($data);
                    $fixed = new database\FixedDepositAccess(new database\SQLHandler($db->conn));
                    $model = new database\models\FixedDeposit($data);
                    $model->set_office($GLOBALS['office']);
                    $model->set_registeredBy($GLOBALS['username']);
                    $model->set_date(date("Y-m-d"));
                    $model->set_timestamp(date("Y-m-d H:i:s"));
                    $model->set_amount(abs($model->get_amount()));
                    $amount = $model->get_amount();
                    $acct_no = $model->get_accountNo();
                    //check the channel chosen and take action as required
                    if($model->get_channel() == 'savings'){
                        //channel is savings account withdraw from savings account
                        $savings = new database\SavingsAccountAccess(new database\SQLHandler($db->conn));
                        $account_data = $savings->select_single(null,array("account_no" => $model->get_accountNo()));
                        $savings->close();
                        if($account_data["balance"] < $model->get_amount()){
                            http_response_code(400);
                            echo '{"error":"Insufficient balance in the target savings account"}';
                            exit();
                        }
                        else{
                            $savings = new database\SavingsAccountAccess(new database\SQLHandler($db->conn),  $account_data["id"]);
                            $savings->column_update(array("balance" => $account_data["balance"] - $model->get_amount()));
                            $savings->close();
                        }
                    }
                    //check if the start date is equal to the current date by checking if tthe date input string is valid
                    $dt = DateTime::createFromFormat("Y-m-d", $model->get_startDate());
                    $date_start = $dt !== false && !array_sum($dt::getLastErrors());
                    if($date_start){
                        //continue date string valid
                        $datetime1 = new DateTime($model->get_startDate());
                        $datetime2 = new DateTime(date("Y-m-d"));
                        if($datetime1 > $datetime2){
                            $model->set_status("pending");
                        }
                        else{
                            $model->set_status("active");
                        }

                        //get the end date
                        $model->set_endDate(date('Y-m-d', strtotime($model->get_startDate() . " + $data->date_no days")));
                        if($data->date_no < 365){
                            //date is less than a year
                            $roi =  ($model->get_amount() * $model->get_rate() * $data->date_no)/(365 * 100);
                            $model->set_roi($roi);
                        }
                        else{
                            $roi =  ($model->get_amount() * $model->get_rate())/(100);
                            $model->set_roi($roi);
                        }
                    }
                    else{
                        http_response_code(400);
                        echo '{"error":"One or more of your date input is not valid. check them and try again"}';
                        exit();
                    }
                    $fixed->add($model); //add new fixed deposit to data base
                    $fixed->close();
                    //update Account transaction monitor
                    $trans = new database\AccountTransactionAccess(new database\SQLHandler($db->conn));
                    $model = new database\models\AccountTransaction();
                    $model->set_office($GLOBALS['office']);
                    $model->set_accountNo($acct_no);
                    $model->set_channel($data->channel);
                    $model->set_metaData('{}');
                    $model->set_authorizedBy(json_encode(array("initial" => $GLOBALS['username'])));
                    $model->set_date(date("Y-m-d"));
                    $model->set_timestamp(date("Y-m-d H:i:s"));
                    $model->set_amount($amount);
                    $model->set_status("completed");
                    $model->set_type("debit");
                    $model->set_category("Fixed Deposit");

                    $trans->add($model); //add new transaction
                    $trans->close();

                    addLog( ["activity" => "create", "meta" => ["title" => "new fixed deposit added","account_no" => $model->get_accountNo(), "by" => $GLOBALS['username']]]);
                    echo '{"success":"The deposit has been fixed successfully will start running on it specified start date"}';
            }
            catch(Exception $e){
                http_response_code(400);
                die($e);
                echo '{"error":"Some inportant fields might be missing in the form"}';
                exit();
            }
    }
    elseif($action == "view"){
        //view fixed deposit details: Single | Multiple
        if(isset($id)){
            //fixed deposit id is set want to view only one fixed deposit
            $fixed = new database\FixedDepositAccess(new database\SQLHandler($db->conn), $id);
            $result = $fixed->select_single(null);
            $fixed->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        //fixed deposit id is not set check for next condition
        elseif(isset($key) && isset($value)){
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $cmd = array("order_by" =>"timestamp", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$list_size");
            $search = array($key => $value);
            $fixed = new database\FixedDepositAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $result = $fixed->select_multiple($filters,$search,$cmd);
            $fixed->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        else{
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $cmd = array("order_by" =>"timestamp", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$list_size");
            $fixed = new database\FixedDepositAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $clause = array("office"=>$GLOBALS["office"]);
            $result = $fixed->select_multiple($filters,$clause,$cmd);
            $fixed->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        
    }
    elseif($action == "edit"){
        //Edit a selected fixed deposit
        $fixed = new database\FixedDepositAccess(new database\SQLHandler($db->conn), $id);
        $fixed->update( new database\models\FixedDeposit($data));
        $fixed->close();

        addLog( ["activity" => "update", "meta" => ["title" => "A fixed Deposit details was updated","account_no" => $data->account_no]]);
    }
    elseif($action == "cashout"){
        //Cash out selected fixed deposit
        $fixed = new database\FixedDepositAccess(new database\SQLHandler($db->conn), $id);
        $result = $fixed->select_single();
        if(strcasecmp($result['status'], 'cashed') === 0){
            http_response_code(400);
            echo '{"error":"Fixed Deposit has been withdrawn already"}';
            exit();
        }
        elseif(strcasecmp($result['status'], 'completed') === 0){
            $tax = $result['roi'] * 10 / 100;
            $total = $result['amount'] + $result['roi'] - $tax;
        }
        else{
            $total = $result['amount'];
        }
        $fixed->column_update(array("status" => "cashed"));
        $fixed->close();

        //From here add to account transacttion with status pending and the channel
        //update Account transaction monitor
        $trans = new database\AccountTransactionAccess(new database\SQLHandler($db->conn));
        $model = new database\models\AccountTransaction();
        $model->set_office($GLOBALS['office']);
        $model->set_accountNo($result['account_no']);
        $model->set_channel($data->channel);
        $model->set_metaData(json_encode(array("deposit_id" => $id)));
        $model->set_authorizedBy(json_encode(array("initial" => $GLOBALS['username'])));
        $model->set_date(date("Y-m-d"));
        $model->set_timestamp(date("Y-m-d H:i:s"));
        $model->set_amount($total);
        $model->set_status("pending");
        $model->set_type("credit");
        $model->set_category("Fixed Deposit");

        $trans->add($model); //add new transaction
        $trans->close();

        addLog( ["activity" => "cash out", "meta" => ["title" => "A fixed Deposit has been withdrawn","id" => $id]]);
    }
}
 else{
    //OAuth is not valid exit controller
    echo json_encode($GLOBALS["response"]);
    exit();
}