<?php
/**
 * The Transaction controller class
 * @extends:abstract\controller
 */
namespace controllers;

 use includes\base\Controller;
 use includes\database\models\Option;
 use includes\database\models\Customer as CustomerModel;
 use includes\database\models\Login;
 use includes\database\models\UserType;
 use includes\utilities\SmsService;
 use includes\auth\{Session,Permission};
 use includes\database\models\{AccountTransaction};
 use includes\BasicTransactions\SavingsAccount as SavingsAction;
 
 class Transaction extends Controller{

    private $db;

    public function __construct(){
        parent::__construct();
        $this->db = $GLOBALS['db'];
    }

    //get total savings account
    public function getTotal($query=null){
        //check if query was sent with the request
        if($query){
            $data_count = $this->db->db->count(AccountTransaction::DB_TABLE,'*',[
                "MATCH" => [
                    "columns" => [
                        AccountTransaction::DB_TABLE.'.'.AccountTransaction::ACCOUNT_NO,
                        AccountTransaction::DB_TABLE.'.'.AccountTransaction::CATEGORY,
                        AccountTransaction::DB_TABLE.'.'.AccountTransaction::TYPE,
                        AccountTransaction::DB_TABLE.'.'.AccountTransaction::CHANNEL,
                        AccountTransaction::DB_TABLE.'.'.AccountTransaction::STATUS,
                    ],
                    "keyword" => $query,
             
                    // [optional] Search mode
                    "mode" => "natural"
                ]
            ]);
            return $data_count;
        }
        else{
            $data_count = $this->db->db->count(AccountTransaction::DB_TABLE,'*',[
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::OFFICE=>Session::get('office'),
            ]);
            return $data_count;
        }
    }

    public function getList(){
        $this->authenticate(Permission::DEFAULT); //Authenticate with permission
        //check if query was sent with the request
        if(!empty($this->request->getUrlParams()->query)){
            $page = $this->request->getUrlParams()->page;
            $rows = 15;
            $offset = ($page * $rows) - $rows;
            if(strcasecmp($this->request->getUrlParams()->query, 'pending') === 0){
                $where = [
                    "MATCH" => [
                        "columns" => [
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::ACCOUNT_NO,
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::CATEGORY,
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::TYPE,
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::CHANNEL,
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::STATUS,
                        ],
                        "keyword" => $this->request->getUrlParams()->query,
                 
                        // [optional] Search mode
                        "mode" => "natural"
                    ],
                    'AND'=>[
                        AccountTransaction::DB_TABLE.'.'.AccountTransaction::OFFICE=>Session::get('office')
                    ],
                    'ORDER'=>[AccountTransaction::DB_TABLE.'.'.AccountTransaction::DATETIME=>"DESC"],
                    'LIMIT'=>[$offset,$rows]
                    ];
            }
            else{
                $where = [
                    "MATCH" => [
                        "columns" => [
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::ACCOUNT_NO,
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::CATEGORY,
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::TYPE,
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::CHANNEL,
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::STATUS,
                        ],
                        "keyword" => $this->request->getUrlParams()->query,
                 
                        // [optional] Search mode
                        "mode" => "natural"
                    ],
                    'ORDER'=>[AccountTransaction::DB_TABLE.'.'.AccountTransaction::DATETIME=>"DESC"],
                    'LIMIT'=>[$offset,$rows]
                    ];
            }
            $data = $this->db->select(AccountTransaction::DB_TABLE,[
                "[>]offices"=>["office"=>"id"],
                "[>]customers"=>["account_no"=>"account_no"]
            ],[
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::ID,
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::ACCOUNT_NO,
                CustomerModel::DB_TABLE.'.'.CustomerModel::BIO_DATA,
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::CATEGORY,
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::TYPE,
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::AMOUNT,
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::CHANNEL,
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::AUTHORIZED_BY,
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::STATUS,
                'offices.description',
                'offices.location',
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::META,
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::DATETIME,
            ],$where);
            if(empty($data)){
                $this->response->addMessage('There is no transaction record associated with this keyword in our database');
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
            $data = $this->db->select(AccountTransaction::DB_TABLE,[
                "[>]offices"=>["office"=>"id"],
                "[>]customers"=>["account_no"=>"account_no"]
            ],[
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::ID,
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::ACCOUNT_NO,
                CustomerModel::DB_TABLE.'.'.CustomerModel::BIO_DATA,
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::CATEGORY,
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::TYPE,
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::AMOUNT,
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::CHANNEL,
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::AUTHORIZED_BY,
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::STATUS,
                'offices.description',
                'offices.location',
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::META,
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::DATETIME,
            ],[
                AccountTransaction::DB_TABLE.'.'.AccountTransaction::OFFICE=>Session::get('office'),
                'ORDER'=>[AccountTransaction::DB_TABLE.'.'.AccountTransaction::DATETIME=>"DESC"],
                'LIMIT'=>[$offset,$rows]
            ]);
            if(empty($data)){
                $this->response->addMessage('Transaction record is empty make some transaction and see them appear on here');
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

    //get transaction with id
    public function getTransactionById(){
        $data = $this->db->select(AccountTransaction::DB_TABLE,[
            "[>]offices"=>["office"=>"id"],
            "[>]customers"=>["account_no"=>"account_no"]
        ],[
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::ID,
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::ACCOUNT_NO,
            CustomerModel::DB_TABLE.'.'.CustomerModel::BIO_DATA,
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::CATEGORY,
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::TYPE,
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::AMOUNT,
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::CHANNEL,
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::AUTHORIZED_BY,
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::STATUS,
            'offices.description',
            'offices.location',
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::META,
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::DATETIME,
        ],[
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::ID=>$this->request->getUrlParams()->id
        ]);
        if(empty($data)){
            $this->response->addMessage('Transaction record with Reference Id: "'.$this->request->getUrlParams()->id.'" does not exist');
            $this->response->jsonResponse(null,400);
        }
        else{
            $this->response->addMeta([
            'total'=>1,
            'list_total'=>1]);
            $this->response->jsonResponse($this->response->NormalizeMysqlArray($data));
        }
    }
    public function confirm(){
        $this->authenticate(Permission::MANAGER); //Authenticate with permission
        //Confirm transaction
        $trans_id = $this->request->getUrlParams()->id;
        $savings_action = new SavingsAction($this->db);
        $trans_data = $savings_action->getSingleTransaction(['id'=>$trans_id]);
        if(strcasecmp($trans_data['status'],'pending') === 0){
            //transaction status is not completes, continue
            if((strcasecmp($trans_data['category'],'savings') === 0)){
                //it is of category savings, act accordingly
                if((strcasecmp($trans_data['type'],'debit') === 0)){
                    //it is a debit transaction
                    $bio_data = $this->db->get(CustomerModel::DB_TABLE,CustomerModel::BIO_DATA,[
                        CustomerModel::ACCOUNT_NO=>$trans_data['account_no']
                    ]);
                    $mobile = json_decode($bio_data)->mobile1;
                    if($savings_action->getBalance($trans_data["account_no"]) >= $trans_data["amount"]){
                        if($savings_action->confirmWithdrawal($trans_id,
                            function() use(&$trans_data,&$savings_action){
                                $sms = new SmsService($mobile);
                                $sms->sendNewDebitMessage([
                                    'amount'=>number_format($trans_data['amount'],2),
                                    'channel'=>$trans_data['channel'],
                                    'account_number'=>$trans_data['account_no'],
                                    'narration'=>json_decode($trans_data['meta_data'])->narration,
                                    'datetime'=>date("Y-m-d H:i:s"),
                                    'balance'=>number_format($savings_action->getBalance($trans_data['account_no']),2)
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
                        $this->response->jsonResponse(null,400);
                    }
                }
                elseif((strcasecmp($trans_data['type'],'credit') === 0)){
                    //it is a debit transaction
                    $bio_data = $this->db->get(CustomerModel::DB_TABLE,CustomerModel::BIO_DATA,[
                        CustomerModel::ACCOUNT_NO=>$trans_data['account_no']
                    ]);
                    $mobile = json_decode($bio_data)->mobile1;
                        if($savings_action->confirmDeposit($trans_id,
                            function() use(&$trans_data,&$savings_action){
                                $sms = new SmsService($mobile);
                                $sms->sendNewCreditMessage([
                                    'amount'=>number_format($trans_data['amount'],2),
                                    'channel'=>$trans_data['channel'],
                                    'account_number'=>$trans_data['account_no'],
                                    'narration'=>json_decode($trans_data['meta_data'])->narration,
                                    'datetime'=>date("Y-m-d H:i:s"),
                                    'balance'=>number_format($savings_action->getBalance($trans_data['account_no']),2)
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
            }
            else{
                $this->response->addMessage("System couldn't find any operational proceedure for this category of transaction");
                $this->response->jsonResponse(null, 400);
            }
        }
        else{
            $this->response->addMessage('No further changes/update can be made on a completed/declined transaction');
            $this->response->jsonResponse(null,400);
        }
    }

    public function decline(){
        $this->authenticate(Permission::MANAGER); //Authenticate with permission
        //decline transaction
        $trans_id = $this->request->getUrlParams()->id;
        $savings_action = new SavingsAction($this->db);
        $trans_data = $savings_action->getSingleTransaction(['id'=>$trans_id]);
        $authorized_by = json_decode($trans_data['authorized_by'],true);
        $authorized_by['final'] = Session::get('username');
        if(strcasecmp($trans_data['status'],'pending') === 0){
            //update transaction status
            $this->db->update(AccountTransaction::DB_TABLE,[
                AccountTransaction::STATUS=>AccountTransaction::$statuses['declined'],
                AccountTransaction::AUTHORIZED_BY=>json_encode($authorized_by)
            ],[
                AccountTransaction::ID=>$trans_id
            ]);
            $this->response->addMessage('Transaction has been declined');
            $this->response->jsonResponse();
        }
        else{
            $this->response->addMessage('No further changes/update can be made on a completed/declined transaction');
            $this->response->jsonResponse(null,400);
        }
    }

    //get total customers
    public function getCentralTotal($where){
        //check if query was sent with the request
        unset($where['LIMIT']);
        $data_count = $this->db->db->count(AccountTransaction::DB_TABLE,'*',$where);
        return $data_count;
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
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::ACCOUNT_NO,
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::CATEGORY,
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::TYPE,
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::CHANNEL,
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::STATUS,
                        ],
                        "keyword" => $this->request->getUrlParams()->query,
                 
                        // [optional] Search mode
                        "mode" => "natural"
                    ],
                    'ORDER'=>[AccountTransaction::DB_TABLE.'.'.AccountTransaction::DATETIME=>"DESC"],
                    'LIMIT'=>[$offset,$rows]
                ];
                
            }
            else{
                $where = [
                    'ORDER'=>[AccountTransaction::DB_TABLE.'.'.AccountTransaction::DATETIME=>"DESC"],
                    'LIMIT'=>[$offset,$rows]
                ];
            }
        }
        else{
            if(!empty($this->request->getUrlParams()->query)){
                $where = [
                    "MATCH" => [
                        "columns" => [
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::ACCOUNT_NO,
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::CATEGORY,
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::TYPE,
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::CHANNEL,
                            AccountTransaction::DB_TABLE.'.'.AccountTransaction::STATUS,
                        ],
                        "keyword" => $this->request->getUrlParams()->query,
                 
                        // [optional] Search mode
                        "mode" => "natural"
                    ],
                    'AND'=>[AccountTransaction::DB_TABLE.'.'.AccountTransaction::OFFICE=>$this->request->getUrlParams()->office],
                    'ORDER'=>[AccountTransaction::DB_TABLE.'.'.AccountTransaction::DATETIME=>"DESC"],
                    'LIMIT'=>[$offset,$rows]
                ];
            }
            else{
                $where = [
                    'AND'=>[AccountTransaction::DB_TABLE.'.'.AccountTransaction::OFFICE=>$this->request->getUrlParams()->office],
                        'ORDER'=>[AccountTransaction::DB_TABLE.'.'.AccountTransaction::DATETIME=>"DESC"],
                        'LIMIT'=>[$offset,$rows]
                ];
            }
        }
        //fetch customers with $where clause
        $data = $this->db->select(AccountTransaction::DB_TABLE,[
            "[>]offices"=>["office"=>"id"],
            "[>]customers"=>["account_no"=>"account_no"]
        ],[
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::ID,
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::ACCOUNT_NO,
            CustomerModel::DB_TABLE.'.'.CustomerModel::BIO_DATA,
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::CATEGORY,
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::TYPE,
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::AMOUNT,
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::CHANNEL,
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::AUTHORIZED_BY,
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::STATUS,
            'offices.description',
            'offices.location',
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::META,
            AccountTransaction::DB_TABLE.'.'.AccountTransaction::DATETIME,
        ],$where);
        if(empty($data)){
            $this->response->addMessage('Customers record is empty, add some new customers and see them appear here');
            $this->response->jsonResponse(null,400);
        }
        else{
            $this->response->addMeta([
            'total'=>$this->getCentralTotal($where),
            'list_total'=>($rows * ($page - 1)) + count($data)]);
            $this->response->jsonResponse($this->response->NormalizeMysqlArray($data));
        }
    }
 }