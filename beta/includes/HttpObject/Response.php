<?php
/**
 * Handles Http Response
 */

 namespace includes\HttpObject;
 
 class Response{
    private $message = null;

    private $meta = null;

    public function __construct(){

    }

    public function JsonResponse($dump_data=null, $resp_code=200){
        $response = array();
        $data['status'] = $resp_code;
        $data['message'] = $this->message;
        $data['meta'] = $this->meta;
        $data['data'] = $dump_data;
        $this->message = null; //remove last message string
        header('Content-Type:application/json');
        http_response_code($resp_code);
        echo json_encode($data);
        exit();
    }

    public function addMessage($msg){
        $this->message = $msg;
    }

    public function addMeta($metadata){
        $this->meta = $metadata;
    }
    public function normalizeMysqlArray($data, $returIndexedArray=false){
        $assoc_array = array();
        $indexed_array = array();
        //check if array is two dimensional
        if(empty($data)) return null ;
        if(count($data) == count($data, COUNT_RECURSIVE)){
            foreach($data as $key => $value){
                if(is_numeric($key)){
                    if($this->isJson($value)){
                        $indexed_array[] = json_decode($value);
                    }
                    else{
                        $indexed_array[] = $value;
                    }
                }
                else{
                    if($this->isJson($value)){
                        $assoc_array[$key] = json_decode($value);
                    }
                    else{
                        $assoc_array[$key] = $value;
                    }
                }
            }
        }
        else{
            foreach($data as $col => $rows){
                foreach($rows as $key => $value){
                    if(is_numeric($key)){
                        if($this->isJson($value)){
                            $indexed_array[$col][] = json_decode($value);
                        }
                        else{
                            $indexed_array[$col][] = $value;
                        }
                    }
                    else{
                        if($this->isJson($value)){
                            $assoc_array[$col][$key] = json_decode($value);
                        }
                        else{
                            $assoc_array[$col][$key] = $value;
                        }
                    }
                }
            }
        }
        if($returIndexedArray){
            return $indexed_array;
        }
        else{
            return $assoc_array;
        }
    }

    function isJson($string){
        return((is_string($string) && substr($string,0,1) === '{' && (is_object(json_decode($string)) || 
        is_array(json_decode($string))))) ? true : false;
    }
 }