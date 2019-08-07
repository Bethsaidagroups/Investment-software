<?php
/**
 * Savings Account Controller
 * @extends:abstract\controller
 */

 namespace controllers;

 use includes\base\Controller;
 use includes\auth\{Session,Permission};
 use includes\database\models\Customer as CustomerModel;
 use includes\database\models\{AccountTransaction, SavingsAccount as SavingsAccountModel};
 use includes\BasicTransactions\SavingsAccount as SavingsAction;
 use includes\utilities\SmsService;
 

 class SavingsAccount extends Controller{

    private $db;

    public function __construct(){
        parent::__construct();
        $this->db = $GLOBALS['db'];
    }
    //get total savings account
    public function getTotal($query=null){
        //check if query was sent with the request
        if($query){
            $data_count = $this->db->db->count(SavingsAccountModel::DB_TABLE,'*',[
                "MATCH" => [
                    "columns" => [
                        SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::ACCOUNT_NO,
                        SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::STATUS,
                    ],
                    "keyword" => $query,
             
                    // [optional] Search mode
                    "mode" => "natural"
                ]
            ]);
            return $data_count;
        }
        else{
            $data_count = $this->db->db->count(SavingsAccountModel::DB_TABLE,'*',[
                SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::OFFICE=>Session::get('office'),
            ]);
            return $data_count;
        }
    }

    public function getPrimaryAccountDetails(){
        $this->authenticate(Permission::DEFAULT); //Authenticate with permission
        $data = $this->db->get(SavingsAccountModel::DB_TABLE,[
            "[>]".CustomerModel::DB_TABLE=>["account_no"=>"account_no"]
        ],[
            CustomerModel::DB_TABLE.'.'.CustomerModel::BIO_DATA,
            SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::BALANCE
        ],[
            SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::ACCOUNT_NO=>$this->request->getUrlParams()->account
        ]);
        if(!empty($data)){
            $this->response->addMessage('Customer Account: '.$this->request->getUrlParams()->account);
            $this->response->jsonResponse($this->response->NormalizeMysqlArray($data));
        }
        else{
            $this->response->addMessage('Account number does not exist');
            $this->response->jsonResponse(null);
        }
    }

    public function initWithdrawal(){
        $this->authenticate(Permission::SECRETARY); //Authenticate with permission
        //check if account balanace can settle the withdrawal
        $data = $this->request->getPost();
        $savings_action = new SavingsAction($this->db, $data->account_no);
        //check if account is registered
        if(!$savings_action->isRegistered()){
            $this->response->addMessage('The target account does not exist, please check and try again');
            $this->response->jsonResponse(null,400);
        }
        if($savings_action->getBalance() > $data->amount){
            //initiate withdrawal
            //Add transaction to database
            $meta = ["narration"=>$data->narration];
            $payload = [
                AccountTransaction::ACCOUNT_NO=>$data->account_no,
                AccountTransaction::CATEGORY=>AccountTransaction::$categories['savings'],
                AccountTransaction::TYPE=>AccountTransaction::$types['debit'],
                AccountTransaction::AMOUNT=>$data->amount,
                AccountTransaction::CHANNEL=>AccountTransaction::$channels[$data->channel],
                AccountTransaction::AUTHORIZED_BY=>json_encode(['initial'=>Session::get('username')]),
                AccountTransaction::STATUS=>AccountTransaction::$statuses['pending'],
                AccountTransaction::OFFICE=>Session::get('office'),
                AccountTransaction::META=>json_encode($meta),
                AccountTransaction::DATETIME=>date("Y-m-d H:i:s")
            ];
            $savings_action->registerTransaction($payload);
            
            $this->response->addMessage('Withdrawal transaction initialized successfully');
            $this->response->jsonResponse();
        }
        else{
            $this->response->addMessage('This account does not have enough credit balance to perform this transaction');
            $this->response->jsonResponse(null,400);
        }
    }

    public function initDeposit(){
        $this->authenticate(Permission::SECRETARY); //Authenticate with permission
        //check if account balanace can settle the withdrawal
        $data = $this->request->getPost();
        $savings_action = new SavingsAction($this->db, $data->account_no);
        //check if account is registered
        if(!$savings_action->isRegistered()){
            $this->response->addMessage('The target account does not exist, please check and try again');
            $this->response->jsonResponse(null,400);
        }
            //initiate deposit
            //Add transaction to database
            $meta = ["narration"=>$data->narration];
            $payload = [
                AccountTransaction::ACCOUNT_NO=>$data->account_no,
                AccountTransaction::CATEGORY=>AccountTransaction::$categories['savings'],
                AccountTransaction::TYPE=>AccountTransaction::$types['credit'],
                AccountTransaction::AMOUNT=>$data->amount,
                AccountTransaction::CHANNEL=>AccountTransaction::$channels[$data->channel],
                AccountTransaction::AUTHORIZED_BY=>json_encode(['initial'=>Session::get('username')]),
                AccountTransaction::STATUS=>AccountTransaction::$statuses['pending'],
                AccountTransaction::OFFICE=>Session::get('office'),
                AccountTransaction::META=>json_encode($meta),
                AccountTransaction::DATETIME=>date("Y-m-d H:i:s")
            ];
            $savings_action->registerTransaction($payload);
            
            $this->response->addMessage('Deposit transaction initialized successfully');
            $this->response->jsonResponse();
    }
    public function getSingleAccount(){
        $this->authenticate(Permission::DEFAULT); //Authenticate with permission
        $data = $this->db->get(SavingsAccountModel::DB_TABLE,'*',[
            SavingsAccountModel::ACCOUNT_NO=>$this->request->getUrlParams()->account
        ]);
        if(empty($data)){
            $this->response->addMessage('There is no account/customer record associated with this account_no in our database');
            $this->response->jsonResponse(null,400);
        }
        else{
            $this->response->jsonResponse($this->response->NormalizeMysqlArray($data));
        }
    }

    //update savings account
    public function updateSavings(){
        $this->authenticate(Permission::MANAGER); //Authenticate with permission
        $data = $this->request->getPost();
        $this->db->update(SavingsAccountModel::DB_TABLE,[
            SavingsAccountModel::PLAN=>json_encode($data->plan),
            SavingsAccountModel::STATUS=>SavingsAccountModel::$statuses[$data->status]
        ],[
            SavingsAccountModel::ACCOUNT_NO=>$this->request->getUrlParams()->account
        ]);
        $this->response->addMessage('Savings account updated successfully');
        $this->response->jsonResponse();
    }
    //get customers savings accounts list
    public function getList(){
        $this->authenticate(Permission::DEFAULT); //Authenticate with permission
        //check if query was sent with the request
        if(!empty($this->request->getUrlParams()->query)){
            $page = $this->request->getUrlParams()->page;
            $rows = 15;
            $offset = ($page * $rows) - $rows;
            $data = $this->db->select(SavingsAccountModel::DB_TABLE,[
                "[>]offices"=>["office"=>"id"],
                "[>]customers"=>["account_no"=>"account_no"]
            ],[
                SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::ACCOUNT_NO,
                SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::PLAN,
                'offices.description',
                'offices.location',
                CustomerModel::DB_TABLE.'.'.CustomerModel::BIO_DATA,
                SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::BALANCE,
                SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::STATUS,
            ],[
                "MATCH" => [
                    "columns" => [
                        SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::ACCOUNT_NO,
                        SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::STATUS,
                    ],
                    "keyword" => $this->request->getUrlParams()->query,
             
                    // [optional] Search mode
                    "mode" => "natural"
                ],
                'ORDER'=>SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::ACCOUNT_NO,
                'LIMIT'=>[$offset,$rows]
            ]);
            if(empty($data)){
                $this->response->addMessage('There is no account/customer record associated with this keyword in our database');
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
            $data = $this->db->select(SavingsAccountModel::DB_TABLE,[
                "[>]offices"=>["office"=>"id"],
                "[>]customers"=>["account_no"=>"account_no"]
            ],[
                SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::ACCOUNT_NO,
                SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::PLAN,
                'offices.description',
                'offices.location',
                CustomerModel::DB_TABLE.'.'.CustomerModel::BIO_DATA,
                SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::BALANCE,
                SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::STATUS,
            ],[
                SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::OFFICE=>Session::get('office'),
                'ORDER'=>SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::ACCOUNT_NO,
                'LIMIT'=>[$offset,$rows]
            ]);
            if(empty($data)){
                $this->response->addMessage('Savings account record is empty, add some new customers and see them appear here');
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

    //make withdrawal
    public function makeWithdrawal(){
        $this->authenticate(Permission::MANAGER); //Authenticate with permission
        //check if account balanace can settle the withdrawal
        $data = $this->request->getPost();
        $savings_action = new SavingsAction($this->db, $data->account_no);
        //check if account is registered
        if(!$savings_action->isRegistered()){
            $this->response->addMessage('The target account does not exist, please check and try again');
            $this->response->jsonResponse(null,400);
        }
        $bio_data = $this->db->get(CustomerModel::DB_TABLE,CustomerModel::BIO_DATA,[
            CustomerModel::ACCOUNT_NO=>$data->account_no
        ]);
        $mobile = json_decode($bio_data)->mobile1;
        if($savings_action->getBalance() >= $data->amount){
            //make withdrawal
            $meta = ["narration"=>$data->narration];
            if($savings_action->makeWithdrawal($data->amount,[
                    'channel'=>$data->channel,
                    'authorized_by'=>json_encode(['final'=>Session::get('username')]),
                    'office'=>Session::get('office'),
                    'meta'=>json_encode($meta),
                    'datetime'=>date("Y-m-d H:i:s")
                ],function() use(&$data,&$savings_action,&$mobile){
                    $sms = new SmsService($mobile);
                    $sms->sendNewDebitMessage([
                        'amount'=>number_format($data->amount,2),
                        'channel'=>$data->channel,
                        'account_number'=>$data->account_no,
                        'narration'=>$data->narration,
                        'datetime'=>date("Y-m-d H:i:s"),
                        'balance'=>number_format($savings_action->getBalance(),2)
                ]);
            })){
                $this->response->addMessage('Withdrawal transaction completed successfully');
                $this->response->jsonResponse();
            }
            else{
                $this->response->addMessage('Can not perform any transaction on deactivated accounts');
                $this->response->jsonResponse(null, 400);
            }
        }
        else{
            //insufficient balance
            $this->response->addMessage('This account does not have enough credit balance to perform this transaction');
            $this->response->jsonResponse(null, 400);
        }
    }

    //make deposit
    public function makeDeposit(){
        $this->authenticate(Permission::MANAGER); //Authenticate with permission
        //check if account balanace can settle the withdrawal
        $data = $this->request->getPost();
        $savings_action = new SavingsAction($this->db, $data->account_no);
        //check if account is registered
        if(!$savings_action->isRegistered()){
            $this->response->addMessage('The target account does not exist, please check and try again');
            $this->response->jsonResponse(null,400);
        }
        $bio_data = $this->db->get(CustomerModel::DB_TABLE,CustomerModel::BIO_DATA,[
            CustomerModel::ACCOUNT_NO=>$data->account_no
        ]);
        $mobile = json_decode($bio_data)->mobile1;
            //make withdrawal
            $meta = ["narration"=>$data->narration];
            if($savings_action->makeDeposit($data->amount,[
                    'channel'=>$data->channel,
                    'authorized_by'=>json_encode(['final'=>Session::get('username')]),
                    'office'=>Session::get('office'),
                    'meta'=>json_encode($meta),
                    'datetime'=>date("Y-m-d H:i:s")
                ],function() use (&$data,&$savings_action,&$mobile){
                    $sms = new SmsService($mobile);
                    $sms->sendNewCreditMessage([
                        'amount'=>number_format($data->amount,2),
                        'channel'=>$data->channel,
                        'account_number'=>$data->account_no,
                        'narration'=>$data->narration,
                        'datetime'=>date("Y-m-d H:i:s"),
                        'balance'=>number_format($savings_action->getBalance(),2)
                ]);
            })){
                $this->response->addMessage('Deposit transaction completed successfully');
                $this->response->jsonResponse();
            }
            else{
                $this->response->addMessage('Can not perform any transaction on deactivated accounts');
                $this->response->jsonResponse(null, 400);
            }
    }
    //get total customers
    public function getCentralTotal($where){
        //check if query was sent with the request
        unset($where['LIMIT']);
        $data_count = $this->db->db->count(SavingsAccountModel::DB_TABLE,'*',$where);
        return $data_count;
    }

    //get balance for the current query set [for central management unit alone]
    public function getCentralBalance($where){
        unset($where['LIMIT']);
        $balance = $this->db->db->sum(SavingsAccountModel::DB_TABLE,SavingsAccountModel::BALANCE,$where);
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
                    "MATCH" => [
                        "columns" => [
                            SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::ACCOUNT_NO,
                            SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::STATUS,
                        ],
                        "keyword" => $this->request->getUrlParams()->query,
                 
                        // [optional] Search mode
                        "mode" => "natural"
                    ],
                    'ORDER'=>SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::ACCOUNT_NO,
                    'LIMIT'=>[$offset,$rows]
                ];
                
            }
            else{
                $where = [
                    'ORDER'=>SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::ACCOUNT_NO,
                    'LIMIT'=>[$offset,$rows]
                ];
            }
        }
        else{
            if(!empty($this->request->getUrlParams()->query)){
                $where = [
                    "MATCH" => [
                        "columns" => [
                            SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::ACCOUNT_NO,
                            SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::STATUS,
                        ],
                        "keyword" => $this->request->getUrlParams()->query,
                 
                        // [optional] Search mode
                        "mode" => "natural"
                    ],
                    'AND'=>[SavingsAccountModel::DB_TABLE.'.office'=>$this->request->getUrlParams()->office],
                    'ORDER'=>SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::ACCOUNT_NO,
                    'LIMIT'=>[$offset,$rows]
                ];
            }
            else{
                $where = [
                    'AND'=>[SavingsAccountModel::DB_TABLE.'.office'=>$this->request->getUrlParams()->office],
                    'ORDER'=>SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::ACCOUNT_NO,
                    'LIMIT'=>[$offset,$rows]
                ];
            }
        }
        //fetch customers with $where clause
        $data = $this->db->select(SavingsAccountModel::DB_TABLE,[
            "[>]offices"=>["office"=>"id"],
            "[>]customers"=>["account_no"=>"account_no"]
        ],[
            SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::ACCOUNT_NO,
            SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::PLAN,
            'offices.description',
            'offices.location',
            CustomerModel::DB_TABLE.'.'.CustomerModel::BIO_DATA,
            SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::BALANCE,
            SavingsAccountModel::DB_TABLE.'.'.SavingsAccountModel::STATUS,
        ],$where);
        if(empty($data)){
            $this->response->addMessage('Savings account record is empty, add some new customers and see them appear here');
            $this->response->jsonResponse(null,400);
        }
        else{
            $this->response->addMeta([
            'balance'=>$this->getCentralBalance($where),
            'total'=>$this->getCentralTotal($where),
            'list_total'=>($rows * ($page - 1)) + count($data)]);
            $this->response->jsonResponse($this->response->NormalizeMysqlArray($data));
        }
    }
 }