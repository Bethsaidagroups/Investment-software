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

    public function init_import(){
        //check if this is comming from investrite server
        if (DEBUG){
            $header = getallheaders();
            $token = explode(' ', $header['Authorization'])[1];
            if(strcmp($token, GATEWAY_HANDSHAKE_KEY) !== 0){
                //Authorization failed
                $this->response->addMessage('Gateway Handshake not successfull');
                $this->response->jsonResponse(null,403);
            }
        }
        else{
            $token = explode(' ', $_SERVER['REDIRECT_HTTP_AUTHORIZATION'])[1];
            if(strcmp($token, GATEWAY_HANDSHAKE_KEY) !== 0){
                //Authorization failed
                $this->response->addMessage('Gateway Handshake not successfull');
                $this->response->jsonResponse(null,403);
            }
        }

        $account_no = $this->request->getUrlParams()->account;
        //get savings account details and initialize 
        $data = $this->db->get(CustomerModel::DB_TABLE,[
            CustomerModel::ACCOUNT_NO,
            CustomerModel::CATEGORY,
            CustomerModel::BIO_DATA,
            CustomerModel::ID_DATA,
            CustomerModel::CRP_MODE,
            CustomerModel::EMPLOYMENT_DATA,
            CustomerModel::KIN_DATA,
            CustomerModel::MARKETER,
        ],[
            CustomerModel::ACCOUNT_NO=>$account_no
        ]);

        if(empty($data)){
            $this->response->addMessage('This customer does not exist');
            $this->response->jsonResponse(null,404);
        }
        else{
            $this->response->addMessage('Customer Account: '.$this->request->getUrlParams()->account);
            $this->response->jsonResponse($this->response->NormalizeMysqlArray($data));
        }
    }
 }