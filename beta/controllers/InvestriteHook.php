<?php
/**
 * The customer controller class
 * @extends:abstract\controller
 */
namespace controllers;

 use includes\base\Controller;
 use includes\database\models\Customer as CustomerModel;
 use includes\database\models\SavingsAccount as SavingsAccountModel;
 use includes\database\models\{AccountTransaction};

 class InvestriteHook extends Controller{
     
    private $db;

    public function __construct(){
        parent::__construct();
        $this->db = $GLOBALS['db'];
    }

    public function account_history(){
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
        $data = $this->db->select(AccountTransaction::DB_TABLE,[
            AccountTransaction::ID,
            AccountTransaction::ACCOUNT_NO,
            AccountTransaction::CATEGORY,
            AccountTransaction::TYPE,
            AccountTransaction::AMOUNT,
            AccountTransaction::CHANNEL,
            AccountTransaction::AUTHORIZED_BY,
            AccountTransaction::STATUS,
            AccountTransaction::META,
            AccountTransaction::DATETIME,
        ],[
            AccountTransaction::ACCOUNT_NO=>$account_no,
            'ORDER'=>[AccountTransaction::DATETIME=>"DESC"] 
        ]);

        if(empty($data)){
            $this->response->addMessage('No record to display');
            $this->response->jsonResponse(null,404);
        }
        else{
            $customer = $this->get_customer($account_no);
            $customer = json_decode(@$customer['bio_data']);
            $this->response->addMeta([
                'balance'=>$this->get_account_balance($account_no),
                'bio_data'=>@$customer,
            ]);
            $this->response->jsonResponse($this->response->NormalizeMysqlArray($data));
        }
    }

    public function get_account_balance($account_no){
        return $this->db->get(SavingsAccountModel::DB_TABLE,SavingsAccountModel::BALANCE,[
            SavingsAccountModel::ACCOUNT_NO=>$account_no
        ]);
    }

    public function get_customer($account_no){
        return $this->db->get(CustomerModel::DB_TABLE,[
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
    }
 }