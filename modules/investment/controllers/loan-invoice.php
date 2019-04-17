<?php

/**
 * The API controller for Loan invoice related operations performed by Investment staff.
 * The Controller performs the following actions
 * 
 * @action  generate loan invoice, update loan invoice
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){
    
    //create new database connection object
    $db = new DatabaseConnection();

    if($action == 'get'){
        //generate invoice and send it to the user for final review
        //get loan from the database with the id
            $loan = new database\LoanApplicationAccess(new database\SQLHandler($db->conn), $id);
            $result = $loan->select_single(null);
            $loan->close();
        if($result['status'] != 'eligible'){
            http_response_code(400);
            echo '{"error":"Inappropriate loan status, can not generate loan invoice"}';
            exit();
        }
        else{
            if($data->plan == "week"){
                $days = 7;
            }
            else if($data->plan == "month"){
                $days = 30;
            }
            //check if date is a valid date
            $timestamp = date_timestamp_get(date_create(date("Y-m-d H:i:s")));
            $pay_amount = $result['amount_approved'] / abs($data->plan_no);
            $invoice = array();
            for($i=1; $i<=abs($data->plan_no); $i++){
                $invoice[$i]['loan_ref'] = $result['ref_no'];
                $invoice[$i]['account_no'] = $result['account_no'];
                $invoice[$i]['amount'] = abs($pay_amount);
                $invoice[$i]['rate'] = $data->rate;
                $invoice[$i]['default_charge'] = 0;
                $invoice[$i]['total_amount'] = abs($pay_amount) + (($data->rate/100) * abs($pay_amount));
                $invoice[$i]['status'] = 'unpaid';
                $date_offset = $days * $i;
                $invoice[$i]['due_date'] = date('Y-m-d H:i:s', strtotime("+$date_offset days", $timestamp));
                $invoice[$i]['amount_paid'] = 0;
                $invoice[$i]['roll_over'] = 0;
                $invoice[$i]['office'] = $result['office'];
            }
        }
        //Output Loan invoice
        echo json_encode($invoice);
        exit();
    }
    elseif($action == 'add'){
        //save invoice in the database
        $array_data = json_decode(json_encode($data->invoice), true);
        //print_r($array_data);
        //check if loan was ever created
        $loan = new database\LoanApplicationAccess(new database\SQLHandler($db->conn), $data->id);
        $loan_result = $loan->select_single();
        if(empty($loan_result)){
            //loan was never created
            http_response_code(400);
            echo '{"error":"Cant save invoice for loan application that does not exist"}';
            exit();
        }
        $loan->close();
        //check if loan invoice was has been saved once
        $loan_invoice = new database\LoanInvoiceAccess(new database\SQLHandler($db->conn), $data->id);
        $result = $loan_invoice->select_single(null, array("loan_ref" => $loan_result['ref_no']));
        if(!empty($result)){
            //loan invoice has been generated before
            http_response_code(400);
            echo '{"error":"Invoice Dupplication: Invoices with this reference number already exist, cant generate for this loan again"}';
            exit();
        }

        //from here all set save invoices in the database
        for($i=1; $i<=count($array_data); $i++){
            $model = new database\models\LoanInvoice($array_data[$i]);
            $loan_invoice->add($model);
        }
        $loan_invoice->close();
        //upadate loan to approved status
        $loan = new database\LoanApplicationAccess(new database\SQLHandler($db->conn), $data->id);
        $loan->column_update(array("status" => "approved"));
        //Create an account tranaction for loan payout
        $trans = new database\AccountTransactionAccess(new database\SQLHandler($db->conn));
        $model = new database\models\AccountTransaction();
        $model->set_office($GLOBALS['office']);
        $model->set_accountNo($loan_result['account_no']);
        $model->set_channel($data->channel);
        $model->set_metaData(json_encode(array("loan_ref" => $loan_result['ref_no'])));
        $model->set_authorizedBy(json_encode(array("initial" => $GLOBALS['username'])));
        $model->set_date(date("Y-m-d"));
        $model->set_timestamp(date("Y-m-d H:i:s"));
        $model->set_amount(abs($loan_result['amount_approved']));
        $model->set_status("pending");
        $model->set_type("credit");
        $model->set_category("Loan Payout");

        $trans->add($model); //add new trans deposit to data base
        $trans->close();

        addLog( ["activity" => "creat", "meta" => ["title" => "New loan invoice was created","loan_ref" => $loan_result['ref_no'], "by" => $GLOBALS['username']]]);
        echo '{"success":"You have successfully saved the invoice for this loan and Accountant has been notified for loan payout. Note: Loan started running immediately after invoice was published"}';
        exit();
    }
    elseif($action == 'view'){
        //view loan details: Single | Multiple
        if(isset($id)){
            //loan id is set want to view only one fixed deposit
            $loan = new database\LoanInvoiceAccess(new database\SQLHandler($db->conn), $id);
            $result = $loan->select_single(null);
            $loan->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        //fixed deposit id is not set check for next condition
        elseif(isset($key) && isset($value)){
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $stop = ($list_size * abs($page));
            $cmd = array("order_by" =>"id", "order_in"=>"ASC", "limit_start"=>"$start", "limit_stop"=>"$stop");
            $search = array($key => $value);
            $loan = new database\LoanInvoiceAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $result = $loan->select_multiple($filters,$search,$cmd);
            $loan->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        else{
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $stop = ($list_size * abs($page));
            $cmd = array("order_by" =>"id", "order_in"=>"ASC", "limit_start"=>"$start", "limit_stop"=>"$stop");
            $loan = new database\LoanInvoiceAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $clause = array("office"=>$GLOBALS["office"]);
            $result = $loan->select_multiple($filters,$clause,$cmd);
            $loan->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
    }
    elseif($action == "edit"){
        //Edit a selected loan
        $loan = new database\LoanInvoiceAccess(new database\SQLHandler($db->conn), $id);
        $result = $loan->select_single();
        $status = $result['status'];
        if(strcasecmp($status, 'unpaid') === 0 || strcasecmp($status, 'overdue') === 0){
            $model = new database\models\LoanInvoice($data); 
            if(empty($data->default_charge)){
                $data->default_charge = 0;
            }
            $model->set_defaultCharge(abs($data->default_charge));

            $loan_roll = new database\LoanInvoiceAccess(new database\SQLHandler($db->conn));
            $roll_result = $loan_roll->select_multiple(null,array("loan_ref" => $result["loan_ref"]));
            $loan_roll->close();
            //check if the invoice is the last
            $count = count($roll_result);
            if($id != $roll_result[$count-1]["id"]){
                //The invoice is not the last
                $total_pay = $result["total_amount"] + abs($data->default_charge) - $result["amount_paid"];
                //check if amount can clear invoice
                if($total_pay > abs($data->amount)){
                    $model->set_status($status);
                }
                else{
                    $model->set_status('paid');
                }
                //set amount paid
                $paid = $result["amount_paid"] + abs($data->amount);
                $model->set_amountPaid($paid);
                //check if there is roll over
                if($paid > $result["total_amount"] + abs($data->default_charge)){
                    $model->set_rollOver($paid - $result["total_amount"] + abs($data->default_charge));
                }
                else{
                    $model->set_rollOver(0);
                }
            }
            else{
                //the invoice is the last
                //get total rollover
                $total_rollover = 0;
                for($i=0; $i<$count; $i++){
                    $total_rollover += $roll_result[$i]["roll_over"];
                }
                $total_pay = $result["total_amount"] + abs($data->default_charge) - $result["amount_paid"] - $total_rollover;
                //check if amount can clear invoice
                if($total_pay > abs($data->amount)){
                    $model->set_status($status);
                }
                else{
                    $model->set_status('paid');
                }
                //set amount paid
                $paid = $result["amount_paid"] + abs($data->amount);
                $model->set_amountPaid($paid);
                //check if there is roll over
                if($paid + $total_rollover > $result["total_amount"] + abs($data->default_charge)){
                    $model->set_rollOver($paid + $total_rollover - $result["total_amount"] + abs($data->default_charge));
                    //customer overpaid loan add excesses to customers savings account excesses
                    $savings = new database\SavingsAccountAccess(new database\SQLHandler($db->conn));
                        $account_data = $savings->select_single(null,array("account_no" => $result["account_no"]));
                        $savings->close();

                        $savings = new database\SavingsAccountAccess(new database\SQLHandler($db->conn),  $account_data["id"]);
                        $savings->column_update(array("balance" => $account_data["balance"] + $model->get_rollOver()));
                        $roll = $model->get_rollOver();
                        $savings->close();
                        //update Account transaction monitor
                        $trans = new database\AccountTransactionAccess(new database\SQLHandler($db->conn));
                        $trans_model = new database\models\AccountTransaction();
                        $trans_model->set_office($GLOBALS['office']);
                        $trans_model->set_accountNo($result['account_no']);
                        $trans_model->set_channel('direct');
                        $trans_model->set_metaData(json_encode(array("loan_ref" => $result['loan_ref'], "id" => $result['id'])));
                        $trans_model->set_authorizedBy(json_encode(array("initial" => $GLOBALS['username'])));
                        $trans_model->set_date(date("Y-m-d"));
                        $trans_model->set_timestamp(date("Y-m-d H:i:s"));
                        $trans_model->set_amount($roll);
                        $trans_model->set_status("completed");
                        $trans_model->set_type("credit");
                        $trans_model->set_category("Loan Excess");

                        $trans->add($trans_model); //add new trans deposit to data base
                        $trans->close();
                }
                else{
                    $model->set_rollOver(0);
                }
            }
            $loan->update($model);
            $loan->close(); //close handle

            //update Account transaction monitor
            $trans = new database\AccountTransactionAccess(new database\SQLHandler($db->conn));
            $model = new database\models\AccountTransaction();
            $model->set_office($GLOBALS['office']);
            $model->set_accountNo($result['account_no']);
            $model->set_channel($data->channel);
            $model->set_metaData(json_encode(array("loan_ref" => $result['loan_ref'], "id" => $result['id'])));
            $model->set_authorizedBy(json_encode(array("initial" => $GLOBALS['username'])));
            $model->set_date(date("Y-m-d"));
            $model->set_timestamp(date("Y-m-d H:i:s"));
            $model->set_amount(abs($data->amount));
            $model->set_status("completed");
            $model->set_type("debit");
            $model->set_category("Invoice Payment");

            $trans->add($model); //add new trans deposit to data base
            $trans->close();

            addLog( ["activity" => "transaction", "meta" => ["title" => "loan invoice was paid","loan_ref" => $result['loan_ref'], "by" => $GLOBALS['username']]]);
            echo '{"success":"Loan invoice payment was successfull"}';
            exit();

        }
        else{
            http_response_code(400);
            echo '{"error":"This loan invoice has been paid completely"}';
            exit();
        }
    }
}
else{
   //OAuth is not valid exit controller
   echo json_encode($GLOBALS["response"]);
   exit();
}