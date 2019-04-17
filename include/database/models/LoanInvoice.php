<?php
//The Loan Invoice database object model

namespace database\models;

class LoanInvoice{
    private $id;

    private $loan_ref;

    private $account_no;

    private $amount;

    private $rate;

    private $default_charge;

    private $total_amount;

    private $status;

    private $due_date;

    private $amount_paid;

    private $roll_over;

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
            $this->loan_ref = $this->serialize_payloads(@$obj_payloads->loan_ref);
            $this->account_no = $this->serialize_payloads(@$obj_payloads->account_no);
            $this->amount = $this->serialize_payloads(@$obj_payloads->amount);
            $this->rate = $this->serialize_payloads(@$obj_payloads->rate);
            $this->default_charge = $this->serialize_payloads(@$obj_payloads->default_charge);
            $this->total_amount = $this->serialize_payloads(@$obj_payloads->total_amount);
            $this->status = $this->serialize_payloads(@$obj_payloads->status);
            $this->due_date = $this->serialize_payloads(@$obj_payloads->due_date);
            $this->amount_paid = $this->serialize_payloads(@$obj_payloads->amount_paid);
            $this->roll_over = $this->serialize_payloads(@$obj_payloads->roll_over);
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
    public function set_loanRef($loan_ref){
        $this->loan_ref = $loan_ref;
    }
    public function set_accountNo($account_no){
        $this->account_no = $account_no;
    }
    public function set_amount($amount){
        $this->amount = $amount;
    }
    public function set_rate($rate){
        $this->rate = $rate;
    }
    public function set_defaultCharge($default_charge){
        $this->default_charge = $default_charge;
    }
    public function set_totalAmount($total_amount){
        $this->total_amount = $total_amount;
    }
    public function set_status($status){
        $this->status = $status;
    }
    public function set_dueDate($due_date){
        $this->due_date = $due_date;
    }
    public function set_amountPaid($amount_paid){
        $this->amount_paid = $amount_paid;
    }
    public function set_rollOver($roll_over){
        $this->roll_over = $roll_over;
    }
    public function set_office($office){
        $this->office = $office;
    }
    //Set Getters method
    public function get_Id(){
       return $this->id;
    }
    public function get_loanRef(){
       return $this->loan_ref;
    }
    public function get_accountNo(){
        return $this->account_no;
    }
    public function get_amount(){
        return $this->amount;
    }
    public function get_rate(){
        return $this->rate;
    }
    public function get_defaultCharge(){
        return $this->default_charge;
    }
    public function get_totalAmount(){
        return $this->total_amount;
    }
    public function get_status(){
        return $this->status;
    }
    public function get_dueDate(){
        return $this->due_date;
    }
    public function get_amountPaid(){
        return $this->amount_paid;
    }
    public function get_rollOver(){
        return $this->roll_over;
    }
    public function get_office(){
        return $this->office;
    }
}
