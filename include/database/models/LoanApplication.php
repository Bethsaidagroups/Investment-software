<?php
//The Loan Application database object model

namespace database\models;

class LoanApplication{
    private $id;

    private $ref_no;

    private $account_no;

    //private $personal;

    //private $residential;

    //private $relative;

    //private $work;

    private $loan;

    //private $guarantor;

    private $amount_approved;

    private $authorized_by;

    private $registered_by;

    private $status;

    private $office;

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
            $this->id = $this->serialize_payloads(@$obj_payloads->id);
            $this->ref_no = $this->serialize_payloads(@$obj_payloads->ref_no);
            $this->account_no = $this->serialize_payloads(@$obj_payloads->account_no);
            //$this->personal = $this->serialize_payloads($obj_payloads->personal);
            //$this->residential = $this->serialize_payloads($obj_payloads->residential);
            //$this->relative = $this->serialize_payloads($obj_payloads->relative);
            //$this->work = $this->serialize_payloads($obj_payloads->work);
            $this->loan = $this->serialize_payloads(@$obj_payloads->loan);
            //$this->guarantor = $this->serialize_payloads($obj_payloads->guarantor);
            $this->amount_approved = $this->serialize_payloads(@$obj_payloads->amount_approved);
            $this->authorized_by = $this->serialize_payloads(@$obj_payloads->authorized_by);
            $this->registered_by = $this->serialize_payloads(@$obj_payloads->registered_by);
            $this->status = $this->serialize_payloads(@$obj_payloads->status);
            $this->office = $this->serialize_payloads(@$obj_payloads->office);
            $this->date = $this->serialize_payloads(@$obj_payloads->date);
            $this->timestamp = $this->serialize_payloads(@$obj_payloads->timestamp);
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
    public function set_personal($personal){
        //$this->personal = $personal;
    }
    public function set_residential($residential){
        //$this->residential = $residential;
    }
    public function set_relative($relative){
        //$this->relative = $relative;
    }
    public function set_work($work){
        //$this->work = $work;
    }
    public function set_loan($loan){
        $this->loan = $loan;
    }
    public function set_guarantor($guarantor){
        //$this->guarantor = $guarantor;
    }
    public function set_amountApproved($amount_approved){
        $this->amount_approved = $amount_approved;
    }
    public function set_authorizedBy($authorized_by){
        $this->authorized_by = $authorized_by;
    }
    public function set_registeredBy($registered_by){
        $this->registered_by = $registered_by;
    }
    public function set_status($status){
        $this->status = $status;
    }
    public function set_office($office){
        $this->office = $office;
    }
    public function set_date($date){
        $this->date = $date;
    }
    public function set_timestamp($timestamp){
        $this->timestamp = $timestamp;
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
    public function get_personal(){
        ///return $this->personal;
    }
    public function get_residential(){
        //return $this->residential;
    }
    public function get_relative(){
        //return $this->relative;
    }
    public function get_work(){
        //return $this->work;
    }
    public function get_loan(){
        return $this->loan;
    }
    public function get_guarantor(){
       // return $this->guarantor;
    }
    public function get_amountApproved(){
        return $this->amount_approved;
    }
    public function get_authorizedBy(){
        return $this->authorized_by;
    }
    public function get_registeredBy(){
        return $this->registered_by;
    }
    public function get_status(){
        return $this->status;
    }
    public function get_office(){
        return $this->office;
    }
    public function get_date(){
        return $this->date;
    }
    public function get_timestamp(){
        return $this->timestamp;
    }
}
