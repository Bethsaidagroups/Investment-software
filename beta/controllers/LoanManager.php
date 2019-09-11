<?php
/**
 * Loan Manager Controller
 * @extends:abstract\controller
 */

namespace controllers;

use includes\base\Controller;
use includes\auth\{Session,Permission};
use includes\database\models\Customer as CustomerModel;
use includes\database\models\LoanRecord;
use includes\database\models\{AccountTransaction, SavingsAccount as SavingsAccountModel};
use includes\BasicTransactions\SavingsAccount as SavingsAction;
use includes\utilities\{SmsService, EmailService};

class LoanManager extends Controller{

    private $db;

    public function __construct(){
        parent::__construct();
        $this->db = $GLOBALS['db'];
    }

    //get total savings account
    public function getTotal($query=null){
        //check if query was sent with the request
        if($query){
            $data_count = $this->db->db->count(LoanRecord::DB_TABLE,'*',[
                "OR"=>[
                    LoanRecord::DB_TABLE.'.'.LoanRecord::ID => $this->request->getUrlParams()->query,
                    LoanRecord::DB_TABLE.'.'.LoanRecord::ACCOUNT_NO => $this->request->getUrlParams()->query,
                    LoanRecord::DB_TABLE.'.'.LoanRecord::STATUS => $this->request->getUrlParams()->query,
                    LoanRecord::DB_TABLE.'.'.LoanRecord::AUTHORIZED_BY => $this->request->getUrlParams()->query,
                    LoanRecord::DB_TABLE.'.'.LoanRecord::DATE => $this->request->getUrlParams()->query
                ]
            ]);
            return $data_count;
        }
        else{
            $data_count = $this->db->db->count(LoanRecord::DB_TABLE,'*',[
                LoanRecord::DB_TABLE.'.'.LoanRecord::OFFICE=>Session::get('office'),
            ]);
            return $data_count;
        }
    }

