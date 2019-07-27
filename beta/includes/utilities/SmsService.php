<?php
    //The SMS service for the sending sms using the Bulk SMS Nigeria API

    namespace includes\utilities;

    class SmsService{
        //Define class properties
        private $base_url = "https://www.bulksmsnigeria.com/api/v1/sms/create";

        private $api_key = "r2fd0DK3uULM8mjRAfKrrrpxskp0UxD0gsbIDYjcvcUkuhcfVTx10AO8pdeM";

        private $sender_id = "BETHSAIDA";

        private $reciepient = "";

        private $message = "";

        private $curl = null;

        public function __construct($reciepient, $message=null){
            $this->reciepient = $reciepient;
            $this->message = $message;
            //initialize c_URL parameters
            if(!is_null($message)){
                $this->init();
            }
        }

        private function init(){
            //build querry string
            $query_str = "api_token=$this->api_key&from=$this->sender_id&to=$this->reciepient&body=$this->message";
            //die("$this->base_url?$query_str");
            $this->curl = curl_init();
            curl_setopt_array($this->curl, array(
                CURLOPT_URL => $this->base_url,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $query_str,
                CURLOPT_RETURNTRANSFER => true
                ));
        }

        public function send(){
            //send message.
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false); //quick but dirty fix to continue connection even when cert cannot be verified
            $response = curl_exec($this->curl);
            $err = curl_error($this->curl);
            //echo $err;
            curl_close($this->curl);
        }


        public function sendNewAccountMessage(array $data){
            $this->message = 'We are happy to notify you... '.$data['surname'].' '.$data['first_name'].
            'that your account...'.$data['account_number'].' with Bethsaida Ivestment Patners has been opened successfully';
            $this->init();
            $this->send();
        }

        public function sendNewCreditMessage(array $data){
            $this->message = "BIP-Credit>>>Amt:NGN ".$data['amount']." Acc:" .$data['account_number']." Chn:".$data['channel']. 
            " Nar: ".$data['narration']." Date:".$data['datetime'] . " PMBal:NGN ".$data['balance'];
            $this->init();
            $this->send();
        }

        public function sendNewDebitMessage(array $data){
            $this->message = "BIP-Debit>>>Amt:NGN ".$data['amount']." Acc:" .$data['account_number']." Chn:".$data['channel']. 
            " Nar: ".$data['narration']." Date:".$data['datetime'] . " PMBal:NGN ".$data['balance'];
            $this->init();
            $this->send();
        }
    }
?>