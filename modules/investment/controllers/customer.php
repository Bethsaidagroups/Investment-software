<?php

/**
 * The API controller for Customer related operations performed by Investment staff.
 * The Controller performs the following actions
 * 
 * @action  Add User, Edit User, Delete User, View Users
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){
    
    //create new database connection object
    $db = new DatabaseConnection();

    if($action == "add"){
        //Add new user to the system
            $customer = new database\CustomerAccess(new database\SQLHandler($db->conn));
            //check if username already exist in the database
            try{
                $account_no = utilities\Activity::nextAccountNumber();
                $clause = array("account_no" => array("account" => $account_no), "bio_data" => array("bio" => json_encode($data->bio_data)));
                $result = $customer->selectWithClause(null, $clause, "OR", null);
                $customer->close();
                if(empty($result)){
                    //create customer model to hold new user data and reset some inner properties
                    //print_r($data);
                    $customer = new database\CustomerAccess(new database\SQLHandler($db->conn));
                    $model = new database\models\Customer($data);
                    $model->set_accountNo($account_no);
                    $model->set_office($GLOBALS['office']);
                    $model->set_registeredBy($GLOBALS['username']);
                    $model->set_date(date("Y-m-d"));
                    $model->set_registrationDate(date("Y-m-d H:i:s"));

                    //create the model for savings account
                    $savings = new database\SavingsAccountAccess(new database\SQLHandler($db->conn));
                    $data_save = array("id" => "", 
                                        "account_no" => $account_no, 
                                        "plan" => $data->plan, 
                                        "balance" => "0.00",
                                        "office" => $GLOBALS['office'],
                                        "status" => "active"
                                    );
                    $model_save = new database\models\SavingsAccount($data_save);
                    $customer->add($model); //add new customer to data base
                    $customer->close();
                    $savings->add($model_save); //create savings account
                    $savings->close();

        addLog( ["activity" => "create", "meta" => ["title" => "new customer added","account_no" => $account_no, "by" => $GLOBALS['username']]]);
                    echo '{"success":"Customer account created successfully with Account No: '.$account_no.'"}';
                }
                else{
                    http_response_code(400);
                    echo '{"error":"Possible account duplication. Hint: (1).Check if Account has been created already (2).Try submiting the form again"}';
                    exit();
                }
            }
            catch(Exception $e){
                http_response_code(400);
                //die($e);
                echo '{"error":"Some inportant fields might be missing in the form"}';
                exit();
            }
    }
    elseif($action == "view"){
        //view customer details: Single | Multiple
        if(isset($id)){
            //customer id is set want to view only one customer
            $customer = new database\CustomerAccess(new database\SQLHandler($db->conn), $id);
            $result = $customer->select_single(null);
            $customer->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        //customer id is not set check for next condition
        elseif(isset($key) && isset($value)){
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $cmd = array("order_by" =>"registration_date", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$list_size");
            $search = array($key => $value);
            $customer = new database\CustomerAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $result = $customer->select_multiple($filters,$search,$cmd);
            $customer->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        else{
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $cmd = array("order_by" =>"registration_date", "order_in"=>"DESC", "limit_start"=>"$start", "limit_stop"=>"$list_size");
            $customer = new database\CustomerAccess(new database\SQLHandler($db->conn));
            $filters = null;
            $clause = array("office"=>$GLOBALS["office"]);
            $result = $customer->select_multiple($filters,$clause,$cmd);
            $customer->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
    }
    elseif($action == "upload"){
        //Upload profile image
        try {
			$dir = ROOT_URL ."/contents/images/customers/";
			$final_name = $dir .$account_no. ".jpg";
			move_uploaded_file($_FILES["file"]["tmp_name"], $final_name);
		}
		catch(Exception $e){
            http_response_code(400);
            echo '{"error":"Unabale to upload image"}';
            exit();
		}
        
    }
    elseif($action == "edit"){
        //Edit a selected user
        $customer = new database\CustomerAccess(new database\SQLHandler($db->conn), $id);
        $customer->update( new database\models\Customer($data));
        $customer->close();

        addLog( ["activity" => "update", "meta" => ["title" => "user updated customer","customer_id" => $id]]);
    }
    elseif($action == "delete"){
        //delete a seleted user
        //$customer = new database\CustomerAccess(new database\SQLHandler($db->conn), $id);
        //$customer->delete();
        //$customer->close();

        //addLog( ["activity" => "delete", "meta" => ["title" => "user deleted customer","customer_id" => $id]]);
    }
}
 else{
    //OAuth is not valid exit controller
    echo json_encode($GLOBALS["response"]);
    exit();
}