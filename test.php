<html>
  <?php
    
date_default_timezone_set("Africa/Lagos");
$date = date('Y-m-d H:i:s');
$date = strtotime($date);
echo date('H', $date);
  ?>
<body>
    <script>
var xhr = new XMLHttpRequest();
///var url = "http://localhost/laser/modules/investment/api/invoice/get/2";
xhr.open("POST", url, true);
xhr.setRequestHeader("Content-Type", "application/json");
xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
        //var json = JSON.parse(xhr.responseText);
        //console.log(json.email + ", " + json.password);
        document.write(xhr.responseText)
    }
};
var data = '{"first_name" : "Emmanuel","last_name" : "James","email" : "Emmajam2@gmail.com","password" : "emmasking","phone" : "09087576857","gender" : "male"}';
var data2 = '{"auth":{"token" : "cbc7d46e850f2b3d800e8999c369035a46412b1a", "email" : "ope@gmail.com" , "type" : "user"},"first_name" : "Samuel Ojogbon","last_name" : "Akosile", "phone" : "09087576857","gender" : "male", "bank_info":{"account_name" : "Akosile Opeyemi", "account_no" : "0987556345", "bank_no" : "Keystone Bank"}}';
var data3 = '{"auth":{"token" : "44575fafbe42b4d434a150f701cc2493485887ca", "email" : "ope@gmail.com" , "type" : "user"}, "password":"samuel"}';
var data4 = '{"email":"ope@gmail.com","password":"samuelopex"}';
var data1 = '{"auth":{"token" : "bc6375ab4b9d638354879f63a3248bed17d509c8", "email" : "ope@gmail.com" , "type" : "user"},"amount":"1500","ref":"bfZ741547986921", "service":{"id" : "5", "amount" : "10000", "details":{"address":"iddjdjdididie8r48ryur7yu", "date":"10-10-2018"}}}';
var data5 = '{"auth":{"token" : "bc6375ab4b9d638354879f63a3248bed17d509c8", "email" : "ope@gmail.com" , "type" : "user"}, "service":{"id" : "2","amount":"2500", "details":{"site_phone":"07065551148","date":"10-10-2018","value":"2500"}}}';
var data6 = '{"action" : "re-verify", "activity":"verification", "type":"user", "email" : "opeyemiakosile@gmail.com", "otp":"54088983"}';

var data7= '{"id":"", "username":"opexzy", "password":"samuelopex", "user_type":"1", "access":"1", "office":"1", "meta":{"first_name":"Akosile","last_name":"Samuel","gender":"male","mobile":"09069495076"}, "last_login":""}'
var data8 = '{"id":"", "location":"Ibadan", "type":"branch", "description":"Bethsaida office in Ibadan"}';
var data9 = '{"id":"", "account_no":"", "category":"individual", "office":"1", "bio_data":{}, "id_data":{}, "crp_mode":"Mail", "employment_data":{},"kin_data":{}, "registered_by":"opeyemi", "marketer":"opexzy","registration_date":"", "plan":{}}';
var data10 = '{"plan":"month", "plan_no":"7", "rate":"15.5"}';
xhr.send(data10);

var data_obj = {
  "event": "charge.success",
  "data": {
    "id": 84,
    "domain": "test",
    "status": "success",
    "reference": "iFuQO1548067190",
    "amount": 50000,
    "message": null,
    "gateway_response": "Approved",
    "paid_at": "2018-12-20T15:00:06.000Z",
    "created_at": "2018-12-20T15:00:05.000Z",
    "channel": "card",
    "currency": "NGN",
    "ip_address": null,
    "metadata": {
      "custome_fields": [
        {
          "display_name": "Wallet ID",
          "variable_name": "wallet_id",
          "value": "1-12097"
        }
      ]
    },
    "log": null,
    "fees": 750,
    "fees_split": null,
    "authorization": {
      "authorization_code": "AUTH_9246d0h9kl",
      "bin": "408408",
      "last4": "4081",
      "exp_month": "12",
      "exp_year": "2020",
      "channel": "card",
      "card_type": "visa DEBIT",
      "bank": "Test Bank",
      "country_code": "NG",
      "brand": "visa",
      "reusable": true,
      "signature": "SIG_iCw3p0rsG7LUiQwlsR3t"
    },
    "customer": {
      "id": 4670376,
      "first_name": "Asample",
      "last_name": "Personpaying",
      "email": "asam@ple.com",
      "customer_code": "CUS_00w4ath3e2ukno4",
      "phone": "",
      "metadata": null,
      "risk_action": "default"
    },
    "plan": {
      "id": 17,
      "name": "A s(i/a)mple plan",
      "plan_code": "PLN_dbam2fwcqkyyfjc",
      "description": "Sample plan for docs",
      "amount": 50000,
      "interval": "hourly",
      "send_invoices": true,
      "send_sms": true,
      "currency": "NGN"
    },
    "subaccount": {},
    "paidAt": "2018-12-20T15:00:06.000Z"
  }
}

    </script>
</body>
<html>