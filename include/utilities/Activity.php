<?php
/**
 * LogActvity is a static class that logs every activities in performed in the entire system
 * It contains static methods to log activities and to view them
 */
 namespace utilities;

 class Activity{
     
    private static $db = null;

    private static function init(){
        self::$db =  new \DatabaseConnection();
    }

    public static function log($data){
        self::init();
        $data["id"] = "";
        $data["user_id"] = \utilities\Session::get("user_id");
        $data["username"] = \utilities\Session::get("username");
        $data["timestamp"] = date("Y-m-d H:i:s");
        $model = new \database\models\ActivityLog($data);
        $activity_log = new \database\ActivityLogAccess(new \database\SQLHandler(self::$db->conn));
        $actvity_log->add($model);
        $actvity_log->close();
    }

    public static function nextAccountNumber(){
        self::init();
        $option = new \database\OptionAccess(new \database\SQLHandler(self::$db->conn));
        $result = $option->select_single(null,array("name" => "pointer")); //get next account number
        $option->close();
        $option = new \database\OptionAccess(new \database\SQLHandler(self::$db->conn), $result['id']);
        $option->column_update(array("value" => $result['value'] + 1));
        $option->close();
        return $result['value'];
    }
 }