    //public function to add new loan or overdraft
    public function addLoan(){
        $this->authenticate(Permission::MANAGER); //Authenticate with permission
        //get request payload from post
        $data = $this->request->getPost();
        $savings_action = new SavingsAction($this->db, $data->account_no);
        if(!$savings_action->isRegistered()){
            $this->response->addMessage('The target account does not exist, please check and try again');
            $this->response->jsonResponse(null,400);
        }
        if(!$savings_action->isActive()){
            $this->response->addMessage('This target account has been deactivated');
            $this->response->jsonResponse(null,400);
        }
        //Start loan issuance proceedure
        $available_balance = $savings_action->getBalance();
        //Check if loan advance for this customer is logical
        if($available_balance > $data->amount){
            $this->response->addMessage('Loan registration not logical. Advance requested is less than customers balance');
            $this->response->jsonResponse(null,400);
        }
        //check if user does not have an active advance, There are two possible ways of checking this:
        #check if user balance is negative or check if OR check if user has an unpaid loan record
        if($available_balance > 0){
            //customers does not have an unpaid loan, continue with grant
            #use atomic transaction
            $result = $this->db->atomic_transaction(function($db) use(&$data, &$available_balance, &$savings_action){
                //update account balance
                $db->update(SavingsAccountModel::DB_TABLE,[
                    SavingsAccountModel::BALANCE=>$available_balance - abs($data->amount)
                ],[
                    SavingsAccountModel::ACCOUNT_NO=>$data->account_no
                ]);
                //register loan in the database
                $db->insert(LoanRecord::DB_TABLE,[
                    LoanRecord::ACCOUNT_NO=>$data->account_no,
                    LoanRecord::AMOUNT=>abs($data->amount) - $available_balance,
                    LoanRecord::STATUS=>LoanRecord::$statuses['unpaid'],
                    LoanRecord::OFFICE=>Session::get('office'),
                    LoanRecord::AUTHORIZED_BY=>Session::get('username'),
                    LoanRecord::DATE=>LoanRecord::get_date()
                ]);
                //Register into accoun transaction table
                $meta = ["narration"=>$data->narration,"balance"=>$available_balance - abs($data->amount)];
                $savings_action->registerTransaction([
                    AccountTransaction::ACCOUNT_NO=>$data->account_no,
                    AccountTransaction::CATEGORY=>AccountTransaction::$categories['savings'],
                    AccountTransaction::TYPE=>AccountTransaction::$types['debit'],
                    AccountTransaction::AMOUNT=>abs($data->amount),
                    AccountTransaction::CHANNEL=>AccountTransaction::$channels[$data->channel],
                    AccountTransaction::AUTHORIZED_BY=>json_encode(['final'=>Session::get('username')]),
                    AccountTransaction::STATUS=>AccountTransaction::$statuses['completed'],
                    AccountTransaction::OFFICE=>Session::get('office'),
                    AccountTransaction::META=>json_encode($meta),
                    AccountTransaction::DATETIME=>date("Y-m-d H:i:s")
                ],$db);
                //check if transaction has been registered: Optional condition
                if(!$db->has(LoanRecord::DB_TABLE,[
                    LoanRecord::ACCOUNT_NO=>$data->account_no,
                    LoanRecord::STATUS=>LoanRecord::$statuses['unpaid'],
                ])){
                    //Rollback here:
                    //NOTE: Transaction might have been rolledback before now if an exception occured this action
                    //id handled by the database wrapper
                    return false;
                }
            }); 
            if($result === false){
                $this->response->addMessage('An error occured while trying to add this loan to record');
                $this->response->jsonResponse(null, 400);
            }
            else{
                //Notify users vial sms and email of a debit transaction
                $bio_data = json_decode($this->db->get(CustomerModel::DB_TABLE,CustomerModel::BIO_DATA,[
                    CustomerModel::ACCOUNT_NO=>$data->account_no
                ]));
                //send sms to user
                $alert_data = [
                    'amount'=>number_format(abs($data->amount),2),
                    'channel'=>$data->channel,
                    'account_number'=>$data->account_no,
                    'narration'=>$data->narration,
                    'datetime'=>date("Y-m-d H:i:s"),
                    'balance'=>number_format($savings_action->getBalance(),2),
                    'type'=>'debit',
                    'category'=>'Savings',
                    'status'=>'completed',
                    'name'=>$bio_data->surname." ".$bio_data->first_name,
                    'email'=> empty($bio_data->email)? null : $bio_data->email
                ];
                $sms = new SmsService($bio_data->mobile1);
                $sms->sendNewDebitMessage($alert_data);
                //send Email
                $mail = new EmailService((object)$alert_data);
                $mail->send();

                $this->response->addMessage("Loan has been registered successfully. Advance has been deducted from customers saving's account");
                $this->response->jsonResponse();
            }
        }
        else{
            $this->response->addMessage('The customer with this account number is currently running on negative balance, which indicates an unsettled loan');
            $this->response->jsonResponse(null, 400);
        }
    }

