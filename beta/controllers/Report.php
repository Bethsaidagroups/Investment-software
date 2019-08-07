<?php
/**
 * The Home controller class
 * @extends:abstract\controller
 */
namespace controllers;

 use includes\base\Controller;
 use includes\database\models\Option;
 use includes\database\models\Customer as CustomerModel;
 use includes\database\models\Login;
 use includes\database\models\UserType;
 use includes\database\models\SavingsAccount;
 use includes\utilities\SmsService;
 use includes\auth\{Session,Permission};
 use includes\database\models\{AccountTransaction};

 class Report extends Controller{

    private $db;

    private $datetime;

    private $date;

    private $year;

    private $month;

    private $day;

    private $hour;

    private $min;

    private $sec;


    public function __construct(){
        parent::__construct();
        $this->db = $GLOBALS['db'];
        $this->datetime = date("Y-m-d H:i:s");
        $this->date = date("Y-m-d");
        $this->year = date('Y');
        $this->month = date('m');
        $this->day = date('d');
    }

    public function getSummary(){
        //get summary for branch manager
        if(Permission::has_permission(Session::get('user_type'), Permission::MANAGER)){
            $this->authenticate(Permission::MANAGER); //Authenticate with permission
            //fetch todays new customers
            $customer = $this->db->db->count(CustomerModel::DB_TABLE,'*',[
                'AND'=>[
                    CustomerModel::OFFICE=>Session::get("office"),
                    CustomerModel::REGISTRATION_DATE."[<>]" => [date("Y-m-d H:i:s",mktime(0,0,1,$this->month,$this->day,$this->year)), 
                    date("Y-m-d H:i:s",mktime(23,59,59,$this->month,$this->day,$this->year))]
                ]
            ]);
            $transaction =$this->db->db->count(AccountTransaction::DB_TABLE,'*',[
                'AND'=>[
                    AccountTransaction::OFFICE=>Session::get("office"),
                    AccountTransaction::DATETIME."[<>]" => [date("Y-m-d H:i:s",mktime(0,0,1,$this->month,$this->day,$this->year)), 
                    date("Y-m-d H:i:s",mktime(23,59,59,$this->month,$this->day,$this->year))]
                ]
            ]);
            $pending = $this->db->db->count(AccountTransaction::DB_TABLE,'*',[
                'AND'=>[
                    AccountTransaction::OFFICE=>Session::get("office"),
                    AccountTransaction::STATUS=>AccountTransaction::$statuses['pending'],
                ]
            ]);
            $balance = $this->db->db->sum(SavingsAccount::DB_TABLE,SavingsAccount::BALANCE,[
                SavingsAccount::OFFICE=>Session::get("office")
            ]);

            $this->response->addMessage('Branch Manger home summary');
            $this->response->jsonResponse($this->response->NormalizeMysqlArray([
                'date'=>$this->date,
                'customer'=>$customer,
                'transaction'=>$transaction,
                'pending'=>$pending,
                'balance'=>$balance
            ]));
        }

        //get summary for branch secretary
        if(Permission::has_permission(Session::get('user_type'), Permission::SECRETARY)){
            $this->authenticate(Permission::SECRETARY); //Authenticate with permission
            //fetch todays new customers
            $customer = $this->db->db->count(CustomerModel::DB_TABLE,'*',[
                'AND'=>[
                    CustomerModel::OFFICE=>Session::get("office"),
                    CustomerModel::REGISTRATION_DATE."[<>]" => [date("Y-m-d H:i:s",mktime(0,0,1,$this->month,$this->day,$this->year)), 
                    date("Y-m-d H:i:s",mktime(23,59,59,$this->month,$this->day,$this->year))]
                ]
            ]);
            $transaction =$this->db->db->count(AccountTransaction::DB_TABLE,'*',[
                'AND'=>[
                    AccountTransaction::OFFICE=>Session::get("office"),
                    AccountTransaction::DATETIME."[<>]" => [date("Y-m-d H:i:s",mktime(0,0,1,$this->month,$this->day,$this->year)), 
                    date("Y-m-d H:i:s",mktime(23,59,59,$this->month,$this->day,$this->year))]
                ]
            ]);
            $pending = $this->db->db->count(AccountTransaction::DB_TABLE,'*',[
                'AND'=>[
                    AccountTransaction::OFFICE=>Session::get("office"),
                    AccountTransaction::STATUS=>AccountTransaction::$statuses['pending'],
                ]
            ]);

            $this->response->addMessage('Branch Manger home summary');
            $this->response->jsonResponse($this->response->NormalizeMysqlArray([
                'date'=>$this->date,
                'customer'=>$customer,
                'transaction'=>$transaction,
                'pending'=>$pending
            ]));
        }

        //get summary for central management unit
        if(Permission::has_permission(Session::get('user_type'), Permission::CENTRAL)){
            $this->authenticate(Permission::CENTRAL); //Authenticate with permission
            //fetch todays new customers
            $customer = $this->db->db->count(CustomerModel::DB_TABLE,'*',[]);
            $offices = $this->db->select('offices','*',[]);
            $balance = $this->db->db->sum(SavingsAccount::DB_TABLE,SavingsAccount::BALANCE,[]);
            $this->response->addMessage('Branch Manger home summary');
            $this->response->jsonResponse([
                'date'=>$this->date,
                'customer'=>$customer,
                'balance'=>$balance,
                'offices'=>$this->response->NormalizeMysqlArray($offices)
            ]);
        }

    }

    //get statement of account
    public function getSOA(){
        $this->authenticate(Permission::LASER_HIGH); //Authenticate with permission
        $data = $this->request->getPost();
        //fetch customers details
        $meta = $this->db->get(CustomerModel::DB_TABLE,[
           "[>]".SavingsAccount::DB_TABLE=>[CustomerModel::ACCOUNT_NO=>SavingsAccount::ACCOUNT_NO]
        ],[
            CustomerModel::DB_TABLE.'.'.CustomerModel::BIO_DATA,
            SavingsAccount::DB_TABLE.'.'.SavingsAccount::BALANCE
        ],[
            CustomerModel::DB_TABLE.'.'.CustomerModel::ACCOUNT_NO=>$data->account_no
        ]);
        //fetch SOA
        $soa = $this->db->select(AccountTransaction::DB_TABLE,'*',[
            'AND'=>[
                AccountTransaction::DATETIME."[<>]"=>[$data->from_date, date('Y-m-d', strtotime( "$data->to_date + 1 day"))],
                AccountTransaction::STATUS=>AccountTransaction::$statuses['completed'],
                AccountTransaction::ACCOUNT_NO=>$data->account_no
            ],
            'ORDER'=>[AccountTransaction::DB_TABLE.'.'.AccountTransaction::ID=>"ASC"]
        ]);

        if(empty($soa)){
            $this->response->addMessage('No transaction was recorded for this account between this period');
            $this->response->jsonResponse(null,400);
        }
        else{
            $this->response->addMessage('Statement of Account');
            $this->response->addMeta([
                    'account_no'=>$data->account_no,
                    'balance'=>$meta['balance'],
                    'bio_data'=>json_decode($meta['bio_data'],true)]);
            $this->response->jsonResponse($this->response->NormalizeMysqlArray($soa));
        }
    }

    //get branch report
    public function getBranchReport(){
        $this->authenticate(Permission::LASER_HIGH); //Authenticate with permission
        $data = $this->request->getPost();
        if(!empty($data->office)){
            $office_id = $data->office;
        }
        else{
            $office_id = Session::get("office");
        }
        if($data->type == 'all'){
            $total_amount = $this->db->db->sum(AccountTransaction::DB_TABLE,AccountTransaction::AMOUNT,[
                'AND'=>[
                    AccountTransaction::DATETIME."[<>]"=>[$data->from_date, date('Y-m-d', strtotime( "$data->to_date + 1 day" ))],
                    AccountTransaction::STATUS=>AccountTransaction::$statuses['completed'],
                    AccountTransaction::OFFICE=>$office_id,
                    AccountTransaction::CATEGORY=>$data->category
                ]
            ]);
        }
        else{
            $total_amount = $this->db->db->sum(AccountTransaction::DB_TABLE,AccountTransaction::AMOUNT,[
                'AND'=>[
                    AccountTransaction::DATETIME."[<>]"=>[$data->from_date, date('Y-m-d', strtotime( "$data->to_date + 1 day" ))],
                    AccountTransaction::STATUS=>AccountTransaction::$statuses['completed'],
                    AccountTransaction::OFFICE=>$office_id,
                    AccountTransaction::CATEGORY=>$data->category,
                    AccountTransaction::TYPE=>$data->type
                ]
            ]);
        }

        //fetch Report
        if($data->type == 'all'){
            $result = $this->db->select(AccountTransaction::DB_TABLE,'*',[
                'AND'=>[
                    AccountTransaction::DATETIME."[<>]"=>[$data->from_date, date('Y-m-d', strtotime( "$data->to_date + 1 day" ))],
                    AccountTransaction::STATUS=>AccountTransaction::$statuses['completed'],
                    AccountTransaction::OFFICE=>$office_id,
                    AccountTransaction::CATEGORY=>$data->category
                ],
                'ORDER'=>[AccountTransaction::DB_TABLE.'.'.AccountTransaction::ID=>"ASC"]
            ]);
        }
        else{
            $result = $this->db->select(AccountTransaction::DB_TABLE,'*',[
                'AND'=>[
                    AccountTransaction::DATETIME."[<>]"=>[$data->from_date, date('Y-m-d', strtotime( "$data->to_date + 1 day" ))],
                    AccountTransaction::STATUS=>AccountTransaction::$statuses['completed'],
                    AccountTransaction::OFFICE=>$office_id,
                    AccountTransaction::CATEGORY=>$data->category,
                    AccountTransaction::TYPE=>$data->type
                ],
                'ORDER'=>[AccountTransaction::DB_TABLE.'.'.AccountTransaction::ID=>"ASC"]
            ]);
        }

        if(empty($result)){
            $this->response->addMessage('No transaction with these filters was recorded for this branch between this period');
            $this->response->jsonResponse(null,400);
        }
        else{
            $office = $this->db->get("offices",'*',["id"=>$office_id]);
            $this->response->addMessage('Branch Transaction Report');
            $this->response->addMeta([
                    'category'=>$data->category,
                    'branch'=>$office["description"].", ".$office["location"],
                    'total_amount'=>$total_amount]);
            $this->response->jsonResponse($this->response->NormalizeMysqlArray($result));
        }
    }

    //get single central summary report
    private function getSingleCentralReport($office){
        //get the office details
        $credit = $this->db->db->sum(AccountTransaction::DB_TABLE,AccountTransaction::AMOUNT,[
            'AND'=>[
                AccountTransaction::OFFICE=>$office,
                AccountTransaction::TYPE=>AccountTransaction::$types['credit'],
                AccountTransaction::STATUS=>AccountTransaction::$statuses['completed'],
                AccountTransaction::DATETIME."[<>]" => [date("Y-m-d H:i:s",mktime(0,0,1,$this->month,$this->day,$this->year)), 
                date("Y-m-d H:i:s",mktime(23,59,59,$this->month,$this->day,$this->year))]
            ]
        ]);
        $credit_no = $this->db->db->count(AccountTransaction::DB_TABLE,'*',[
            'AND'=>[
                AccountTransaction::OFFICE=>$office,
                AccountTransaction::TYPE=>AccountTransaction::$types['credit'],
                AccountTransaction::STATUS=>AccountTransaction::$statuses['completed'],
                AccountTransaction::DATETIME."[<>]" => [date("Y-m-d H:i:s",mktime(0,0,1,$this->month,$this->day,$this->year)), 
                date("Y-m-d H:i:s",mktime(23,59,59,$this->month,$this->day,$this->year))]
            ]
        ]);
        $debit = $this->db->db->sum(AccountTransaction::DB_TABLE,AccountTransaction::AMOUNT,[
            'AND'=>[
                AccountTransaction::OFFICE=>$office,
                AccountTransaction::TYPE=>AccountTransaction::$types['debit'],
                AccountTransaction::STATUS=>AccountTransaction::$statuses['completed'],
                AccountTransaction::DATETIME."[<>]" => [date("Y-m-d H:i:s",mktime(0,0,1,$this->month,$this->day,$this->year)), 
                date("Y-m-d H:i:s",mktime(23,59,59,$this->month,$this->day,$this->year))]
            ]
        ]);
        $debit_no = $this->db->db->count(AccountTransaction::DB_TABLE,'*',[
            'AND'=>[
                AccountTransaction::OFFICE=>$office,
                AccountTransaction::TYPE=>AccountTransaction::$types['debit'],
                AccountTransaction::STATUS=>AccountTransaction::$statuses['completed'],
                AccountTransaction::DATETIME."[<>]" => [date("Y-m-d H:i:s",mktime(0,0,1,$this->month,$this->day,$this->year)), 
                date("Y-m-d H:i:s",mktime(23,59,59,$this->month,$this->day,$this->year))]
            ]
        ]);
        $balance = $this->db->db->sum(SavingsAccount::DB_TABLE,SavingsAccount::BALANCE,[
            SavingsAccount::OFFICE=>$office
        ]);
        return [
            'credit_no'=>$credit_no,
            'credit'=>$credit,
            'debit_no'=>$debit_no,
            'debit'=>$debit,
            'balance'=>$balance
        ];
    }
    //for central management unit for summary report
    public function getCentralSummaryReport(){
        $this->authenticate(Permission::CENTRAL); //Authenticate with permission
        $offices = $this->db->select("offices",['id','location','description'],[]); 
        $result = array();
        foreach($offices as $value){
            $result[] = [
                "office"=>$value,
                "result"=>$this->getSingleCentralReport($value['id'])
            ];
        }
        $this->response->addMessage('General Report');
        $this->response->addMeta(['date'=>date('Y-m-d')]);
        $this->response->jsonResponse($this->response->NormalizeMysqlArray($result));
    }
 }
 ?>