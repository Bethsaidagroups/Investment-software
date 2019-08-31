<?php
/**
 * Email service 
 */
namespace includes\utilities;

use libs\PHPMailer\{PHPMailer};

 class EmailService{

    private $handle;

    private $sender_mail = "no_reply@bethsaidagroups.org";

    private $sender_name = "Bethsaida Investment Partners Ltd.";

    private $reply_email = "support@bethsaidaiplimited.com";

    private $cc_email = "mellow.money.mind@gmail.com";

    private $bcc_email = "";

    private $recipient_mail;

    private $data;

    public function __construct($data){
        $this->handle = new PHPMailer();
        $this->data = $data;
    }

    //HTML content
    public function content($data){
        return $content = <<<EOD
        <div class="soa modal-box">
    <div class="modal-content">
        <div id="print-area" style="width: 21cm;
        height: 24.5cm;
        border: 2px dotted rgb(66, 66, 66);
        padding: 5px;
        margin-top: 53px;
        margin-bottom: 20px;
        margin-left: 1cm;
        border-radius: 5px;
        background: #f7f7f7;
        position: absolute;">
            <div class="reciept-head" style="text-align: right;">
                    <img style="width: 150px;
                    height: 150px;
                    border-radius: 50%;" src="https://app.bethsaidagroups.org/contents/images/logo-soa.jpg">
                    <h5 style="font-size: 20px;
                    font-weight: 700;
                    margin-top: 5px;
                    color: #064b28;">TRANSACTION ALERT</h5>
            </div>
            <div class="reciept-sub-head" style=" margin-top: 1cm;">
                <span style="border: 1px solid #058a68;
                background: #058a68;
                color:#f3f3f3;
                padding: 10px;
                float: left;
                font-weight: 700;
                text-transform: uppercase;" class="status $data->status">$data->status</span>

                <span style=" border: 1px solid #07644d;
                background: #a5cac1;
                color:#07644d;
                padding: 10px;
                float: right;
                font-weight: 700;
                text-transform: uppercase;" class="type $data->type">$data->type</span>
            </div>
            <div class="reciept-body" style="margin-top: 0.3cm;">
                <table style="margin-top: 150px; width: 100%; height: auto; border-collapse: collapse; text-align: left;">
                    <tr style="padding: 5px; background: #ffffff">
                        <th style="padding: 5px;">Account No.</th>
                        <td style="text-transform: capitalize; padding: 5px;">$data->account_number</td>
                    </tr>
                    <tr style="padding: 5px; background: #e0e0e0">
                        <th style="padding: 5px;">Account Name</th>
                        <td style="text-transform: capitalize; padding: 5px;">$data->name</td>
                    </tr>
                    <tr style="padding: 5px; background: #ffffff">
                        <th style="padding: 5px;">Category</th>
                        <td style="text-transform: capitalize; padding: 5px;">$data->category</td>
                    </tr>
                    <tr style="padding: 5px; background: #e0e0e0">
                        <th style="padding: 5px;">Channel</th>
                        <td style="text-transform: capitalize; padding: 5px;">$data->channel</td>
                    </tr>
                    <tr style="padding: 5px; background: #ffffff">
                        <th style="padding: 5px;">Date</th>
                        <td style="text-transform: capitalize; padding: 5px;">$data->datetime</td>
                    </tr>
                    <tr style="padding: 5px; background: #e0e0e0">
                        <th style="padding: 5px;">Naration</th>
                        <td style="text-transform: capitalize; padding: 5px;">$data->narration</td>
                    </tr>
                    <tr style="padding: 5px; background: #ffffff">
                        <th style="padding: 5px;">Amount</th>
                        <th style="padding: 5px;">&#8358;$data->amount</th>
                    </tr>
                    <tr style="padding: 5px; background: #e0e0e0">
                        <th style="padding: 5px;">Available Balance</th>
                        <th style="padding: 5px;">&#8358;$data->balance</th>
                    </tr>
                </table>
            </div>
            <div class="reciept-discaliamer" style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 14px;
            text-align: center;
            color: #991717;">
                    <p>*This alert was auto-generated from Bethsaida Investment Partners 
                         and may be considered as evidence of a transaction</p>
                </div>
            <div class="reciept-footer" style="background: #ffffff;
            position: absolute;
            margin-top: 6cm;
            margin-left: 0px;
            float: left;">

                <div class="row" style="float: left;">
                    <div class="col-sm-4 col-md-4 col-lg-4" style="width: 31%;float: left;position: relative; padding: 5px;">
                        <p style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                        font-size: 15px;
                        color: #2e2e2e;
                        font-weight: 600;"><span style="color: #991717;">Head Office: </span> No 5, Idowu Taylor Street, 
                        3rd Floor, Okoi Arikpo House, (N.U.C BUILDING) Victorial Island, Lagos, Nigeria.</p>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4" style="width: 31%;float: left;position: relative; padding: 5px;">
                        <p style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                        font-size: 15px;
                        color: #2e2e2e;
                        font-weight: 600;"><span style="color: #991717;">Magboro Branch Office: </span> No 3, Ahaji Agba Street, Glass House 
                        3rd Floor off Lagos/Ibadan Expressway, Magboro, Ogun-State, Nigeria.</p>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4" style="width: 31%;float: left;position: relative; padding: 5px;">
                        <p style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                        font-size: 15px;
                        color: #2e2e2e;
                        font-weight: 600;"><i class="fa fa-fax"></i> +234 802 263 7689</p>
                        <p style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                        font-size: 15px;
                        color: #2e2e2e;
                        font-weight: 600;"><i class="fa fa-envelope"></i> info@bethsaidaiplimited.com</p>
                        <p style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
                        font-size: 15px;
                        color: #2e2e2e;
                        font-weight: 600;"><i class="fa fa-globe"></i> www.bethsaidaiplimited.com</p>
                    </div>
                </div>
            </div>
EOD;
    }
    
     //Plain Text alternative mail content
     public function alt_content($data){
        return $content = <<<EOD
            TRANSACTION ALERT FROM <<<BETHSAIDA INVESTMENT PARTNERS>>> \r\n
            Status: $data->status \r\n
            Type: $data->type \r\n
            Account No.: $data->account_number \r\n
            Full Name: $data->name \r\n
            Category: $data->category \r\n
            Channel: $data->channel \r\n
            Timestamp: $data->datetime \r\n
            Naration: $data->narration \r\n
            Amount: $data->amount \r\n
            Available Balance: $data->balance \r\n
EOD;
    }

    //send mail method
    public function send(){
        if(is_null($this->data->email)){
            #customer email not set
            return "Customer Email Not Set";
        }
        $this->handle->From = $this->sender_mail;
        $this->handle->FromName = $this->sender_name;
        $this->handle->addAddress($this->data->email);
        $this->handle->addReplyTo($this->reply_email);
        $this->handle->addCC($this->cc_email);
        $this->handle->isHTML(true);
        if($this->data->type == 'credit'){
            $subject = "no_reply BIP Credit Alert";
        }
        else{
            $subject = "no_reply BIP Debit Alert";
        }
        $this->handle->Subject = $subject;
        $this->handle->Body = $this->content($this->data);
        $this->handle->AltBody = $this->alt_content($this->data);

        if($this->handle->send()){
            echo "Mailer Error: " . $this->handle->ErrorInfo;
            exit();
        }
        else{

        }
    }
 }