<?php
//The Options database object model

namespace database\models;

class Option{
    private $id;

    private $name;

    private $value;

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
            $this->name = $this->serialize_payloads($obj_payloads->name);
            $this->value = $this->serialize_payloads($obj_payloads->value);
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
    public function set_name($name){
        $this->name = $name;
    }
    public function set_value($value){
        $this->value = $value;
    }

    //Set Getters method
    public function get_Id(){
        return $this->id;
    }
    public function get_name(){
        return $this->name;
    }
    public function get_value(){
        return $this->value;
    }
}
