<?php
    /**
     * This static class contains methods to manipulate JSON
    */

    namespace utilities;

    class JSONHandler{
        private static function isJson($str){
            json_decode($str);
            return (json_last_error() == JSON_ERROR_NONE && !is_numeric($str));
        }
        public static function arrayToJSON($array){
            if(empty($array)){
                //array is empty
                return false;
            }
            if (count($array) == count($array, COUNT_RECURSIVE)) {
                //Array has one dimension
                $counter = 1;
                $json_str = '{';
                foreach($array as $key => $value){
                    if(!is_numeric($key)){
                        if($counter != count($array)-1){
                            self::isJson($value) ? $json_str = $json_str . '"' . $key . '":' . $value . ',' : $json_str = $json_str . '"' . $key . '":"' . $value . '",';
                            //$json_str = $json_str . '"' . $key . '":"' . $value . '",';
                        }
                        else{
                            //$json_str = $json_str . '"' . $key . '":"' . $value . '"}';
                            self::isJson($value) ? $json_str = $json_str . '"' . $key . '":' . $value . '}' : $json_str = $json_str . '"' . $key . '":"' . $value . '"}';
                        }  
                    }
                    $counter++;
                }
                return $json_str;
            }
            else{
                //Array has two dimension
                $json_str = '{';
                for($i=0; $i < count($array); $i++){
                    $index = $i + 1;
                    $counter = 1;
                    $json_str = $json_str . '"' . $index . '":{'; 
                    foreach($array[$i] as $key => $value){
                        if(!is_numeric($key)){
                            if($counter != count($array[$i])-1){
                                self::isJson($value) ? $json_str = $json_str . '"' . $key . '":' . $value . ',' : $json_str = $json_str . '"' . $key . '":"' . $value . '",';
                                //$json_str = $json_str . '"' . $key . '":"' . $value . '",';
                            }
                            else{
                                //$json_str = $json_str . '"' . $key . '":"' . $value . '"}';
                                self::isJson($value) ? $json_str = $json_str . '"' . $key . '":' . $value . '}' : $json_str = $json_str . '"' . $key . '":"' . $value . '"}';
                            }  
                        }
                        $counter++;
                    }
                    if($i != count($array)-1){
                        $json_str = $json_str . ",";
                    }
                    else{
                        $json_str = $json_str . "}";
                    }
                }
                return $json_str;
            }
        }
    }
?>