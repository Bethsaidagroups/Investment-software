<?php
/**
 * The API controller for to initialize user activity menu for the adminiatrator.
 * The Controller performs the following actions
 * 
 * @action  gets initialization variables from the server
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){

  //create new database connection object
  $db = new DatabaseConnection();

    $menu = [1 => [
                    "key" =>  "add-user",
                    "icon" => "fa fa-user-plus",
                    "text" => "Add User"
                  ],
             2 => [
                    "key" =>  "manage-user",
                    "icon" => "fa fa-users",
                    "text" => "Manage Users"
                  ],
             3 => [
                    "key" =>  "add-office",
                    "icon" => "fa fa-briefcase",
                    "text" => "Add Office Location"
                  ],
             4 => [
                    "key" =>  "manage-office",
                    "icon" => "fa fa-institution",
                    "text" => "Manage Office Location"
                  ],
             5 => [
                    "key" =>  "activity-log",
                    "icon" => "fa fa-history",
                    "text" => "Activities Log"
                  ]
            ];

            $type_inst = new database\UsersTypeAccess(new database\SQLHandler($db->conn));
            $types = $type_inst->select_multiple();
            $type_inst->close();
            
            $office_inst = new database\OfficeAccess(new database\SQLHandler($db->conn));
            $offices = $office_inst->select_multiple();
            $office_inst->close();
    
            $res = array("types" => null, "offices" => null);
            for($i=0; $i<count($types); $i++){
                 $res["types"][$i]["id"] = $types[$i]["id"];
                 $res["types"][$i]["name"] = $types[$i]["name"];
            }
            for($i=0; $i<count($offices); $i++){
                 $res["offices"][$i]["id"] = $offices[$i]["id"];
                 $res["offices"][$i]["location"] = $offices[$i]["location"];
                 $res["offices"][$i]["type"] = $offices[$i]["type"];
                 $res["offices"][$i]["description"] = $offices[$i]["description"];
            }
        $init_data = array("menu" => $menu, "res" => $res);
  
        echo json_encode($init_data);
}
else{
    //OAuth is not valid exit controller
    echo json_encode($GLOBALS["response"]);
    exit();
}