<?php
/**
 * The customer controller class
 * @extends:abstract\controller
 */

namespace controllers;

set_time_limit(0);

 use includes\base\Controller;
 use includes\database\models\Option;
 use includes\database\models\Customer as CustomerModel;
 use includes\database\models\SavingsAccount;
 use includes\database\models\Login;
 use includes\database\models\UserType;
 use includes\utilities\SmsService;
 use includes\auth\{Session,Permission};
 
 class MessageCronTask extends Controller{

    private $db;

    private $today;

    public function __construct(){
        parent::__construct();
        $this->db = $GLOBALS['db'];
        $this->today = date("m-d");
    }

    //Get all customers
    public function get_customers(){
        return $this->db->select(CustomerModel::DB_TABLE,
        [
            CustomerModel::ACCOUNT_NO,
            CustomerModel::CATEGORY,
            CustomerModel::BIO_DATA,
            CustomerModel::ID_DATA,
            CustomerModel::CRP_MODE,
            CustomerModel::EMPLOYMENT_DATA,
            CustomerModel::KIN_DATA,
            CustomerModel::MARKETER,
        ]);
    }

    //Run Cron Task
    public function run_task(){
        $this->send_birthday_wishes();
        $this->send_christmas_wishes();
        $this->send_new_month_wishes();
        $this->send_new_year_wishes();
    }
    //Birthday message cron task
    public function send_birthday_wishes(){
        $customers = $this->get_customers();
        $sms = new SmsService();
        foreach ($customers as $key => $value) {
            $bio_data = json_decode($value['bio_data'],true);
            if(!empty($bio_data['birthday'])){
                $today = \DateTime::createFromFormat('m-d', $this->today);
                $birthday = \DateTime::createFromFormat('m-d', explode('-',$bio_data['birthday'],2)[1]);
                if($today->format('md') == $birthday->format('md')){
                    //Send Birthday wishes
                    if(!empty($bio_data['mobile1'])){
                        $sms->sendBirthdayMessage($bio_data['mobile1']);
                    }
                }
            }
        }
    }

    //New month message cron task
    public function send_new_month_wishes(){
        $customers = $this->get_customers();
        $sms = new SmsService();
        if((date('j') === '1') && (date('n') !== '1')){
            foreach ($customers as $key => $value) {
                $bio_data = json_decode($value['bio_data']);
                //Send New Month wishes
                if(!empty($bio_data['mobile1'])){
                    $sms->sendNewMonthMessage($bio_data['mobile1']);
                }
            }
        }
    }

    //Christmas message cron task
    public function send_christmas_wishes(){
        $customers = $this->get_customers();
        $sms = new SmsService();
        if((date('j') === '25') && (date('n') === '12')){
            foreach ($customers as $key => $value) {
                $bio_data = json_decode($value['bio_data']);
                //Send Christmas wishes
                if(!empty($bio_data['mobile1'])){
                    $sms->sendChristmasMessage($bio_data['mobile1']);
                }
            }
        }
    }

    //New Year message cron task
    public function send_new_year_wishes(){
        $customers = $this->get_customers();
        $sms = new SmsService();
        if((date('j') === '1') && (date('n') === '1')){
            foreach ($customers as $key => $value) {
                $bio_data = json_decode($value['bio_data']);
                //Send new year wishes
                if(!empty($bio_data['mobile1'])){
                    $sms->sendNewYearMessage($bio_data['mobile1']);
                }
            }
        }
    }
 }