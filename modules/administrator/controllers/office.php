<?php

/**
 * The API controller for Office related operations performed by administrator.
 * The Controller performs the following actions
 * 
 * @action  Add Office, Edit Office, Delete Office, View Offices
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){
    //create new database connection object
    $db = new DatabaseConnection();
    if($action == "add"){
        //Add new Office to the system
            $office = new database\OfficeAccess(new database\SQLHandler($db->conn));
            if(!empty($data->location) && !empty($data->type) && !empty($data->description)){
                    //create office model to hold new office data
                    $model = new database\models\Office($data);
                    //now add it to office relation
                    $office->add($model);
                    $office->close();

                    addLog( ["activity" => "create", "meta" => ["title" => "new office addition","location" => $data->location]]);
                    echo '{"success":"Office created successfully"}';
            }
            else{
                http_response_code(400);
                echo '{"error":"Provide data to all the fields"}';
                exit();
            }
    }
    elseif($action == "view"){
        //view users details: Single | Multiple
        if(isset($id)){
            //user id is set want to view only one user
            $office = new database\OfficeAccess(new database\SQLHandler($db->conn), $id);
            $result = $office->select_single();
            $office->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        //user id is not set check for next condition
        elseif(isset($key) && isset($value)){
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $stop = ($list_size * abs($page));
            $cmd = array("order_by" =>"id", "order_in"=>"ASC", "limit_start"=>"$start", "limit_stop"=>"$stop");
            $search = array($key => $value);
            $office = new database\OfficeAccess(new database\SQLHandler($db->conn));
            $result = $office->select_multiple(null,$search,$cmd);
            $office->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
        else{
            $list_size = 15;
            $start = ($list_size * abs($page)) - $list_size;
            $stop = ($list_size * abs($page));
            $cmd = array("order_by" =>"id", "order_in"=>"ASC", "limit_start"=>"$start", "limit_stop"=>"$stop");
            $office = new database\OfficeAccess(new database\SQLHandler($db->conn));
            $result = $office->select_multiple(null,null,$cmd);
            $office->close();
            echo utilities\JSONHandler::arrayToJSON($result);
        }
    }
    elseif($action == "edit"){
        //Edit a selected user
        $office = new database\OfficeAccess(new database\SQLHandler($db->conn), $id);
        $office->update( new database\models\Office($data));
        $office->close();
        addLog( ["activity" => "update", "meta" => ["title" => "office updated","id" => $id]]);
    }
    elseif($action == "delete"){
        //delete a seleted user
        $office = new database\OfficeAccess(new database\SQLHandler($db->conn), $id);
        $office->delete();
        $office->close();
        addLog( ["activity" => "delete", "meta" => ["title" => "office deleted","id" => $id]]);
    }
}
else{
    //OAuth is not valid exit controller
    echo json_encode($GLOBALS["response"]);
    exit();
}