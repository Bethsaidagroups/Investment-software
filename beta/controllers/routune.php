<?php
/**
 * The customer controller class
 * @extends:abstract\controller
 */
namespace controllers;

 use includes\base\Controller;
 use includes\database\models\Option;
 use includes\database\models\Customer as CustomerModel;
 use includes\database\models\SavingsAccount;
 use includes\database\models\Login;
 use includes\database\models\UserType;
 use includes\utilities\SmsService;
 use includes\auth\{Session,Permission};

 class InvestriteImport extends Controller{
     
    private $db;

    public function __construct(){
        parent::__construct();
        $this->db = $GLOBALS['db'];
    }

    public function execute(){
        //This routine script will check to coorrect errors made in loan manager
    }
 }