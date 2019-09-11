<?php
/**
 * Base Class BaseTransaction for all account transaction functions
 * @Methods
 * Author:
 */

namespace includes\BasicTransactions\BaseClasses;

use includes\database\models\AccountTransaction;

 class BaseTransaction{

    protected $category;

    protected $db;

    public function __construct($db,$category){
       $this->db = $db;
       $this->category = $category;
    }

    /**
     * @method: registerTransaction
     * @param: payload - transaction payload
     */
    public function registerTransaction($payload,$db=null){
       if(is_null($db)){
         $this->db->insert(AccountTransaction::DB_TABLE,$payload);
       }
       else{
          //Atomic
         $db->insert(AccountTransaction::DB_TABLE,$payload);
       }
    }

    /**
     * @method: registerMultipleTransaction
     * @param: payload - transaction payload
     */
    public function registerMultipleTransaction($payloads){
      foreach($payloads as $payload){
         $this->db->insert(AccountTransaction::DB_TABLE,$payload);
      }
   }

    /**
     * @method: getSingleTransaction
     * @param: filter
     */
    public function getSingleTransaction($filter){
      $data = $this->db->get(AccountTransaction::DB_TABLE,'*',$filter);
      return $data;
   }

    /**
     * Get all the transactions from database
     * @param: all arguments are optional but must come in order
     */
    public function getTransactions(){
      if(func_num_args==0){
         //no arguments was passed to the function
         //treat as default and return all transaction in database
         $data = $this->db->select(AccountTransaction::DB_TABLE,'*');
         return $data;
      }
      else{
         //an array of arguments has been passed, use them to filter output
         $filter = func_get_arg(0);
         $filter['AND'][AccountTransaction::CATEGORY] = $this->category;
         $data = $this->db-select(AccountTransaction::DB_TABLE,'*',$filter);
         return $data;
      }
    }

    public function getCreditTransactions(){
      if(func_num_args==0){
         //no arguments was passed to the function
         //treat as default and return all credit transactions in database
         $data = $this->db->select(AccountTransaction::DB_TABLE,'*',[
            AccountTransaction::TYPE=> AccountTransaction::$types['credit']
         ]);
         return $data;
      }
      else{
         //an array of arguments has been passed, use them to filter output
         $filter = func_get_arg(0);
         $filter['AND'][AccountTransaction::CATEGORY] = $this->category;
         $filter['AND'][AccountTransaction::TYPE] = AccountTransaction::$types['credit'];
         $data = $this->db-select(AccountTransaction::DB_TABLE,'*',$filter);
         return $data;
      }
    }

    public function getDebitTransactions(){
      if(func_num_args==0){
         //no arguments was passed to the function
         //treat as default and return all debit transactions in database
         $data = $this->db->select(AccountTransaction::DB_TABLE,'*',[
            AccountTransaction::TYPE=> AccountTransaction::$types['debit']
         ]);
         return $data;
      }
      else{
         //an array of arguments has been passed, use them to filter output
         $filter = func_get_arg(0);
         $filter['AND'][AccountTransaction::CATEGORY] = $this->category;
         $filter['AND'][AccountTransaction::TYPE] = AccountTransaction::$types['debit'];
         $data = $this->db-select(AccountTransaction::DB_TABLE,'*',$filter);
         return $data;
      }
    }

    public function getTransactionByStatus($status){
      if(func_num_args==0){
         //no arguments was passed to the function
         //treat as default and return all credit transactions in database
         $data = $this->db->select(AccountTransaction::DB_TABLE,'*',[
            AccountTransaction::STATUS=>AccountTransaction::$statuses[$status]
         ]);
         return $data;
      }
      else{
         //an array of arguments has been passed, use them to filter output
         $filter = func_get_arg(0);
         $filter['AND'][AccountTransaction::CATEGORY] = $this->category;
         $filter['AND'][AccountTransaction::STATUS] = AccountTransaction::$statuses[$status];
         $data = $this->db-select(AccountTransaction::DB_TABLE,'*',$filter);
         return $data;
      }
    }
 }
