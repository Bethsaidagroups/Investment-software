<?php
    /**
     * This class handles authentications for restricted pages
     * The  class contains only one method for the authentication
    */
    namespace includes\auth;

    class Authenticate{

        public static function isValidAuth($db, $OAuth){
            //get arguements
            $db = func_get_arg(0);
            $OAuth = (object) func_get_arg(1);
            $token = $OAuth->token;
            $user_id = $OAuth->user_id;
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $user_ip = $_SERVER['REMOTE_ADDR'];

            try{
                $data = $db->get('sessions','*',['token'=>$token]);
                //Check if token exist in database
                if(empty($data)){
                    return 1;
                }
               
                //check if token has timed out and delete it from database
                $last_access = date_create($data["last_access"]);
                $current_date = date_create(date("Y-m-d H:i:s"));
                $date_diff = date_timestamp_get($current_date) - date_timestamp_get($last_access);

                if($date_diff > 1200){
                    //Token has expired (no access in 15 minutes), delete token from database
                    $db->delete('sessions',['token'=>$token]); //sql to delete token from database
                    return 2;
                }
                //check if User agent is the same
                if ((strcasecmp($user_agent, $data['user_agent']) != 0)){
                    //user agent is not the same
                    return 1;
                }
                //check if user id correlates
                if(!($data['user_id'] === $user_id)){
                    //user id does not correlate
                    return 1;
                }


                //From here, user has a valid access token update session manager table
                $db->update('sessions',[
                    'last_access'=>date("Y-m-d H:i:s"),
                ], [
                    'token'=>$token
                ]);//sql to fectch token info from database

                return 0;
            }
            catch(PDOException $e){
                 die($e);
                return 2;
            }

        }
    }
?>