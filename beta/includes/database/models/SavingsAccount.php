<?php
//The Savings Account database object model

namespace includes\database\models;

class SavingsAccount{
    const DB_TABLE = 'savings_account';

    const ID = 'id';

    const ACCOUNT_NO = 'account_no';

    const PLAN = 'plan';

    const BALANCE = 'balance';

    const OFFICE = 'office';

    const STATUS = 'status';

    public static $statuses = array('active'=>'active','inactive'=>'inactive');
    
    public function __construct(){

    }
}
