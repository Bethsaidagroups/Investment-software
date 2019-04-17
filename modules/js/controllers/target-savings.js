/**
 * The Fixed angular controller
 */
'use strict';

user.controller("addTargetCtrl", function($scope, $location, httpReq, showAlert){
    //default settings
    $scope.account_no = true;
        //set continue action performed
        $scope.continue = function(){
            httpReq.send('/customer/view/search/accountQQno/' + $scope.target.account_no, null, 'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        var valid = false;
                
                        if(response.data.hasOwnProperty(1) &&  response.data[1].account_no == $scope.target.account_no){
                            showAlert.success('Account No: ' + response.data[1].account_no + ', Name: ' + response.data[1].bio_data.surname +" "+ response.data[1].bio_data.first_name + ' was selected');
                            $scope.account_no = false;
                            $scope.target_details = true;
                        }
                        else{
                            showAlert.warning("This account number does not exist in our customers database")
                        }
                    }
                },
                error: function(response){
                    switch(response.status){
                        case 400:{
                            showAlert.warning("This account number does not exist in our customer database");
                            break;
                        }
                        case 403:{
                            showAlert.danger(response.data.auth + ' | ' + response.data.access);
                            break;
                        }
                        default:{
                            showAlert.warning("unknown internal error, workspace might be empty. Hint: Reload browser window");
                        }
                    }
                }
            });

        }
        //set back action performed
        $scope.back = function(){
            $scope.account_no = true;
            $scope.target_details = false;
        }
        //Preview action performed
        $scope.preview = function(){
            var data = $scope.target
            httpReq.send('/target/preview',JSON.stringify(data),'POST',
                {
                    success: function(response){
                        if(response.status === 200){
                            $scope.invoice = response.data;
                            $scope.target_preview = true;
                        }
                    },
                    error: function(response){
                        switch(response.status){
                            case 400:{
                                showAlert.warning(response.data.error);
                                $scope.target_preview = false;
                                break;
                            }
                            case 403:{
                                showAlert.danger(response.data.auth + ' | ' + response.data.access);
                                break;
                            }
                            default:{
                                showAlert.warning("unknown internal error, workspace might be empty. Hint: Reload browser window");
                            }
                        }
                    }
                });
        }
        //submit button action performed
        $scope.submit = function(){
            if(confirm('By clicking submit, means you have reviewed the target savings details and you are satisfied')){
            
                    //add default values to data
                    var data = new Object();
                    data = $scope.target;
                    data.id = "";
                    data.ref_no = "";
                    data.office = "0";
                    data.registered_by = "";
                    data.timestamp = "";
                    data.status = "";
                    data.roi = "";
                    data.paid_amount = 0;
                    httpReq.send('/target/add',JSON.stringify(data),'POST',
                {
                    success: function(response){
                        if(response.status === 200){
                            showAlert.success(response.data.success);
                            $location.url('/manage-target')
                        }
                    },
                    error: function(response){
                        switch(response.status){
                            case 400:{
                                showAlert.warning(response.data.error);
                                break;
                            }
                            case 403:{
                                showAlert.danger(response.data.auth + ' | ' + response.data.access);
                                break;
                            }
                            default:{
                                showAlert.warning("unknown internal error, workspace might be empty. Hint: Reload browser window");
                            }
                        }
                    }
                });
            }
        }
});

