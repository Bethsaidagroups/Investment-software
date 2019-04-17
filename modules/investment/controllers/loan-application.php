<?php

/**
 * The API controller for Loan application related operations performed by Investment staff.
 * The Controller performs the following actions
 * 
 * @action  Add loan, Edit loan, Delete loan, View loan
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){
    
    //create new database connection object
    $db = new DatabaseConnection();

    if($action == "add"){
            try{
                    //create fixed deposit model to hold new user data and reset some inner properties
                    //print_r($data);
                    $loan = new database\LoanApplicationAccess(new database\SQLHandler($db->conn));
                    $model = new database\models\LoanApplication($data);
                    $model->set_office($GLOBALS['office']);
                    $model->set_registeredBy($GLOBALS['username']);
                    $model->set_authorizedBy(json_encode(array("initial" => "none","final" => "none")));
                    $model->set_date(date("Y-m-d"));
                    $model->set_timestamp(date("Y-m-d H:i:s"));
                    $model->set_amountApproved(0);
                    $model->set_status('pending');
                    $stamp = date_timestamp_get(date_create(date("Y-m-d H:i:s")));
                    $ref = utilities\CryptoLib::randomFigure(5) . $stamp;
                    $model->set_refNo($ref);

                    $loan->add($model); //add new fixed deposit to data base
                    $loan->close();

        addLog( ["activity" => "create", "meta" => ["title" => "new loan application added","ref_no" => $ref, "by" => $GLOBALS['username']]]);
                    echo '{"success":"Loan application has been submited for review"}';
            }
            catch(Exception $e){
                http_response_code(400);
                die($e);
                echo '{"error":"Some inportant fields might be missing in the form"}';
                exit();
            }
    }
    elseif($action == "view"){
        //view loan details: Single | Multiple
        if(isset($id)){
            //loan id is set want to view only one fixed deposit
            $loan = new database\LoanApplicationAccess(new database\SQLHandler($db->conn), $id);
            $result = $loan->select_single(null);
            $loan->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        //fixed deposit id is not set check for next condition
        elseif(isset($key) && isset($value)){
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $stop = ($list_size * abs($page));
            $cmd = array("order_by" =>"timestamp", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$stop");
            $search = array($key => $value);
            $loan = new database\LoanApplicationAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $result = $loan->select_multiple($filters,$search,$cmd);
            $loan->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        else{
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $stop = ($list_size * abs($page));
            $cmd = array("order_by" =>"timestamp", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$stop");
            $loan = new database\LoanApplicationAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $clause = array("office"=>$GLOBALS["office"]);
            $result = $loan->select_multiple($filters,$clause,$cmd);
            $loan->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        
    }
    elseif($action == "edit"){
        //Edit a selected loan
        if($data->status == 'pending'){
            $loan = new database\LoanApplicationAccess(new database\SQLHandler($db->conn), $id);
            $loan->update(new database\models\LoanApplication($data));
            $loan->close();
            echo '{"success":"Loan application updated successfully"}';
            exit();
        }
        else{
            http_response_code(400);
            echo '{"error":"You do not have the permission to change/edit loan parameter at this point"}';
            exit();
        }
        addLog( ["activity" => "update", "meta" => ["title" => "Loan application was updated","ref_no" => $data->ref_no]]);
    }
    elseif($action == "delete"){
       //delete a seleted user
       $loan = new database\LoanApplicationAccess(new database\SQLHandler($db->conn), $id);
       $result = $loan->select_single();
       if($result['status'] == 'approved'){
        http_response_code(400);
        echo '{"error":"You do not have the permission to delete an approved loan"}';
        exit();
       }
       else{
        $loan->delete();
        $loan->close();
       }
       addLog( ["activity" => "delete", "meta" => ["title" => "Loan application deleted","ref_no" => $result['ref_no']]]);
    }
}
 else{
    //OAuth is not valid exit controller
    echo json_encode($GLOBALS["response"]);
    exit();
}