<?php
    //The session database object model
    namespace database\models;

    class Session{

        //Atrributes of the Session manager Relation
        private $id;

        private $token;

        private $last_access;

        private $user_agent;

        private $ip_address;

        private $user_id;

        private $timestamp;

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
            $this->token = $this->serialize_payloads($obj_payloads->token);
            $this->last_access = $this->serialize_payloads($obj_payloads->last_access);
            $this->user_agent = $this->serialize_payloads($obj_payloads->user_agent);
            $this->ip_address = $this->serialize_payloads($obj_payloads->ip_address);
            $this->user_id = $this->serialize_payloads($obj_payloads->user_id);
            $this->timestamp = $this->serialize_payloads($obj_payloads->timestamp);
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
        public function set_token($token){
            $this->token = $token;
        }
        public function set_lastAccess($last_access){
            $this->last_access = $last_access;
        }
        public function set_userAgent($user_agent){
            $this->user_agent = $user_agent;
        }
        public function set_ipAddress($ip_address){
            $this->ip_address = $ip_address;
        }
        public function set_userId($user_id){
            $this->user_id = $user_id;
        }
        public function set_timestamp($timestamp){
            $this->timestamp = $timestamp;
        }

        public function get_id(){
            return $this->id;
        }
        public function get_token(){
            return $this->token;
        }
        public function get_lastAccess(){
            return $this->last_access;
        }
        public function get_userAgent(){
            return $this->user_agent;
        }
        public function get_ipAddress(){
            return $this->ip_address;
        }
        public function get_userId(){
            return $this->user_id;
        }
        public function get_timestamp(){
            return $this->timestamp;
        }
    }
?>