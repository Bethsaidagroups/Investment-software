<?php
//The User Type database object model

namespace includes\database\models;

class UserType{

    const DB_TABLE = 'users_type';

    const ID = 'id';

    const NAME = 'name';

    public static $types = [
        1=>'Administrator',
        2=>'Managing Director',
        3=>'Secretary',
        4=>'Accountant',
        5=>'Investment',
        8=>'Marketer'
    ];
}