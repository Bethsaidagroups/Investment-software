<?php
//The Customer database object model

namespace includes\database\models;

class Customer{

    const DB_TABLE = 'customers';
    
    const ID = 'id';

    const ACCOUNT_NO = 'account_no';

    const CATEGORY = 'category';

    const OFFICE = 'office';

    const BIO_DATA = 'bio_data';

    const ID_DATA = 'id_data';

    const CRP_MODE = 'crp_mode';

    const EMPLOYMENT_DATA = 'employment_data';

    const KIN_DATA = 'kin_data';

    const REGISTERED_BY = 'registered_by';
    
    const MARKETER = 'marketer';

    const DATE = 'date';

    const REGISTRATION_DATE = 'registration_date';

    public static $categories = array('individual'=>'individual','cooperate'=>'cooperate');

    public static $crp_modes = array('email'=>'email','in_person'=>'in person');

}
