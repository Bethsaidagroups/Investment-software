<?php
/**
 * Abstract Controller class
 */
 namespace includes\base;
 
 use includes\auth\{Session,Authenticate,Permission};

 class Controller{

    public $request;

    public $response;

    public function __construct(){
      // Empty constructor
    }

    public function default(){
      //
    }

    public function authenticate($permits=Permission::DEFAULT){
      $token = Session::get('token');
      $user_id = Session::get('user_id');
      $state = Authenticate::isValidAuth($GLOBALS['db'],array("token" => $token, "user_id" => $user_id));
      //check through returned state and handle appropriately
      if($state === 0){
         //OAuth is completed access variables/token is valid, check for valid permission
         if(Permission::has_permission(Session::get('user_type'),$permits)){
            return;
         }
         else{
            $this->response->addMessage("You don't have the right priviledge to perform this operation");
            $this->response->jsonResponse(null,401);
            exit();
         }
      }
      elseif($state === 1){
         //OAuth verication is completed and credential is not valid
         $this->response->addMessage('Invalid session token, please try login again');
         $this->response->jsonResponse(null,403);
         exit();
      }
      elseif($state === 2){
         //OAuth verification is completed and credential has expired
         $this->response->addMessage('Session expired after 15 minutes of inactivity');
         $this->response->jsonResponse(null,403);
         exit();
      }
      else{
         //OAuth verication is completed and credential is not valid
         $this->response->addMessage('Unknown internal error, please try login again');
         $this->response->jsonResponse(null,403);
         exit();
      }
    }
 }