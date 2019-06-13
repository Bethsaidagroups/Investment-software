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

    if($action == "view"){
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
            $cmd = array("order_by" =>"timestamp", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$list_size");
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
            $cmd = array("order_by" =>"timestamp", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$list_size");
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
        if($data->status != 'approved'){
            $loan = new database\LoanApplicationAccess(new database\SQLHandler($db->conn), $id);
            $model = new database\models\LoanApplication($data);
            $authorizers = json_decode($model->get_authorizedBy(), true);
            $authorizers["final"] = $GLOBALS["username"];
            $model->set_authorizedBy(json_encode($authorizers));
            $loan->update($model);
            $loan->close();
            echo '{"success":"Loan application status updated successfully"}';
            exit();
        }
        else{
            http_response_code(400);
            echo '{"error":"You do not have the permission to change/edit loan parameter at this point"}';
            exit();
        }
        addLog( ["activity" => "update", "meta" => ["title" => "Loan application was updated","ref_no" => $data->ref_no]]);
    }
}
 else{
    //OAuth is not valid exit controller
    echo json_encode($GLOBALS["response"]);
    exit();
}