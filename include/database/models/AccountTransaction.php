<?php
//The Account Transaction database object model

namespace database\models;

class AccountTransaction{
    private $id;

    private $date;

    private $account_no;

    private $category;

    private $type;

    private $amount;

    private $channel;

    private $authorized_by;

    private $status;

    private $office;

    private $meta_data;

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
            $this->date = $this->serialize_payloads($obj_payloads->date);
            $this->account_no = $this->serialize_payloads($obj_payloads->account_no);
            $this->category = $this->serialize_payloads($obj_payloads->category);
            $this->type = $this->serialize_payloads($obj_payloads->type);
            $this->amount = $this->serialize_payloads($obj_payloads->amount);
            $this->channel = $this->serialize_payloads($obj_payloads->channel);
            $this->authorized_by = $this->serialize_payloads($obj_payloads->authorized_by);
            $this->status = $this->serialize_payloads($obj_payloads->status);
            $this->office = $this->serialize_payloads($obj_payloads->office);
            $this->meta_data = $this->serialize_payloads(@$obj_payloads->meta_data);
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
    public function set_Id($id){
        $this->id = $id;
    }
    public function set_date($date){
        $this->date = $date;
    }
    public function set_accountNo($account_no){
        $this->account_no = $account_no;
    }
    public function set_category($category){
        $this->category = $category;
    }
    public function set_type($type){
        $this->type = $type;
    }
    public function set_amount($amount){
        $this->amount = $amount;
    }
    public function set_channel($channel){
        $this->channel = $channel;
    }
    public function set_authorizedBy($authorized_by){
        $this->authorized_by = $authorized_by;
    }
    public function set_status($status){
        $this->status = $status;
    }
    public function set_office($office){
        $this->office = $office;
    }
    public function set_metaData($meta_data){
        $this->meta_data = $meta_data;
    }
    public function set_timestamp($timestamp){
        $this->timestamp = $timestamp;
    }
    //Set Getters method
    public function get_Id(){
        return $this->id;
    }
    public function get_date(){
        return $this->date;
    }
    public function get_accountNo(){
        return $this->account_no;
    }
    public function get_category(){
        return $this->category;
    }
    public function get_type(){
        return $this->type;
    }
    public function get_amount(){
        return $this->amount;
    }
    public function get_channel(){
        return $this->channel;
    }
    public function get_authorizedBy(){
        return $this->authorized_by;
    }
    public function get_status(){
        return $this->status;
    }
    public function get_office(){
        return $this->office;
    }
    public function get_metaData(){
        return $this->meta_data;
    }
    public function get_timestamp(){
        return $this->timestamp;
    }
}
