<?php
//The Target Savings Invoice Invoice database object model

namespace database\models;

class SavingsInvoice{
    private $id;

    private $ref_no;

    private $account_no;

    private $amount;

    private $channel;

    private $rate;

    private $status;

    private $due_date;

    private $office;

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
            $this->id = $this->serialize_payloads(@$obj_payloads->id);
            $this->ref_no = $this->serialize_payloads(@$obj_payloads->ref_no);
            $this->account_no = $this->serialize_payloads(@$obj_payloads->account_no);
            $this->amount = $this->serialize_payloads(@$obj_payloads->amount);
            $this->channel = $this->serialize_payloads(@$obj_payloads->channel);
            $this->rate = $this->serialize_payloads(@$obj_payloads->rate);
            $this->status = $this->serialize_payloads(@$obj_payloads->status);
            $this->due_date = $this->serialize_payloads(@$obj_payloads->due_date);
            $this->office = $this->serialize_payloads(@$obj_payloads->office);
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
    public function set_refNo($ref_no){
        $this->ref_no = $ref_no;
    }
    public function set_accountNo($account_no){
        $this->account_no = $account_no;
    }
    public function set_amount($amount){
        $this->amount = $amount;
    }
    public function set_channel($channel){
        $this->channel = $channel;
    }
    public function set_rate($rate){
        $this->rate = $rate;
    }
    public function set_status($status){
        $this->status = $status;
    }
    public function set_dueDate($due_date){
        $this->due_date = $due_date;
    }
    public function set_office($office){
        $this->office = $office;
    }
    //Set Getters method
    public function get_Id(){
       return $this->id;
    }
    public function get_refNo(){
       return $this->ref_no;
    }
    public function get_accountNo(){
        return $this->account_no;
    }
    public function get_amount(){
        return $this->amount;
    }
    public function get_channel(){
        return $this->channel;
    }
    public function get_rate(){
        return $this->rate;
    }
    public function get_status(){
        return $this->status;
    }
    public function get_dueDate(){
        return $this->due_date;
    }
    public function get_office(){
        return $this->office;
    }
}
