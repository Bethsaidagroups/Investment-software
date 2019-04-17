<?php
    //The session database object model
    namespace database\models;

    class ActivityLog{

        //Atrributes of the Session manager Relation
        private $id;

        private $activity;

        private $username;

        private $meta;

        private $user_id;

        private $date;

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
            $this->activity = $this->serialize_payloads($obj_payloads->activity);
            $this->meta = $this->serialize_payloads($obj_payloads->meta);
            $this->username = $this->serialize_payloads($obj_payloads->username);
            $this->user_id = $this->serialize_payloads($obj_payloads->user_id);
            $this->date = $this->serialize_payloads(@$obj_payloads->date);
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
        elseif(is_object($payload_set) || is_array($payload_set)){
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
        public function set_activity($activity){
            $this->activity = $activity;
        }
        public function set_meta($user_meta){
            $this->meta = $user_meta;
        }
        public function set_username($username){
            $this->username= $username;
        }
        public function set_userId($user_id){
            $this->user_id = $user_id;
        }
        public function set_date($date){
            $this->date = $date;
        }
        public function set_timestamp($timestamp){
            $this->date = $date;
        }

        public function get_id(){
            return $this->id;
        }
        public function get_activity(){
            return $this->activity;
        }
        public function get_meta(){
            return $this->meta;
        }
        public function get_username(){
            return $this->username;
        }
        public function get_userId(){
            return $this->user_id;
        }
        public function get_date(){
            return $this->date;
        }
        public function get_timestamp(){
            return $this->timestamp;
        }
    }
?>