<?php
/**
 * The user controller class
 * @extends:abstract\controller
 */
namespace controllers;

use includes\base\Controller;
use includes\database\models\Login;
use includes\database\models\UserType;
use includes\utilities\SmsService;
use includes\auth\{Session,Permission};

class User extends Controller{

    private $db;

    public function __construct(){
        parent::__construct();
        $this->db = $GLOBALS['db'];
    }

    public function getProfile(){
        $this->authenticate(Permission::DEFAULT); //Authenticate with permission
        $data = $this->db->get(Login::DB_TABLE,[
            "[>]".UserType::DB_TABLE=>[Login::USER_TYPE=>UserType::ID],
            "[>]offices"=>[Login::OFFICE=>'id']
        ],[
            Login::DB_TABLE.'.'.Login::USERNAME,
            UserType::DB_TABLE.'.'.UserType::NAME,
            Login::DB_TABLE.'.'.Login::ACCESS,
            Login::DB_TABLE.'.'.Login::META,
            'offices.description',
            'offices.location',
            Login::DB_TABLE.'.'.Login::LAST_LOGIN
        ],[
            Login::USERNAME=>$this->request->getUrlParams()->username
        ]);
        if(empty($data)){
            $this->response->addMessage('There is no record associated to this username');
            $this->response->jsonResponse(null,400);
        }
        else{
            $this->response->jsonResponse($this->response->NormalizeMysqlArray($data));
        }
    }

    public function updatePassword(){
        $this->authenticate(Permission::DEFAULT); //Authenticate with permission
        $data = $this->request->getPost();
        $hash = $this->db->get(Login::DB_TABLE,'password',[
            Login::USERNAME=>$this->request->getUrlParams()->username
        ]);
        if(password_verify($data->old,$hash)){
            //old password is correct
            //check if new password is the same as old password
            if(strcmp($data->old,$data->new) === 0){
                //old and new password are the same
                $this->response->addMessage('Your new password must be different from your current password');
                $this->response->jsonResponse(null,400);
            }
            else{
                $this->db->update(Login::DB_TABLE,[
                    Login::PASSWORD=>password_hash($data->new,PASSWORD_DEFAULT)
                ],[
                    Login::USERNAME=>$this->request->getUrlParams()->username
                ]);
                $this->response->addMessage('Your password has been changed successfully. Your new Password is required on your next login');
                $this->response->jsonResponse();
            }
        }
        else{
            //old password is wrong
            $this->response->addMessage('Incorrect old password');
            $this->response->jsonResponse(null,400);
        }
    }
}