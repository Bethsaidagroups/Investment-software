/**
 * The Fixed angular controller
 */
'use strict';

user.controller("addFixedCtrl", function($scope, httpReq, showAlert){
    //default settings
    $scope.account_no = true;
        //set continue action performed
        $scope.continue = function(){
            httpReq.send('/customer/view/search/accountQQno/' + $scope.fixed.account_no, null, 'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        var valid = false;
                
                        if(response.data.hasOwnProperty(1) &&  response.data[1].account_no == $scope.fixed.account_no){
                            showAlert.success('Account No: ' + response.data[1].account_no + ', Name: ' + response.data[1].bio_data.surname +" "+ response.data[1].bio_data.first_name + ' was selected');
                            $scope.account_no = false;
                            $scope.fixed_details = true;
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
            $scope.fixed_details = false;
        }

        //submit button action performed
        $scope.submit = function(){
            if(confirm('By clicking submit, means you have reviewed the fixed deposit details and you are satisfied')){
            
                    //add default values to data
                    var data = new Object();
                    data = $scope.fixed;
                    data.id = "";
                    data.office = "0";
                    data.registered_by = "";
                    data.timestamp = "";
                    data.status = "";
                    data.roi = "";
                    httpReq.send('/fixed/add',JSON.stringify(data),'POST',
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

//Fixed Deposit management controller
user.controller("manageFixedCtrl", function($scope, $location, $route, httpReq, showAlert){
    //set default search keys
    var keyParams = {id:'Fixed Deposit Id',accountQQno:'Savings Account No',office:'Registration Branch',registeredQQby:'Registered By'};
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
            $location.url('/manage-fixed?key=' + $scope.key + '&value=' + $scope.value);
        }
        else{
            showAlert.warning("Select correct search parameters");
        }
    }
    //build Parameters
    var page = 1;
    var key = "";
    var value = "";
    var url = '/fixed/view';
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
    $scope.editBtn = function(id){
        //$location.url('/edit-fixed?id=' + id);
    }
    //View btn action performed
    $scope.cashOutBtn = function(id,status){
        if(status == 'cashed'){
            showAlert.warning("This deposit has been cashed out already");
        }
        else if(status == 'completed'){
            $location.url('/cash-out-fixed?id=' + id);
        }
        else{
            if(confirm('This fixed deposit is yet to be completed. if you continue ROI will be lost')){
                $location.url('/cash-out-fixed?id=' + id);
            }
        }
    }
    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        if($location.search().value && $location.search().key){
            $location.url('/manage-fixed?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/manage-fixed?page=' + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().value && $location.search().key){
            $location.url('/manage-fixed?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/manage-fixed?page=' + new_page);
        }
    }
});

//Cash Out Fixed Deposit controller
user.controller("cashOutFixedCtrl", function($scope, $location, $route, httpReq, showAlert){
    //get customer from database
    var url = "/fixed/" + $location.search().id;
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