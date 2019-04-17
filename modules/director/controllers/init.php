<?php
/**
 * The API controller for to initialize user activity menu for the Director.
 * The Controller performs the following actions
 * 
 * @action  gets initialization variables from the server
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){

  //create new database connection object
  $db = new DatabaseConnection();

    $menu = [1 => [
                    "key" =>  "manage-customer",
                    "icon" => "fa fa-user",
                    "text" => "Customers"
                  ],

              2 => [
                    "key" =>  "pending-trans",
                    "icon" => "fa fa-chain",
                    "text" => "Pending Transaction"
                  ],
              3 => [
                    "key" =>  "declined-trans",
                    "icon" => "fa fa-times-rectangle-o ",
                    "text" => "Declined Transaction"
                  ],
              4 => [
                    "key" =>  "debit-trans",
                    "icon" => "fa fa-arrow-circle-left",
                    "text" => "Debit Transaction"
                  ],
              5 => [
                    "key" =>  "credit-trans",
                    "icon" => "fa fa-arrow-circle-right",
                    "text" => "Credit Transaction"
                  ],
              6 =>  [
                    "key" =>  "manage-savings",
                    "icon" => "fa fa-list-alt",
                    "text" => "Manage Savings Accounts"
                  ],
              7 => [
                    "key" =>  "manage-fixed",
                    "icon" => "fa fa-money",
                    "text" => "Manage Fixed Deposits"
                  ],
             8 => [
                    "key" =>  "loans",
                    "icon" => "fa fa-list-alt",
                    "text" => "Loan Applications"
                  ],
              9 =>  [
                    "key" =>  "manage-invoice",
                    "icon" => "fa fa-address-card",
                    "text" => "Manage Loan Invoice"
                   ],
             10 => [
                    "key" =>  "quick-access",
                    "icon" => "fa fa-paper-plane",
                    "text" => "Quick Access"
                  ],
             11 => [
                    "key" =>  "trans-report",
                    "icon" => "fa fa-line-chart",
                    "text" => "Transaction Report"
             ],
             12 => [
              "key" =>  "add-user",
              "icon" => "fa fa-user-plus",
              "text" => "Add User"
            ],
          13 => [
              "key" =>  "manage-user",
              "icon" => "fa fa-users",
              "text" => "Manage Users"
            ],
       14 => [
              "key" =>  "add-office",
              "icon" => "fa fa-briefcase",
              "text" => "Add Office Location"
            ],
       15 => [
              "key" =>  "manage-office",
              "icon" => "fa fa-institution",
              "text" => "Manage Office Location"
            ],
       16 => [
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