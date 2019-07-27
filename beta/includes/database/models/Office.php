<?php
//The Office database object model

namespace includes\database\models;

class Office{
    private $id;

    private $location;

    private $type;

    private $description;

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
            $this->location = $this->serialize_payloads($obj_payloads->location);
            $this->type = $this->serialize_payloads($obj_payloads->type);
            $this->description = $this->serialize_payloads($obj_payloads->description);
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
    public function set_Id($id){
        $this->id = $id;
    }
    public function set_location($location){
        $this->location = $location;
    }
    public function set_type($type){
        $this->type = $type;
    }
    public function set_description($description){
        $this->description = $description;
    }

    //Set Getters method
    public function get_Id(){
        return $this->id;
    }
    public function get_location(){
        return $this->location;
    }
    public function get_type(){
        return $this->type;
    }
    public function get_description(){
        return $this->description;
    }
}

