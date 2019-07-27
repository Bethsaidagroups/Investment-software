<?php
/**
 * The permission class is a simple class that checks through
 * the permission array of a function/modules and decides if the user
 * has the right permission to view/take action
 */

 namespace includes\auth;
 use includes\auth\Session;
 use includes\database\models\UserType;

 class Permission{
     const DEFAULT = '*';

     const ADMIN = ['Administrator'];

     const CENTRAL = ['Managing Director','Accountant'];

     const MANAGER = ['Investment'];

     const SECRETARY = ['Secretary'];

     const LASER_DIM = ['Secretary','Investment'];

     public static function has_permission($user_type, $permits = Permission::DEFAULT){
        if($permits === '*'){
           return true;
        }
        foreach($permits as $key => $value){
           if(strcasecmp(UserType::$types[$user_type],$value) === 0){
              return true;
           }
        }
        //no permission match
        return false;
     }
 }