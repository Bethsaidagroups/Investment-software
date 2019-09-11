<?php
//The Account Transaction database object model

namespace includes\database\models;

class LoanRecord{

    const DB_TABLE = 'loan_record';

    const ID = 'id';

    const ACCOUNT_NO = 'account_no';

    const AMOUNT = 'amount';

    const OFFICE = 'office';

    const AUTHORIZED_BY = 'authorized_by';

    const STATUS = 'status';

    const DATE = "date";


    public static $statuses = array('paid'=>'paid','unpaid'=>'unpaid');

    public function __construct(){

    }

    public static function get_date(){
        return date("Y-m-d");
    }
}