//Fixed Deposit management controller
user.controller("manageTargetCtrl", function($scope, $location, $route, httpReq, showAlert){
    //set default search keys
    var keyParams = {id:'Target Savings Id', refQQno:'Target Savings Ref No',accountQQno:'Savings Account No',office:'Registration Branch',registeredQQby:'Registered By'};
    var data_no = 0;
    $scope.keys = keyParams;
    //Key Change Action performed
    $scope.list = JSON.parse(localStorage.getItem('offices')); //set offices
    $scope.keyChange = function(){
        if($scope.key == 'office'){
            $scope.value = "";
            $scope.value_input = false;
            $scope.value_select = true;
        }
        else{
            $scope.value = "";
            $scope.value_input = true;
            $scope.value_select = false;
            $scope.selected_key = keyParams[$scope.key];
        }
    }
    if($location.search().key && $location.search().value){
        $scope.key = $location.search().key;
        $scope.value = $location.search().value;
        //set appropriate forms
        if($scope.key == 'office'){
            $scope.value_select = true;
        }
        else{
            $scope.value_input = true;
            $scope.selected_key = keyParams[$scope.key];
        }
    }
    //search action performed
    $scope.searchBtn = function(){
        if($scope.key != null && $scope.value != null){
            $location.url('/manage-target?key=' + $scope.key + '&value=' + $scope.value);
        }
        else{
            showAlert.warning("Select correct search parameters");
        }
    }
    //build Parameters
    var page = 1;
    var key = "";
    var value = "";
    var url = '/target/view';
    if($location.search().page){
        page = $location.search().page;
    }
    if($location.search().key && $location.search().value){
        key = $location.search().key;
        value = $location.search().value;
        url = url + '/search/' + key + '/' + value;
    }
    url = url + '/' + page;
    //get Savings accounts from database
    httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.deposits = response.data;
                    $scope.page = page;
                    //check the number of data in response data
                    for(var data in response.data){
                        data_no++;
                    }
                    data_no < 15 ? $scope.show_next = false : $scope.show_next = true;
                    page > 1 ? $scope.show_prev = true : $scope.show_prev = false;
                }
            },
            error: function(response){
                switch(response.status){
                    case 400:{
                        showAlert.warning(response.data);
                        break;
                    }
                    case 403:{
                        showAlert.danger(response.data.auth + ' | ' + response.data.access);
                        break;
                    }
                    default:{
                        showAlert.warning("unknown internal error, workspace might be empty. Hint: Reload browser window");
                    }
                }
            }
        });
    //Edit action performed
    $scope.viewBtn = function(ref_no){
        $location.url('/target-invoice?key=refQQno&value=' + ref_no);
    }
    //cashout btn action performed
    $scope.cashOutBtn = function(index,status){
        $scope.selected_target = new Object;
        $scope.selected_target.id = $scope.deposits[index].id;
        $scope.selected_target.paid_amount = $scope.deposits[index].paid_amount;
        $scope.selected_target.status = $scope.deposits[index].status;
        if($scope.selected_target.status == 'cashed'){

        }
        else if($scope.selected_target.status == 'completed'){
            $scope.selected_target.penalty = '0';
            $scope.penalty = false;
            $scope.msg = '';
        }
        else{
            $scope.msg = 'This target savings is yet to be completed. if you continue penalty charge will apply';
            $scope.penalty = true;
        }
    }
        $scope.comfirmCashOut = function(){
            if($scope.selected_target.status == 'cashed'){
                showAlert.warning("This target savings has been cashed out already");
            }
            else if($scope.selected_target.status == 'completed'){
                cash_out($scope.selected_target.id);
            }
            else{
                cash_out($scope.selected_target.id);
            }
        }

        var cash_out = function(id){
            if(confirm('By clicking submit, means you have reviewed the fix details and you are satisfied')){
                var url = "/target/cashout/" + id;
                var data = JSON.stringify($scope.selected_target);
                httpReq.send(url,data,'POST',
                {
                    success: function(response){
                        if(response.status === 200){
                            showAlert.success("Transaction completed successfully. Sent to Accountant for finalization");
                            $route.reload();
                        }
                    },
                    error: function(response){
                        switch(response.status){
                            case 400:{
                                showAlert.warning(response.data.error);
                                break;
                            }
                            case 403:{
                                showAlert.danger(response.data.auth + ' | ' + response.data.access);
                                break;
                            }
                            default:{
                                showAlert.warning("unknown internal error, workspace might be empty. Hint: Reload browser window");
                            }
                        }
                    }
                });
            }
        }
    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        if($location.search().value && $location.search().key){
            $location.url('/manage-target?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/manage-target?page=' + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().value && $location.search().key){
            $location.url('/manage-target?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/manage-target?page=' + new_page);
        }
    }
});

//Cash Out Fixed Deposit controller
user.controller("cashOutTargetCtrl", function($scope, $location, $route, httpReq, showAlert){
    //get customer from database
    var url = "/target/" + $location.search().id;
    httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.fixed = response.data;

                    if(response.data.status == 'cashed'){
                        showAlert.warning("This deposit has been cashed out already");
                        $location.url('/manage-fixed');
                    }
                    else if(response.data.status == 'completed'){
                        $scope.roi = response.data.roi;
                        $scope.tax = response.data.roi * 10 / 100;
                        $scope.total = parseFloat(response.data.amount) + parseFloat(response.data.roi) - $scope.tax;
                    }
                    else{
                        $scope.tax = '0.00';
                        $scope.roi = '0.00';
                        $scope.total = response.data.amount;
                    }
                }
            },
            error: function(response){
                switch(response.status){
                    case 400:{
                        showAlert.warning(response.data.error);
                        break;
                    }
                    case 403:{
                        showAlert.danger(response.data.auth + ' | ' + response.data.access);
                        break;
                    }
                    default:{
                        showAlert.warning("unknown internal error, workspace might be empty. Hint: Reload browser window");
                    }
                }
            }
        });
    
    //Update button action performed
    $scope.submit = function(id){
        if(confirm('By clicking submit, means you have reviewed the fixed deposit details and you are satisfied')){
            var url = "/fixed/cashout/" + id;
            var data = JSON.stringify({channel:$scope.channel});
            httpReq.send(url,data,'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        showAlert.success("Transaction completed successfully. Sent to Accountant for finalization");
                        $route.reload();
                    }
                },
                error: function(response){
                    switch(response.status){
                        case 400:{
                            showAlert.warning(response.data.error);
                            break;
                        }
                        case 403:{
                            showAlert.danger(response.data.auth + ' | ' + response.data.access);
                            break;
                        }
                        default:{
                            showAlert.warning("unknown internal error, workspace might be empty. Hint: Reload browser window");
                        }
                    }
                }
            });
        }
    }
});