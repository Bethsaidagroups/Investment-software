<?php
/**
 * Class SavingsAccount
 */

 namespace includes\BasicTransactions;

 use includes\auth\{Session,Permission};
 use includes\database\models\{AccountTransaction, SavingsAccount as SavingsAccountModel};
 use includes\BasicTransactions\BaseClasses\BaseTransaction;

 class SavingsAccount extends BaseTransaction{

    protected $db;
    
    public $account_no;

    public function __construct($db, $account_no=null){
        parent::__construct($db, AccountTransaction::$categories['savings']);
        $this->db = $db;
        $this->account_no = $account_no;
    }

    /**
     * @method: getBalance
     * @param: [account_no]
     */
    public function getBalance($account_no=null){
        if(is_null($account_no)){
            return $this->db->get(SavingsAccountModel::DB_TABLE,SavingsAccountModel::BALANCE,[
                SavingsAccountModel::ACCOUNT_NO=>$this->account_no
            ]);
        }
        else{
            return $this->db->get(SavingsAccountModel::DB_TABLE,SavingsAccountModel::BALANCE,[
                SavingsAccountModel::ACCOUNT_NO=>$account_no
            ]);
        }
    }

    /**
     * @method: isActive
     * @param: [account_no]
     */
    public function isRegistered($account_no=null){
        if(is_null($account_no)){
            if($this->db->has(SavingsAccountModel::DB_TABLE,[
                'AND'=>[
                    SavingsAccountModel::ACCOUNT_NO=>$this->account_no,
                ]
            ])){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            if($this->db->has(SavingsAccountModel::DB_TABLE,[
                'AND'=>[
                    SavingsAccountModel::ACCOUNT_NO=>$account_no,
                ]
            ])){
                return true;
            }
            else{
                return false;
            }
        }
    }

    /**
     * @method: isActive
     * @param: [account_no]
     */
    public function isActive($account_no=null){
        if(is_null($account_no)){
            if($this->db->has(SavingsAccountModel::DB_TABLE,[
                'AND'=>[
                    SavingsAccountModel::ACCOUNT_NO=>$this->account_no,
                    SavingsAccountModel::STATUS=>SavingsAccountModel::$statuses['active']
                ]
            ])){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            if($this->db->has(SavingsAccountModel::DB_TABLE,[
                'AND'=>[
                    SavingsAccountModel::ACCOUNT_NO=>$account_no,
                    SavingsAccountModel::STATUS=>SavingsAccountModel::$statuses['active']
                ]
            ])){
                return true;
            }
            else{
                return false;
            }
        }
    }

    /**
     * @method: makeWithdrawal
     * @param: [amount, meta, callback]
     */
    public function makeWithdrawal($amount, $meta, $callback){ 
        if($this->isActive()){
            if($this->getBalance() >= $amount){
                $new_balance = $this->getBalance() - abs($amount);
                $this->db->update(SavingsAccountModel::DB_TABLE,[
                    SavingsAccountModel::BALANCE=>$new_balance
                ],[
                    SavingsAccountModel::ACCOUNT_NO=>$this->account_no
                ]);
                //Add transaction to database
                $meta_value = json_decode($meta['meta'],true);
                $meta_value["balance"] = $new_balance;
                $meta['meta'] = \json_encode($meta_value);
                $payload = [
                    AccountTransaction::ACCOUNT_NO=>$this->account_no,
                    AccountTransaction::CATEGORY=>AccountTransaction::$categories['savings'],
                    AccountTransaction::TYPE=>AccountTransaction::$types['debit'],
                    AccountTransaction::AMOUNT=>$amount,
                    AccountTransaction::CHANNEL=>AccountTransaction::$channels[$meta['channel']],
                    AccountTransaction::AUTHORIZED_BY=>$meta['authorized_by'],
                    AccountTransaction::STATUS=>AccountTransaction::$statuses['completed'],
                    AccountTransaction::OFFICE=>$meta['office'],
                    AccountTransaction::META=>$meta['meta'],
                    AccountTransaction::DATETIME=>$meta['datetime']
                ];
                $this->registerTransaction($payload);
                //initiate callback function
                @call_user_func($callback);
                return true;
            }
        }
        else{
            return false;
        }
    }
    /**
     * @method: comfirmWithdrawal
     * @param: [transaction_id, meta(nullable), callback]
     */
    public function confirmWithdrawal($transaction_id, $callback){
        //get transaction from database
        $data = $this->getSingleTransaction([AccountTransaction::ID=>$transaction_id]);
        $authorized_by = json_decode($data['authorized_by'],true);
        $authorized_by['final'] = Session::get('username');
        if($this->isActive($data['account_no'])){
            if($this->getBalance($data['account_no']) >= $data['amount']){
                $new_balance = $this->getBalance($data['account_no']) - abs($data['amount']);
                $this->db->update(SavingsAccountModel::DB_TABLE,[
                    SavingsAccountModel::BALANCE=>$new_balance
                ],[
                    SavingsAccountModel::ACCOUNT_NO=>$data['account_no']
                ]);
                //update transaction status
                $meta_value = json_decode($data['meta_data'],true);
                $meta_value["balance"] = $new_balance;

                $this->db->update(AccountTransaction::DB_TABLE,[
                    AccountTransaction::STATUS=>AccountTransaction::$statuses['completed'],
                    AccountTransaction::AUTHORIZED_BY=>json_encode($authorized_by),
                    AccountTransaction::META=>json_encode($meta_value)
                ],[
                    AccountTransaction::ID=>$transaction_id
                ]);
                //initiate callback
                @call_user_func($callback);
                return true;
            }
        }
        else{
            return false;
        }
    }

    /**
     * @method: makeDeposit
     * @param: [amount, meta, callback]
     */
    public function makeDeposit($amount, $meta, $callback){ 
        if($this->isActive()){
                $new_balance = $this->getBalance() + abs($amount);
                $this->db->update(SavingsAccountModel::DB_TABLE,[
                    SavingsAccountModel::BALANCE=>$new_balance
                ],[
                    SavingsAccountModel::ACCOUNT_NO=>$this->account_no
                ]);
                //Add transaction to database
                $meta_value = json_decode($meta['meta'],true);
                $meta_value["balance"] = $new_balance;
                $meta['meta'] = json_encode($meta_value);
                $payload = [
                    AccountTransaction::ACCOUNT_NO=>$this->account_no,
                    AccountTransaction::CATEGORY=>AccountTransaction::$categories['savings'],
                    AccountTransaction::TYPE=>AccountTransaction::$types['credit'],
                    AccountTransaction::AMOUNT=>$amount,
                    AccountTransaction::CHANNEL=>AccountTransaction::$channels[$meta['channel']],
                    AccountTransaction::AUTHORIZED_BY=>$meta['authorized_by'],
                    AccountTransaction::STATUS=>AccountTransaction::$statuses['completed'],
                    AccountTransaction::OFFICE=>$meta['office'],
                    AccountTransaction::META=>$meta['meta'],
                    AccountTransaction::DATETIME=>$meta['datetime']
                ];
                $this->registerTransaction($payload);
                //initiate callback function
                @call_user_func($callback);
                return true;
        }
        else{
            return false;
        }
    }
    /**
     * @method: comfirmWithdrawal
     * @param: [transaction_id, meta(nullable), callback]
     */
    public function confirmDeposit($transaction_id, $callback){
        //get transaction from database
        $data = $this->getSingleTransaction(['id'=>$transaction_id]);
        $authorized_by = json_decode($data['authorized_by'],true);
        $authorized_by['final'] = Session::get('username');
        if($this->isActive($data['account_no'])){
                $new_balance = $this->getBalance($data['account_no']) + abs($data['amount']);
                $this->db->update(SavingsAccountModel::DB_TABLE,[
                    SavingsAccountModel::BALANCE=>$new_balance
                ],[
                    SavingsAccountModel::ACCOUNT_NO=>$data['account_no']
                ]);
                //update transaction status
                $meta_value = json_decode($data['meta_data'],true);
                $meta_value["balance"] = $new_balance;

                $this->db->update(AccountTransaction::DB_TABLE,[
                    AccountTransaction::STATUS=>AccountTransaction::$statuses['completed'],
                    AccountTransaction::AUTHORIZED_BY=>json_encode($authorized_by),
                    AccountTransaction::META=>json_encode($meta_value)
                ],[
                    AccountTransaction::ID=>$transaction_id
                ]);
                //initiate callback
                @call_user_func($callback);
                return true;
            }
        else{
            return false;
        }
    }
 }