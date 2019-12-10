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
 
 class Customer extends Controller{

    private $db;

    public function __construct(){
        parent::__construct();
        $this->db = $GLOBALS['db'];
    }

    //get next account number
    private function getNextAccountNo(){
        $account_number = $this->db->get(Option::DB_TABLE,Option::VALUE,[Option::NAME=>Option::$names['pointer']]);
        //change account number pointer by updating the db
        $this->db->update(Option::DB_TABLE,[
            Option::VALUE=>$account_number + 1
        ],[
            Option::NAME=>Option::$names['pointer']
        ]);
        return $account_number;
    }

    //get marketers
    public function getMarketers($office = NULL){
        $this->authenticate();
        if(is_null($office)){
            $marketers = $this->db->select(Login::DB_TABLE,['username','meta'],['user_type'=>8]);
        }
        else{
            $marketers = $this->db->select(Login::DB_TABLE,'*',[
                'user_type'=>8,
                'office'=>$office
            ]);
        }
        $this->response->jsonResponse($this->response->normalizeMysqlArray($marketers));
    }

    //Customer Data validator
    private function validate($data){
        $errors = 'The following fields are required: ';
        $counter = 0; //success counter
        foreach($data as $key => $value){
            if(empty($value)){
                $errors.="$key | ";
            }
            else{
                if($key == 'bio_data'){
                    (empty($value->surname)) ? $errors.="Surname (bio data) | " : $counter++;
                    (empty($value->first_name)) ? $errors.="First Name (bio data) | " : $counter++;
                    (empty($value->mobile1)) ? $errors.="Phone Number 1 (bio data) | " : $counter++;;
                }
                else{
                    $counter++;
                }
            }
        }
        if($counter < 10){
            $rem = 10 - $counter;
            $errors .= "At least $rem out of 10 important sections/fields are utterly blank";
            return $errors;
        }
        else{
            return false;
        }
    }
    //get total customers
    public function getTotal($query=null){
        //check if query was sent with the request
        if($query){
            $data_count = $this->db->db->count(CustomerModel::DB_TABLE,'*',[
                "MATCH" => [
                    "columns" => [
                        CustomerModel::DB_TABLE.'.'.CustomerModel::ACCOUNT_NO,
                        CustomerModel::DB_TABLE.'.'.CustomerModel::CATEGORY,
                        CustomerModel::DB_TABLE.'.'.CustomerModel::MARKETER,
                    ],
                    "keyword" => $query,
             
                    // [optional] Search mode
                    "mode" => "natural"
                ]
            ]);
            return $data_count;
        }
        else{
            $data_count = $this->db->db->count(CustomerModel::DB_TABLE,'*',[
                CustomerModel::DB_TABLE.'.'.CustomerModel::OFFICE=>Session::get('office'),
            ]);
            return $data_count;
        }
    }
    //save new customer
    public function save(){
       //authenticate with permission before continuing with the rest of the script;
       $this->authenticate(Permission::LASER_DIM);
       $data = $this->request->getPost();
       if($this->validate($data)){
           $this->response->addMessage($this->validate($data) . ' [all important fields and sections are marked with *]');
           $this->response->jsonResponse();
       }
       $account_number = $this->getNextAccountNo();
       if(!$this->db->has(CustomerModel::DB_TABLE,[CustomerModel::ACCOUNT_NO=>$account_number])){
          //account no does not exist continue new customer creation
          $this->db->insert(CustomerModel::DB_TABLE,[
              CustomerModel::ACCOUNT_NO=>$account_number,
              CustomerModel::CATEGORY=>$data->category,
              CustomerModel::OFFICE=>Session::get('office'),
              CustomerModel::BIO_DATA=>json_encode($data->bio_data),
              CustomerModel::ID_DATA=>@json_encode($data->id_data),
              CustomerModel::CRP_MODE=>json_encode($data->crp_mode),
              CustomerModel::EMPLOYMENT_DATA=>@json_encode($data->employment_data),
              CustomerModel::INVESTMENT_PLAN=>json_encode($data->investment),
              CustomerModel::KIN_DATA=>@json_encode($data->kin_data),
              CustomerModel::REGISTERED_BY=>Session::get('username'),
              CustomerModel::MARKETER=>$data->marketer,
              CustomerModel::DATE=>date("Y-m-d"),
              CustomerModel::REGISTRATION_DATE=>date("Y-m-d H:i:s"),
          ]);

          //create savings account for the new customer
            $this->db->insert(SavingsAccount::DB_TABLE,[
                SavingsAccount::ACCOUNT_NO=>$account_number,
                SavingsAccount::PLAN=>json_encode($data->plan),
                SavingsAccount::BALANCE=>0.00,
                SavingsAccount::OFFICE=>Session::get('office'),
                SavingsAccount::STATUS=>SavingsAccount::$statuses['active']   
            ]);
            //notify user via sms
            $sms = new SmsService($data->bio_data->mobile1);
            $sms->sendNewAccountMessage([
                'surname'=>$data->bio_data->surname,
                'first_name'=>$data->bio_data->first_name,
                'account_number'=>$account_number
            ]);
            //send response to user
            $this->response->addMessage('Account Opened Successfully');
            $this->response->jsonResponse(array('account_no'=>$account_number));
       }
    }

    //update customer details
    public function update(){
        $this->authenticate(Permission::MANAGER); //Authenticate with permission
        $data = $this->request->getPost();
        $this->db->update(CustomerModel::DB_TABLE,[
            CustomerModel::CATEGORY=>$data->category,
            CustomerModel::BIO_DATA=>json_encode($data->bio_data),
            CustomerModel::ID_DATA=>json_encode($data->id_data),
            CustomerModel::CRP_MODE=>json_encode($data->crp_mode),
            CustomerModel::EMPLOYMENT_DATA=>json_encode($data->employment_data),
            CustomerModel::KIN_DATA=>json_encode($data->kin_data),
            CustomerModel::INVESTMENT_PLAN=>json_encode($data->investment_plan),
            CustomerModel::MARKETER=>$data->marketer,
        ],[
            CustomerModel::ACCOUNT_NO=>$this->request->getUrlParams()->account
        ]);
        $this->response->addMessage('Customer profile updated successfully');
        $this->response->jsonResponse(null);
    }
    //get a single customers details
    public function getCustomer(){
        $this->authenticate(Permission::DEFAULT); //Authenticate with permission
        $data = $this->db->get(CustomerModel::DB_TABLE,[
            CustomerModel::ACCOUNT_NO,
            CustomerModel::CATEGORY,
            CustomerModel::BIO_DATA,
            CustomerModel::ID_DATA,
            CustomerModel::CRP_MODE,
            CustomerModel::EMPLOYMENT_DATA,
            CustomerModel::KIN_DATA,
            CustomerModel::INVESTMENT_PLAN,
            CustomerModel::MARKETER,
        ],[
            CustomerModel::ACCOUNT_NO=>$this->request->getUrlParams()->account
        ]);
        if(empty($data)){
            $this->response->addMessage('There is no account/customer record associated with this keyword in our database');
            $this->response->jsonResponse(null,400);
        }
        else{
            $this->response->addMessage('Customer Account: '.$this->request->getUrlParams()->account);
            $this->response->jsonResponse($this->response->NormalizeMysqlArray($data));
        }
    }
    //get customers list
    public function getList(){
        $this->authenticate(Permission::DEFAULT); //Authenticate with permission
        //check if query was sent with the request
        if(!empty($this->request->getUrlParams()->query)){
            $page = $this->request->getUrlParams()->page;
            $rows = 15;
            $offset = ($page * $rows) - $rows;
            $data = $this->db->select(CustomerModel::DB_TABLE,[
                "[>]offices"=>["office"=>"id"]
            ],[
                CustomerModel::DB_TABLE.'.'.CustomerModel::ACCOUNT_NO,
                CustomerModel::DB_TABLE.'.'.CustomerModel::CATEGORY,
                'offices.description',
                'offices.location',
                CustomerModel::DB_TABLE.'.'.CustomerModel::BIO_DATA,
                CustomerModel::DB_TABLE.'.'.CustomerModel::MARKETER,
                CustomerModel::DB_TABLE.'.'.CustomerModel::DATE,
            ],[
                "MATCH" => [
                    "columns" => [
                        CustomerModel::DB_TABLE.'.'.CustomerModel::ACCOUNT_NO,
                        CustomerModel::DB_TABLE.'.'.CustomerModel::CATEGORY,
                        CustomerModel::DB_TABLE.'.'.CustomerModel::MARKETER,
                    ],
                    "keyword" => $this->request->getUrlParams()->query,
             
                    // [optional] Search mode
                    "mode" => "natural"
                ],
                'ORDER'=>CustomerModel::DB_TABLE.'.'.CustomerModel::ACCOUNT_NO,
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
            $data = $this->db->select(CustomerModel::DB_TABLE,[
                "[>]offices"=>["office"=>"id"]
            ],[
                CustomerModel::DB_TABLE.'.'.CustomerModel::ACCOUNT_NO,
                CustomerModel::DB_TABLE.'.'.CustomerModel::CATEGORY,
                'offices.description',
                'offices.location',
                CustomerModel::DB_TABLE.'.'.CustomerModel::BIO_DATA,
                CustomerModel::DB_TABLE.'.'.CustomerModel::MARKETER,
                CustomerModel::DB_TABLE.'.'.CustomerModel::DATE,
            ],[
                CustomerModel::DB_TABLE.'.'.CustomerModel::OFFICE=>Session::get('office'),
                'ORDER'=>CustomerModel::DB_TABLE.'.'.CustomerModel::ACCOUNT_NO,
                'LIMIT'=>[$offset,$rows]
            ]);
            if(empty($data)){
                $this->response->addMessage('Customers record is empty, add some new customers and see them appear here');
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

    //get total customers
    public function getCentralTotal($where){
        //check if query was sent with the request
        unset($where['LIMIT']);
        $data_count = $this->db->db->count(CustomerModel::DB_TABLE,'*',$where);
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
                            CustomerModel::DB_TABLE.'.'.CustomerModel::ACCOUNT_NO,
                            CustomerModel::DB_TABLE.'.'.CustomerModel::CATEGORY,
                            CustomerModel::DB_TABLE.'.'.CustomerModel::MARKETER,
                        ],
                        "keyword" => $this->request->getUrlParams()->query,
                 
                        // [optional] Search mode
                        "mode" => "natural"
                    ],
                    'ORDER'=>CustomerModel::DB_TABLE.'.'.CustomerModel::ACCOUNT_NO,
                    'LIMIT'=>[$offset,$rows]
                ];
                
            }
            else{
                $where = [
                    'ORDER'=>CustomerModel::DB_TABLE.'.'.CustomerModel::ACCOUNT_NO,
                    'LIMIT'=>[$offset,$rows]
                ];
            }
        }
        else{
            if(!empty($this->request->getUrlParams()->query)){
                $where = [
                    "MATCH" => [
                        "columns" => [
                            CustomerModel::DB_TABLE.'.'.CustomerModel::ACCOUNT_NO,
                            CustomerModel::DB_TABLE.'.'.CustomerModel::CATEGORY,
                            CustomerModel::DB_TABLE.'.'.CustomerModel::MARKETER,
                        ],
                        "keyword" => $this->request->getUrlParams()->query,
                 
                        // [optional] Search mode
                        "mode" => "natural"
                    ],
                    'AND'=>[CustomerModel::OFFICE=>$this->request->getUrlParams()->office],
                    'ORDER'=>CustomerModel::DB_TABLE.'.'.CustomerModel::ACCOUNT_NO,
                    'LIMIT'=>[$offset,$rows]
                ];
            }
            else{
                $where = [
                    'AND'=>[CustomerModel::OFFICE=>$this->request->getUrlParams()->office],
                    'ORDER'=>CustomerModel::DB_TABLE.'.'.CustomerModel::ACCOUNT_NO,
                    'LIMIT'=>[$offset,$rows]
                ];
            }
        }
        //fetch customers with $where clause
        $data = $this->db->select(CustomerModel::DB_TABLE,[
            "[>]offices"=>["office"=>"id"]
        ],[
            CustomerModel::DB_TABLE.'.'.CustomerModel::ACCOUNT_NO,
            CustomerModel::DB_TABLE.'.'.CustomerModel::CATEGORY,
            'offices.description',
            'offices.location',
            CustomerModel::DB_TABLE.'.'.CustomerModel::BIO_DATA,
            CustomerModel::DB_TABLE.'.'.CustomerModel::MARKETER,
            CustomerModel::DB_TABLE.'.'.CustomerModel::DATE,
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