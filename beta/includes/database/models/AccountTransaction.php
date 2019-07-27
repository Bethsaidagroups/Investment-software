<?php
//The Account Transaction database object model

namespace includes\database\models;

class AccountTransaction{

    const DB_TABLE = 'account_transactions';

    const ID = 'id';

    const DATETIME = 'timestamp';

    const ACCOUNT_NO = 'account_no';

    const CATEGORY = 'category';

    const TYPE = 'type';

    const AMOUNT = 'amount';

    const CHANNEL = 'channel';

    const AUTHORIZED_BY = 'authorized_by';

    const STATUS = 'status';

    const OFFICE = 'office';

    const META = 'meta_data';


    public static $types = array('credit'=>'credit','debit'=>'debit');

    public static $statuses = array('pending'=>'pending','completed'=>'completed','declined'=>'declined');

    public static $categories = array('savings'=>'savings');

    public static $channels = array('cash'=>'cash','direct'=>'direct','transfer'=>'transfer','cheque'=>'cheque');

    public function __construct(){

    }

}
