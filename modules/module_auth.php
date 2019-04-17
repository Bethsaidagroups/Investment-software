<?php
    //module  Authorization scripts to only allow administrator to access this module

    $user_id = utilities\Session::get("user_id");
    //create new database connection object
    $db = new DatabaseConnection();
    //create Login object instance

    $login = new database\LoginAccess(new database\SQLHandler($db->conn), $user_id);
    $user_type = $login->select_single("username,user_type,office");
    //create users type object
    $type = new database\UsersTypeAccess(new database\SQLHandler($db->conn), $user_type["user_type"]);
    $type_name = $type->select_single("name");

    //compare name and see if they are the same
    if(http_response_code() === 200){
        if(strcasecmp($type_name["name"], MODULE) === 0){
            //Current user has access to this page
            http_response_code(200);
            $GLOBALS["response"]->access = 'Access granted';
            $GLOBALS["response"]->username = $user_type["username"];
            $GLOBALS["response"]->office = $user_type["office"];
        }
        else{
            //Current user has access to this page
            http_response_code(403);
            $GLOBALS["response"]->access = 'You dont have the permission to access this page';
    
        }
    }