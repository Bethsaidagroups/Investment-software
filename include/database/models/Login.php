<?php
//The Logins database object model

namespace database\models;

class Login{
    private $id;

    private $username;

    private $password;

    private $user_type;

    private $access;

    private $office;

    private $meta;

    private $last_login;

    //public constructor for loading the model
    public function __construct($payloads=null){

        if(is_null($payloads)){
            return;
        }
        elseif(is_array($payloads) || is_object($payloads)){
            //start assigning variables
            $obj_payloads = null;
            if(is_array($payloads)){
                $obj_payloads = (object)$payloads;
            }
            else{
                $obj_payloads = $payloads;
            }
            //start assigning variables
            $this->id = $this->serialize_payloads($obj_payloads->id);
            $this->username = $this->serialize_payloads($obj_payloads->username);
            $this->password = $this->serialize_payloads($obj_payloads->password);
            $this->user_type = $this->serialize_payloads($obj_payloads->user_type);
            $this->access = $this->serialize_payloads($obj_payloads->access);
            $this->office = $this->serialize_payloads($obj_payloads->office);
            $this->meta = $this->serialize_payloads($obj_payloads->meta);
            $this->last_login = $this->serialize_payloads($obj_payloads->last_login);
        }
        else{
            return;
        }
    }
    //Serialize Payloads
    private function serialize_payloads($payload_set){
        if(is_null($payload_set)){
            return null;
        }
        elseif(is_object($payload_set)  || is_array($payload_set)){
            return json_encode($payload_set);
        }
        else{
            return $payload_set;
        }
    }
    //Get all object model data in an array
    public function get_all(){
        return get_object_vars($this);
    }
    //Get all object model data without the id in an array
    public function get_all_exclude_id(){
        $data = get_object_vars($this);
        unset($data["id"]);
        return $data;
    }
    //set Setters Method
    public function set_id($id){
        $this->id = $id;
    }
    public function set_username($username){
        $this->username = $username;
    }
    public function set_password($password){
        $this->password = $password;
    }
    public function set_userType($user_type){
        $this->user_type = $user_type;
    }
    public function set_access($access){
        $this->access = $access;
    }
    public function set_office($office){
        $this->office = $office;
    }
    public function set_meta($meta){
        $this->meta = $meta;
    }
    public function set_lastLogin($last_login){
        $this->last_login = $last_login;
    }

    //Set Getters method
    public function get_id(){
        return $this->id;
    }
    public function get_username(){
        return $this->username;
    }
    public function get_password(){
        return $this->password;
    }
    public function get_userType(){
        return $this->user_type;
    }
    public function get_access(){
        return $this->access;
    }
    public function get_office(){
        return $this->office;
    }
    public function get_meta(){
        return $this->meta;
    }
    public function get_lastLogin(){
        return $this->last_login;
    }
}