    /**
     * List loan records
     */
    public function getList(){
        $this->authenticate(Permission::MANAGER); //Authenticate with permission

        //check if query was sent with the request
        if(!empty($this->request->getUrlParams()->query)){
            $page = $this->request->getUrlParams()->page;
            $rows = 15;
            $offset = ($page * $rows) - $rows;
            $data = $this->db->select(LoanRecord::DB_TABLE,[
                "[>]offices"=>["office"=>"id"],
                "[>]".CustomerModel::DB_TABLE=>["account_no"=>"account_no"]
            ],[
                LoanRecord::DB_TABLE.'.'.LoanRecord::ID,
                LoanRecord::DB_TABLE.'.'.LoanRecord::ACCOUNT_NO,
                'offices.description',
                'offices.location',
                CustomerModel::DB_TABLE.'.'.CustomerModel::BIO_DATA,
                LoanRecord::DB_TABLE.'.'.LoanRecord::AMOUNT,
                LoanRecord::DB_TABLE.'.'.LoanRecord::STATUS,
                LoanRecord::DB_TABLE.'.'.LoanRecord::AUTHORIZED_BY,
                LoanRecord::DB_TABLE.'.'.LoanRecord::DATE,
            ],[
                "OR"=>[
                    LoanRecord::DB_TABLE.'.'.LoanRecord::ID => $this->request->getUrlParams()->query,
                    LoanRecord::DB_TABLE.'.'.LoanRecord::ACCOUNT_NO => $this->request->getUrlParams()->query,
                    LoanRecord::DB_TABLE.'.'.LoanRecord::STATUS => $this->request->getUrlParams()->query,
                    LoanRecord::DB_TABLE.'.'.LoanRecord::AUTHORIZED_BY => $this->request->getUrlParams()->query,
                    LoanRecord::DB_TABLE.'.'.LoanRecord::DATE => $this->request->getUrlParams()->query
                ],
                'ORDER'=>[LoanRecord::DB_TABLE.'.'.LoanRecord::DATE=>"DESC"],
                'LIMIT'=>[$offset,$rows]
            ]);
            if(empty($data)){
                $this->response->addMessage('There is no account/loan record associated with this keyword in our database');
                $this->response->jsonResponse(null,400);
            }
            else{
                $this->response->addMessage('Search Result for keyword: '.$this->request->getUrlParams()->query);
                $this->response->addMeta([
                    'total'=>$this->getTotal($this->request->getUrlParams()->query),
                    'list_total'=>($rows * ($page - 1)) + count($data)]);
                $this->response->jsonResponse($this->response->NormalizeMysqlArray($data));
            }
        }
        else{
            $page = $this->request->getUrlParams()->page;
            $rows = 15;
            $offset = ($page * $rows) - $rows;
            $data = $this->db->select(LoanRecord::DB_TABLE,[
                "[>]offices"=>["office"=>"id"],
                "[>]".CustomerModel::DB_TABLE=>["account_no"=>"account_no"]
            ],[
                LoanRecord::DB_TABLE.'.'.LoanRecord::ID,
                LoanRecord::DB_TABLE.'.'.LoanRecord::ACCOUNT_NO,
                'offices.description',
                'offices.location',
                CustomerModel::DB_TABLE.'.'.CustomerModel::BIO_DATA,
                LoanRecord::DB_TABLE.'.'.LoanRecord::AMOUNT,
                LoanRecord::DB_TABLE.'.'.LoanRecord::STATUS,
                LoanRecord::DB_TABLE.'.'.LoanRecord::AUTHORIZED_BY,
                LoanRecord::DB_TABLE.'.'.LoanRecord::DATE,
            ],[
                LoanRecord::DB_TABLE.'.'.LoanRecord::OFFICE => Session::get('office'),   
                'ORDER'=>[LoanRecord::DB_TABLE.'.'.LoanRecord::DATE=>"DESC"],
                'LIMIT'=>[$offset,$rows]
            ]);
            if(empty($data)){
                $this->response->addMessage('Loan manager record is empty, add some new loans and see them appear here');
                $this->response->jsonResponse(null,400);
            }
            else{
                $this->response->addMeta([
                'total'=>$this->getTotal(),
                'list_total'=>($rows * ($page - 1)) + count($data)]);
                $this->response->jsonResponse($this->response->NormalizeMysqlArray($data));
            }
        }
    }

    //get total records
    public function getCentralTotal($where){
        //check if query was sent with the request
        unset($where['LIMIT']);
        $data_count = $this->db->db->count(LoanRecord::DB_TABLE,'*',$where);
        return $data_count;
    }

    //get balance for the current query set [for central management unit alone]
    public function getCentralBalance($where){
        unset($where['LIMIT']);
        $balance = $this->db->db->sum(LoanRecord::DB_TABLE,LoanRecord::AMOUNT,$where);
        return $balance;
    }

