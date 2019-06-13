/**
 * The Loan application angular controller
 */
'use strict';

user.controller("addLoanCtrl", function($scope, httpReq, showAlert){
    //default settings
    $scope.account_no = true;
        //set continue action performed
        $scope.continue = function(){
            httpReq.send('/customer/view/search/accountQQno/' + $scope.loanApp.account_no, null, 'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        var valid = false;
                
                        if(response.data.hasOwnProperty(1) &&  response.data[1].account_no == $scope.loanApp.account_no){
                            showAlert.success('Account No: ' + response.data[1].account_no + ', Name: ' + response.data[1].bio_data.surname +" "+ response.data[1].bio_data.first_name + ' was selected');
                            $scope.account_no = false;
                            $scope.loan_details = true;
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
            $scope.loan_details = false;
        }

        //submit button action performed
        $scope.submit = function(){
            //add default values to data
            var data = new Object();
            data = $scope.loanApp;
            httpReq.send('/loan/add',JSON.stringify(data),'POST',
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
});

//Loan application management controller
user.controller("manageLoanCtrl", function($scope, $location, $route, httpReq, showAlert){
    //set default search keys
    var keyParams = {refQQno:'Loan Reference No',accountQQno:'Savings Account No',office:'Registration Branch',registeredQQby:'Registered By'};
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
            $location.url('/manage-loan?key=' + $scope.key + '&value=' + $scope.value);
        }
        else{
            showAlert.warning("Select correct search parameters");
        }
    }
    //build Parameters
    var page = 1;
    var key = "";
    var value = "";
    var url = '/loan/view';
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
                    $scope.loans = response.data;
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
    $scope.edit = function(id){
        $location.url('/edit-loan?id=' + id);
    }
    //approve action performed
    $scope.approve = function(id, status){
        if(status == 'pending'){
            showAlert.warning('Loan yet to be acknowledged and approved by Director');
        }
        else if(status == "approved"){
            showAlert.warning('Loan has been approved already');
        }
        else if (status == "eligible"){
            $location.url('/generate-invoice?id=' + id);
        }
    }
    //View btn action performed
    $scope.delete = function(id){
        if(confirm("Are sure you want to delete this Loan application ?")){
            var url = '/loan/delete/' + id
            httpReq.send(url,null,'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        showAlert.success('Loan application deleted successfully');
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
            $location.url('/manage-loan?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/manage-loan?page=' + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().value && $location.search().key){
            $location.url('/manage-loan?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/manage-loan?page=' + new_page);
        }
    }
});

//Edit  loan application controller
user.controller("editLoanCtrl", function($scope, $location, $route, httpReq, showAlert){
    //get customer from database
    var url = "/loan/" + $location.search().id;
    httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.loanApp = response.data;
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
    $scope.update = function(id){
        var url = "/loan/edit/" + id;
        var data = JSON.stringify($scope.loanApp);
        httpReq.send(url,data,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    showAlert.success(response.data.success);
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
});

//Director Loan view controller
user.controller("loansCtrl", function($scope, $location, $route, httpReq, showAlert){
    //set default search keys
    var keyParams = {refQQno:'Loan Reference No',accountQQno:'Savings Account No',office:'Registration Branch',registeredQQby:'Registered By'};
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
            $location.url('/loans?key=' + $scope.key + '&value=' + $scope.value);
        }
        else{
            showAlert.warning("Select correct search parameters");
        }
    }
    //build Parameters
    var page = 1;
    var key = "";
    var value = "";
    var url = '/loan/view';
    if($location.search().page){
        page = $location.search().page;
    }
    if($location.search().key && $location.search().value){
        key = $location.search().key;
        value = $location.search().value;
        url = url + '/search/' + key + '/' + value;
    }
    url = url + '/' + page;
    //get Loan application from database
    httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.loans = response.data;
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
    //approve action performed
    $scope.approve = function(index, status){
        if(status == "approved"){
            showAlert.warning('Loan has been approved already. Cant change status at this point');
        }
        else{
            $scope.selected_loan = $scope.loans[index];
        }
    }

    //confirm button action performed
    $scope.confirmBtn = function(){
        if(confirm('Change of status confirmation for this Loan')){
            httpReq.send('/loan/edit/' + $scope.selected_loan.id, JSON.stringify($scope.selected_loan), 'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        showAlert.success(response.data.success);
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
            $location.url('/loans?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/loans?page=' + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().value && $location.search().key){
            $location.url('/loans?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/loans?page=' + new_page);
        }
    }
});
