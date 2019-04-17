<?php
/**
 * The API controller for to initialize user activity menu for the Investment module.
 * The Controller performs the following actions
 * 
 * @action  gets initialization variables from the server
 */
//Check if http response is 200 to decide continuity
if(http_response_code() === 200){

  //create new database connection object
  $db = new DatabaseConnection();

    $menu = [1 => [
                    "key" =>  "add-customer",
                    "icon" => "fa fa-user-plus",
                    "text" => "Add Customer"
                  ],
             2 => [
                    "key" =>  "manage-customer",
                    "icon" => "fa fa-users",
                    "text" => "Manage Customers"
                  ],
             3 => [
                    "key" =>  "manage-savings",
                    "icon" => "fa fa-list-alt",
                    "text" => "Manage Savings Accounts"
                  ],
             4 => [
                    "key" =>  "add-fixed",
                    "icon" => "fa fa-plus",
                    "text" => "Add Fixed Deposit"
                  ],
             5 => [
                    "key" =>  "manage-fixed",
                    "icon" => "fa fa-money",
                    "text" => "Manage Fixed Deposits"
                  ],
             6 => [
                    "key" =>  "add-target",
                    "icon" => "fa fa-calendar-plus-o",
                    "text" => "Add Target Savings"
                  ],
             7 => [
                    "key" =>  "manage-target",
                    "icon" => "fa fa-calendar-check-o",
                    "text" => "Manage Target Savings"
                  ],
             8 => [
                    "key" =>  "make-deposit",
                    "icon" => "fa fa-plus-circle",
                    "text" => "Make Deposit"
                  ],
             9 => [
                    "key" =>  "make-withdrawal",
                    "icon" => "fa fa-minus-circle",
                    "text" => "Make Withdrawal"
                  ],
             10 => [
                    "key" =>  "awaiting-trans",
                    "icon" => "fa fa-chain",
                    "text" => "Awaiting Transactions"
                  ],
             11 => [
                    "key" =>  "add-loan",
                    "icon" => "fa fa-plus-square",
                    "text" => "Add Loan Application"
                    ],
             12 => [
                    "key" =>  "manage-loan",
                    "icon" => "fa fa-building",
                    "text" => "Manage Loan Application"
                  ],
             13 => [
                    "key" =>  "manage-invoice",
                    "icon" => "fa fa-address-card",
                    "text" => "Manage Loan Invoice"
                   ],
             14 => [
                    "key" =>  "acct-trans",
                    "icon" => "fa fa-history",
                    "text" => "Account Transactions History"
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