    //used by central management
    public function getCentralList(){
        $this->authenticate(Permission::CENTRAL); //Authenticate with permission
        $page = $this->request->getUrlParams()->page;
        $rows = 15;
        $offset = ($page * $rows) - $rows;
        if(strcasecmp($this->request->getUrlParams()->office, 'all') === 0){
            if(!empty($this->request->getUrlParams()->query)){
                $where = [
                    "OR"=>[
                        LoanRecord::DB_TABLE.'.'.LoanRecord::ID => $this->request->getUrlParams()->query,
                        LoanRecord::DB_TABLE.'.'.LoanRecord::ACCOUNT_NO => $this->request->getUrlParams()->query,
                        LoanRecord::DB_TABLE.'.'.LoanRecord::STATUS => $this->request->getUrlParams()->query,
                        LoanRecord::DB_TABLE.'.'.LoanRecord::AUTHORIZED_BY => $this->request->getUrlParams()->query,
                        LoanRecord::DB_TABLE.'.'.LoanRecord::DATE => $this->request->getUrlParams()->query
                    ],
                    'ORDER'=>[LoanRecord::DB_TABLE.'.'.LoanRecord::DATE=>"DESC"],
                    'LIMIT'=>[$offset,$rows]
                ];
                
            }
            else{
                $where = [
                    'ORDER'=>[LoanRecord::DB_TABLE.'.'.LoanRecord::DATE=>"DESC"],
                    'LIMIT'=>[$offset,$rows]
                ];
            }
        }
        else{
            if(!empty($this->request->getUrlParams()->query)){
                $where = [
                    "OR"=>[
                        LoanRecord::DB_TABLE.'.'.LoanRecord::ID => $this->request->getUrlParams()->query,
                        LoanRecord::DB_TABLE.'.'.LoanRecord::ACCOUNT_NO => $this->request->getUrlParams()->query,
                        LoanRecord::DB_TABLE.'.'.LoanRecord::STATUS => $this->request->getUrlParams()->query,
                        LoanRecord::DB_TABLE.'.'.LoanRecord::AUTHORIZED_BY => $this->request->getUrlParams()->query,
                        LoanRecord::DB_TABLE.'.'.LoanRecord::DATE => $this->request->getUrlParams()->query
                    ],
                    'AND'=>[LoanRecord::DB_TABLE.'.office'=>$this->request->getUrlParams()->office],
                    'ORDER'=>[LoanRecord::DB_TABLE.'.'.LoanRecord::DATE=>"DESC"],
                    'LIMIT'=>[$offset,$rows]
                ];
            }
            else{
                $where = [
                    'AND'=>[LoanRecord::DB_TABLE.'.office'=>$this->request->getUrlParams()->office],
                    'ORDER'=>[LoanRecord::DB_TABLE.'.'.LoanRecord::DATE=>"DESC"],
                    'LIMIT'=>[$offset,$rows]
                ];
            }
        }
        //fetch customers with $where clause
        $data = $this->db->select(LoanRecord::DB_TABLE,[
            "[>]offices"=>["office"=>"id"],
            "[>]".CustomerModel::DB_TABLE=>["account_no"=>"account_no"]
        ],[
            LoanRecord::DB_TABLE.'.'.LoanRecord::ID,
            LoanRecord::DB_TABLE.'.'.LoanRecord::ACCOUNT_NO,
            'offices.description',
            'offices.location',
            CustomerModel::DB_TABLE.'.'.CustomerModel::BIO_DATA,
            LoanRecord::DB_TABLE.'.'.LoanRecord::AMOUNT,
            LoanRecord::DB_TABLE.'.'.LoanRecord::STATUS,
            LoanRecord::DB_TABLE.'.'.LoanRecord::AUTHORIZED_BY,
            LoanRecord::DB_TABLE.'.'.LoanRecord::DATE,
        ],$where);
        if(empty($data)){
            $this->response->addMessage('Loan record is empty, add some new loans and see them appear here');
            $this->response->jsonResponse(null,400);
        }
        else{
            $this->response->addMeta([
            'balance'=>null,
            'total'=>$this->getCentralTotal($where),
            'list_total'=>($rows * ($page - 1)) + count($data)]);
            $this->response->jsonResponse($this->response->NormalizeMysqlArray($data));
        }
    }
}