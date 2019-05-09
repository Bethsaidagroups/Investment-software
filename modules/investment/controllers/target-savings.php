<?php

/**
 * The API controller for Target Savings related operations performed by Investment staff.
 * The Controller performs the following actions
 * 
 * @action  Add Target Savings, Edit Target Savings, Delete Target Savings, View Target Savings
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){
    
    //create new database connection object
    $db = new DatabaseConnection();

    if($action == "add"){
            $target = new database\TargetSavingsAccess(new database\SQLHandler($db->conn));
            try{
                    //create Target Savings model to hold new user data and reset some inner properties
                    //print_r($data);
                    $target = new database\TargetSavingsAccess(new database\SQLHandler($db->conn));
                    $model = new database\models\TargetSavings($data);
                    $model->set_office($GLOBALS['office']);
                    $model->set_registeredBy($GLOBALS['username']);
                    $model->set_date(date("Y-m-d"));
                    $model->set_timestamp(date("Y-m-d H:i:s"));
                    $model->set_targetAmount(abs($model->get_targetAmount()));
                    $amount = $model->get_targetAmount();
                    $acct_no = $model->get_accountNo();
                    $stamp = date_timestamp_get(date_create(date("Y-m-d H:i:s")));
                    $ref = utilities\CryptoLib::randomFigure(3) . $stamp;
                    $model->set_refNo($ref);
                    //check if the start date is equal to the current date by checking if the date input string is valid
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
                        $roi =  ($model->get_targetAmount() * $model->get_rate() * $data->date_no)/(365 * 100);
                        $model->set_roi($roi);
                    }
                    else{
                        http_response_code(400);
                        echo '{"error":"One or more of your date input is not valid. check them and try again"}';
                        exit();
                    }
                    $target->add($model); //add new Target Savings to data base
                    $target->close();

                    //Add new target savings to the savings invoice
                    if($data->date_no < 90){
                        //date is not in multiple of 30 days
                        http_response_code(400);
                        echo '{"error":"Duration can not be less than 90 days"}';
                        exit();
                    }
                    $counter =  floor($data->date_no/30);
                    $date_left = $data->date_no%30;
                    $invoice = array();
                    for($i=1; $i<=$counter; $i++){
                        $invoice[$i]['account_no'] = $data->account_no;
                        $invoice[$i]['ref_no'] = $ref;
                        $invoice[$i]['amount'] = abs($data->target_amount)/$counter;
                        $invoice[$i]['channel'] = 'none';
                        $invoice[$i]['rate'] = $data->rate;
                        $invoice[$i]['roi'] = ((abs($data->target_amount) * (abs($data->rate)/100) * abs($data->date_no))/365)/$counter;
                        $invoice[$i]['status'] = 'unpaid';
                        if($i == $counter){
                            $date_offset = (30 * $i) + $date_left;
                        }
                        else{
                            $date_offset = 30 * $i;
                        }
                        $invoice[$i]['due_date'] = date('Y-m-d', strtotime($data->start_date . " + $date_offset days"));
                        $invoice[$i]['office'] = $GLOBALS['office'];
                    }
                    $target_invoice = new database\SavingsInvoiceAccess(new database\SQLHandler($db->conn));
                    //from here all set save invoices in the database
                    for($i=1; $i<=$counter; $i++){
                        $model = new database\models\SavingsInvoice($invoice[$i]);
                        $target_invoice->add($model);
                    }
                    $target_invoice->close();

                    addLog( ["activity" => "create", "meta" => ["title" => "new target savings added","account_no" => $model->get_accountNo(), "by" => $GLOBALS['username']]]);
                    echo '{"success":"The Target savings has been saved successfully"}';
            }
            catch(Exception $e){
                http_response_code(400);
                die($e);
                echo '{"error":"Some inportant fields might be missing in the form"}';
                exit();
            }
    }
    elseif($action == "preview"){
        //generate invoice and send it to the user for final review
        if($data->date_no < 90){
            //date is not in multiple of 30 days
            http_response_code(400);
            echo '{"error":"Duration can not be less than 90 days"}';
            exit();
        }
        $counter =  floor($data->date_no/30);
        $date_left = $data->date_no%30;
        $invoice = array();
        for($i=1; $i<=$counter; $i++){
            $invoice[$i]['account_no'] = $data->account_no;
            $invoice[$i]['amount'] = abs($data->target_amount)/$counter;
            $invoice[$i]['rate'] = $data->rate;
            $invoice[$i]['roi'] = ((abs($data->target_amount) * (abs($data->rate)/100) * abs($data->date_no))/365)/$counter;
            $invoice[$i]['status'] = 'unpaid';
            if($i == $counter){
                $date_offset = (30 * $i) + $date_left;
            }
            else{
                $date_offset = 30 * $i;
            }
            $invoice[$i]['due_date'] = date('Y-m-d', strtotime($data->start_date . " + $date_offset days"));
            $invoice[$i]['office'] = $GLOBALS['office'];
        }
    //Output Loan invoice
    echo json_encode($invoice);
    exit();
    }
    elseif($action == "view"){
        //view Target Savings details: Single | Multiple
        if(isset($id)){
            //Target Savings id is set want to view only one Target Savings
            $target = new database\TargetSavingsAccess(new database\SQLHandler($db->conn), $id);
            $result = $target->select_single(null);
            $target->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        //Target Savings id is not set check for next condition
        elseif(isset($key) && isset($value)){
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $stop = ($list_size * abs($page));
            $cmd = array("order_by" =>"timestamp", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$stop");
            $search = array($key => $value);
            $target = new database\TargetSavingsAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $result = $target->select_multiple($filters,$search,$cmd);
            $target->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        else{
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $stop = ($list_size * abs($page));
            $cmd = array("order_by" =>"timestamp", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$stop");
            $target = new database\TargetSavingsAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $clause = array("office"=>$GLOBALS["office"]);
            $result = $target->select_multiple($filters,$clause,$cmd);
            $target->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        
    }
    elseif($action == "edit"){
        //Edit a selected Target Savings
        $target = new database\TargetSavingsAccess(new database\SQLHandler($db->conn), $id);
        $target->update( new database\models\FixedDeposit($data));
        $target->close();

        addLog( ["activity" => "update", "meta" => ["title" => "A Target Savings details was updated","account_no" => $data->account_no]]);
    }
    elseif($action == "cashout"){
        //Cash out selected Target Savings
        $target = new database\TargetSavingsAccess(new database\SQLHandler($db->conn), $id);
        $result = $target->select_single();
        if(strcasecmp($result['status'], 'cashed') === 0){
            http_response_code(400);
            echo '{"error":"Target Savings has been withdrawn already"}';
            exit();
        }
        else{
            //get all roii from invoices
            $all_invoices = new database\SavingsInvoiceAccess(new database\SQLHandler($db->conn));
            $invoice_result = $all_invoices->selectWithClause(null, array('ref_no' => $result['ref_no']));
            $single_roi = $result['roi']/count($invoice_result);
            $total_roi = 0;
            for($i=0; $i<count($invoice_result); $i++){
                if($invoice_result[$i]['status'] == 'paid'){
                    $total_roi += $single_roi;
                }
            }
            $total = $result['paid_amount'] + $total_roi - abs($data->penalty);
        }
        $target->column_update(array("status" => "cashed"));
        $target->close();

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
        $model->set_category("Target Savings");

        $trans->add($model); //add new transaction
        $trans->close();

        addLog( ["activity" => "cash out", "meta" => ["title" => "A Target Savings has been withdrawn","id" => $id]]);
    }
}
 else{
    //OAuth is not valid exit controller
    echo json_encode($GLOBALS["response"]);
    exit();
}