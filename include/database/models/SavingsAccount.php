<?php
//The Savings Account database object model

namespace database\models;

class SavingsAccount{
    private $id;

    private $account_no;

    private $plan;

    private $balance;

    private $office;

    private $status;

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
            $this->account_no = $this->serialize_payloads($obj_payloads->account_no);
            $this->plan = $this->serialize_payloads($obj_payloads->plan);
            $this->balance = $this->serialize_payloads($obj_payloads->balance);
            $this->office = $this->serialize_payloads($obj_payloads->office);
            $this->status = $this->serialize_payloads($obj_payloads->status);
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
    public function set_accountNo($account_no){
        $this->account_no = $account_no;
    }
    public function set_plan($plan){
        $this->plan = $plan;
    }
    public function set_balance($balance){
        $this->balance = $balance;
    }
    public function set_office($office){
        $this->office = $office;
    }
    public function set_status($status){
        $this->status = $status;
    }

    //Set Getters method
    public function get_Id(){
        return $this->id;
    }
    public function get_accountNo(){
        return $this->account_no;
    }
    public function get_plan(){
        return $this->plan;
    }
    public function get_balance(){
        return $this->balance;
    }
    public function get_office(){
        return $this->office;
    }
    public function get_status(){
        return $this->status;
    }
}
