<?php
/**
 * Handles Request processes
 */
namespace includes\HttpObject;

 use includes\HttpObject\Request;
 use includes\HttpObject\Response;

 class RequestProcessor{

    private $req;

    public function __construct(Request $request){
        $this->req = $request;
    }

    public function classController($classname, $method=null, $params=null){
        $obj = new $classname();
        $obj->request = $this->req;
        $obj->response = new Response();
        if(is_null($params)){
            $obj->{$method}() or $obj->default();
        }
        else{
            $obj->{$method}($params) or $obj->default($params);
        }
    }

    public function functionController($file_path, $func_name, $params=null){
        require $file_path;
        if(is_null($params)){
            call_user_func($func_name, array('request'=>$this->req, 'response'=>new Response())) or 
            die('function does not exist in the required file');
        }
        else{
            call_user_func($func_name, array('request'=>$this->req, 'response'=>new Response()),$params) or 
            die('function does not exist in the required file');
        }
    }
 }