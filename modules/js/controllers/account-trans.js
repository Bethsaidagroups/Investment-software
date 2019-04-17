/**
 * The Account Transaction angular controller
 */
'use strict';

user.controller("depositCtrl", function($scope, httpReq, showAlert){
    //default settings
    $scope.account_no = true;
        //set continue action performed
        $scope.continue = function(){
            httpReq.send('/customer/view/search/accountQQno/' + $scope.deposit.account_no, null, 'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        var valid = false;
                        if(response.data.hasOwnProperty(1) &&  response.data[1].account_no == $scope.deposit.account_no){
                            $scope.customer = response.data[1];
                            $scope.account_no = false;
                            $scope.deposit_details = true;
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
            $scope.deposit_details = false;
        }

        //submit button action performed
        $scope.submit = function(){
            if(confirm('By clicking submit, means you have reviewed the deposit details and you are sure of all entries')){
                    //add default values to data
                    var data = new Object();
                    data = $scope.deposit;
                    data.id = "";
                    data.office = "0";
                    data.authorized_by = "";
                    data.timestamp = "";
                    data.date = "";
                    data.status = "";
                    data.category = "Savings Deposit";
                    data.type = "credit";
                    httpReq.send('/transaction/deposit/' + $scope.deposit.account_no,JSON.stringify(data),'POST',
                    {
                    success: function(response){
                        if(response.status === 200){
                            showAlert.success(response.data.success);
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

user.controller("withdrawCtrl", function($scope, httpReq, showAlert){
    //default settings
    $scope.account_no = true;
        //set continue action performed
        $scope.continue = function(){
            httpReq.send('/customer/view/search/accountQQno/' + $scope.withdraw.account_no, null, 'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        var valid = false;
                        if(response.data.hasOwnProperty(1) &&  response.data[1].account_no == $scope.withdraw.account_no){
                            $scope.customer = response.data[1];
                            $scope.account_no = false;
                            $scope.withdraw_details = true;
                            httpReq.send('/savings/view/search/accountQQno/' + $scope.withdraw.account_no,null,'POST',
                                {
                                success: function(response){
                                    if(response.status === 200){
                                        $scope.savings = response.data[1];
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
            $scope.withdraw_details = false;
        }

        //submit button action performed
        $scope.submit = function(){
            if(confirm('By clicking submit, means you have reviewed the withdrawal details and you are sure of all entries')){
                    //add default values to data
                    var data = new Object();
                    data = $scope.withdraw;
                    data.id = "";
                    data.office = "0";
                    data.authorized_by = "";
                    data.timestamp = "";
                    data.date = "";
                    data.status = "";
                    data.category = "Savings Withdrawal";
                    data.type = "debit";
                    httpReq.send('/transaction/withdraw/' + $scope.withdraw.account_no,JSON.stringify(data),'POST',
                {
                    success: function(response){
                        if(response.status === 200){
                            showAlert.success(response.data.success);
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

user.controller("manageTransCtrl", function($scope, $location, $route, httpReq, showAlert){
    //set default search keys
    var keyParams = {id:'Transaction Id',accountQQno:'Savings Account No',office:'Registration Branch'};
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
            $location.url('/acct-trans?key=' + $scope.key + '&value=' + $scope.value);
        }
        else{
            showAlert.warning("Select correct search parameters");
        }
    }
    //build Parameters
    var page = 1;
    var key = "";
    var value = "";
    var url = '/transaction/view';
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
                    $scope.trans = response.data;
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
    //Edit action performed
    $scope.editBtn = function(id){
        //$location.url('/edit-fixed?id=' + id);
    }
    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        if($location.search().value && $location.search().key){
            $location.url('/acct-trans?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/acct-trans?page=' + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().value && $location.search().key){
            $location.url('/acct-trans?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/acct-trans?page=' + new_page);
        }
    }
});