<?php
//The Options database object model

namespace includes\database\models;

class Option{

    const DB_TABLE = 'options';

    const ID = 'id';

    const NAME = 'name';

    const VALUE = 'value';

    public static $names = array('pointer'=>'pointer');
    
}
