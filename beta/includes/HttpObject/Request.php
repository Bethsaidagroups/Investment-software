<?php
/**
 * Request Handler class
 */

 namespace includes\HttpObject;

 class Request{

    private $params = array();

    private $post_data = array();

    private $get_data = array();

    private $headers;

    public function __construct($params=null){
        $this->headers = getallheaders();
        $this->params = $params;
        $this->post_data = $_POST;
        $this->get_data = $_GET;

        //type cast params to object
        (object)$this->params;
    }

    public function getData($returAsObject=true){
        if($returAsObject){
            return (object)$this->get_data;
        }
        else{
            return $this->get_data;
        }
    }

    public function getPost($returAsObject=true){
        $content_type = $this->headers['Content-Type'];
        if(strcasecmp($content_type, 'application/json;charset=utf-8') === 0){
            $data = json_decode(json_decode(file_get_contents('php://input'), true));
        }
        
        if($returAsObject){
            return (object)$data;
        }
        else{
            return $data;
        }
    }

    public function getUrlParams($returAsObject=true){
        if($returAsObject){
            return (object)$this->params;
        }
        else{
            return $this->params;
        }
    }

 }