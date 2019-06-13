<?php

/**
 * The API controller for Savings invoice related operations performed by Investment staff.
 * The Controller performs the following actions
 * 
 * @action  generate loan invoice, update loan invoice
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){
    
    //create new database connection object
    $db = new DatabaseConnection();

    if($action == 'get'){
        
    }
    elseif($action == 'add'){
        
    }
    elseif($action == 'view'){
        //view loan details: Single | Multiple
        if(isset($id)){
            //loan id is set want to view only one fixed deposit
            $savings = new database\SavingsInvoiceAccess(new database\SQLHandler($db->conn), $id);
            $result = $savings->select_single(null);
            $savings->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        //Target Savings id is not set check for next condition
        elseif(isset($key) && isset($value)){
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $cmd = array("order_by" =>"id", "order_in"=>"ASC", "limit_start"=>"$start", "limit_stop"=>"$list_size");
            $search = array($key => $value);
            $savings = new database\SavingsInvoiceAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $result = $savings->select_multiple($filters,$search,$cmd);
            $savings->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        else{
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $cmd = array("order_by" =>"id", "order_in"=>"ASC", "limit_start"=>"$start", "limit_stop"=>"$list_size");
            $savings = new database\SavingsInvoiceAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $clause = array("office"=>$GLOBALS["office"]);
            $result = $savings->select_multiple($filters,$clause,$cmd);
            $savings->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
    }
    elseif($action == "edit"){
        //Edit a selected savings
        $savings = new database\SavingsInvoiceAccess(new database\SQLHandler($db->conn), $id);
        $result = $savings->select_single();
        $status = $result['status'];
        if((strcasecmp($status, 'unpaid') === 0) && (strcasecmp($data->status, 'paid') === 0)){

            $model = new database\models\SavingsInvoice($data); 
            //check the channel chosen and take action as required
            if($data->channel == 'savings'){
                //channel is savings account withdraw from savings account
                $savings_inst = new database\SavingsAccountAccess(new database\SQLHandler($db->conn));
                $account_data = $savings_inst->select_single(null,array("account_no" => $result['account_no']));
                $savings_inst->close();
                
                if($account_data["balance"] < $result['amount']){
                    http_response_code(400);
                    echo '{"error":"Insufficient balance in the target savings account"}';
                    exit();
                }
                else{
                    $savings_inst = new database\SavingsAccountAccess(new database\SQLHandler($db->conn),  $account_data["id"]);
                    $savings_inst->column_update(array("balance" => $account_data["balance"] - $result['amount']));
                    $savings_inst->close();

                     //Notify users via SMS
                     $balance = number_format($account_data["balance"] - $result['amount'], 2);
                     $amt = number_format($result['amount'], 2);
                     $msg = "Debit>>>Amt:NGN $amt Acc:" . $result['account_no'] . " Desc:" . 'direct,target-savings' . ">" . $result['id']. " Date:" . $result['timestamp'] . " PMBal:NGN $balance";
                     //Get users mobile number.
                     //echo $msg;
                     $customer = new database\CustomerAccess(new database\SQLHandler($db->conn));
                     $bio_data = $customer->select_single('bio_data',array("account_no" => $result['account_no']));
                     $bio_meta = json_decode($bio_data['bio_data']);
                     if(!empty($bio_meta->mobile1)){
                         $sms = new utilities\SmsService($bio_meta->mobile1, $msg);
                         $sms->send(); //send sms here
                     }

                }
            }

            $savings->update($model);
            $savings->close(); //close handle

            //update Account transaction monitor
            $trans = new database\AccountTransactionAccess(new database\SQLHandler($db->conn));
            $model = new database\models\AccountTransaction();
            $model->set_office($GLOBALS['office']);
            $model->set_accountNo($result['account_no']);
            $model->set_channel($data->channel);
            $model->set_metaData(json_encode(array("ref_no" => $result['ref_no'], "id" => $result['id'])));
            $model->set_authorizedBy(json_encode(array("initial" => $GLOBALS['username'])));
            $model->set_date(date("Y-m-d"));
            $model->set_timestamp(date("Y-m-d H:i:s"));
            $model->set_amount(abs($data->amount));
            $model->set_status("completed");
            $model->set_type("debit");
            $model->set_category("Savings Invoice Payment");

            $trans->add($model); //add new trans deposit to data base
            $trans->close();

            $all_invoices = new database\SavingsInvoiceAccess(new database\SQLHandler($db->conn));
            $invoice_result = $all_invoices->selectWithClause(null, array('ref_no' => $result['ref_no']));
            $count = count($invoice_result);

            $target = new database\TargetSavingsAccess(new database\SQLHandler($db->conn));
            $targ_result = $target->selectWithClause(null, array('ref_no' => $result['ref_no']));
            $target = new database\TargetSavingsAccess(new database\SQLHandler($db->conn), $targ_result[0]['id']);
            $paid_amount = $targ_result[0]['paid_amount'] + $result['amount'];
            $target->column_update(array('paid_amount' => $paid_amount));
            if($invoice_result[$count-1]['id'] == $result['id']){
                //last invoice for target savings paid
                $target->column_update(array('status' => 'completed'));
                $target->close();
            }

            addLog( ["activity" => "transaction", "meta" => ["title" => "Savings invoice was paid","ref_no" => $result['ref_no'], "by" => $GLOBALS['username']]]);
            echo '{"success":"Savings invoice payment was successfull"}';
            exit();

        }
        else{
            http_response_code(400);
            echo '{"error":"This Savings invoice has been paid completely or you are changing to an impossible status"}';
            exit();
        }
    }
}
else{
   //OAuth is not valid exit controller
   echo json_encode($GLOBALS["response"]);
   exit();
}