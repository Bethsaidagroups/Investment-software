<?php
//The Customer database object model

namespace database\models;

class Customer{
    private $id;

    private $account_no;

    private $category;

    private $office;

    private $bio_data;

    private $id_data;

    private $crp_mode;

    private $employment_data;

    private $kin_data;

    private $registered_by;
    
    private $marketer;

    private $date;

    private $registration_date;

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
            $this->category = $this->serialize_payloads($obj_payloads->category);
            $this->office = $this->serialize_payloads($obj_payloads->office);
            $this->bio_data = $this->serialize_payloads($obj_payloads->bio_data);
            $this->id_data = $this->serialize_payloads($obj_payloads->id_data);
            $this->crp_mode = $this->serialize_payloads($obj_payloads->crp_mode);
            $this->employment_data = $this->serialize_payloads($obj_payloads->employment_data);
            $this->kin_data = $this->serialize_payloads($obj_payloads->kin_data);
            $this->registered_by = $this->serialize_payloads($obj_payloads->registered_by);
            $this->marketer = $this->serialize_payloads($obj_payloads->marketer);
            $this->date = $this->serialize_payloads(@$obj_payloads->date);
            $this->registration_date = $this->serialize_payloads($obj_payloads->registration_date);
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
    public function set_category($category){
        $this->category = $category;
    }
    public function set_office($office){
        $this->office = $office;
    }
    public function set_bioData($bio_data){
        $this->bio_data = $bio_data;
    }
    public function set_idData($id_data){
        $this->id_data = $id_data;
    }
    public function set_crpMode($crp_mode){
        $this->crp_mode = $crp_mode;
    }
    public function set_employmentData($employment_data){
        $this->employment_data = $employment_data;
    }
    public function set_kinData($kin_data){
        $this->kin_data = $kin_data;
    }
    public function set_registeredBy($registered_by){
        $this->registered_by = $registered_by;
    }
    public function set_marketer($marketer){
        $this->marketer = $marketer;
    }
    public function set_date($date){
        $this->date = $date;
    }
    public function set_registrationDate($registration_date){
        $this->registration_date = $registration_date;
    }
    //Set Getters method
    public function get_Id(){
        return $this->id;
    }
    public function get_accountNo(){
        return $this->account_no;
    }
    public function get_category(){
        return $this->category;
    }
    public function get_office(){
        return $this->office;
    }
    public function get_bioData(){
        return $this->bio_data;
    }
    public function get_idData(){
        return $this->id_data;
    }
    public function get_crpMode(){
        return $this->crp_mode;
    }
    public function get_employmentData(){
        return $this->employment_data;
    }
    public function get_kinData(){
        return $this->kin_data;
    }
    public function get_registeredBy(){
        return $this->registered_by;
    }
    public function get_marketer(){
        return $this->marketer;
    }
    public function get_date(){
        return $this->date;
    }
    public function get_registrationDate(){
        return $this->registration_date;
    }
}
