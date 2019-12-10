/**
 * The Loan angular controller
 */
'use strict';

user.controller("addLoanCtrl", function($scope, $location, $route, httpReq, showAlert){
    var fetchDetails;
    $scope.hideSummary = function(){
        $scope.show_summary = false;
    }
    $scope.getDetails = fetchDetails = function(){
        $scope.show_summary = false;
        if($scope.account_no.length === 10){
            $scope.show_spinner = true;
            //get account details
            httpReq.send('/savings/get/init/'+$scope.account_no,null,'GET',
            {
                success: function(response){
                    if(response.status === 200){
                        $scope.show_spinner = false;
                        if(response.data.data){
                            var other_name = (response.data.data.bio_data.other_name)?response.data.data.bio_data.other_name:'';
                            $scope.msg = '';
                            $scope.show_details = true;
                            $scope.account_name = response.data.data.bio_data.surname + 
                            ' ' +response.data.data.bio_data.first_name + ' ' + other_name;
                            $scope.account_balance = response.data.data.balance;
                        }
                        else{
                            $scope.msg = response.data.message;
                            $scope.show_details = false;
                        }
                    }
                }
            },true);
        }
        else{
            $scope.show_spinner = false;
            $scope.msg = '';
            $scope.show_details = false;
        }
    }
    if($location.search().account){
        $scope.account_no = $location.search().account;
        fetchDetails();
    }
    $scope.continue = function(){
        if($scope.account_no && $scope.amount && $scope.channel && $scope.narration){
            $scope.summary = {account_no:'',account_name:'',amount:'',channel:'',narration:''};
            $scope.show_summary = true;
            $scope.summary.account_no = $scope.account_no;
            $scope.summary.account_name = $scope.account_name;
            $scope.summary.amount = $scope.amount;
            $scope.summary.channel = $scope.channel;
            $scope.summary.narration = $scope.narration;
        }
        else{
            showAlert.warning('Incorrect details filled in, please check and try again')
        }
    }
    $scope.confirm = function(){
        if($scope.account_no && $scope.amount && $scope.channel && $scope.narration 
            && confirm('Are you sure you want to continue with the issuance of this loan? Transaction made can not be reversed!')){
            var post_data = {account_no:$scope.account_no,amount:$scope.amount,channel:$scope.channel,narration:$scope.narration};
            httpReq.send('/loan/add/'+$scope.account_no,JSON.stringify(post_data),'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        showAlert.success(response.data.message);
                        //refresh page
                        $route.reload();
                    }
                }
            });
        }
        else{
            showAlert.warning('Incorrect details filled in, please check and try again');
        }
    }
});

//List customers controller
user.controller("listLoanRecordCtrl", function($scope, $location, $route, httpReq, showAlert){
    //check if there is one or more query parameter
    if($location.search().query){
        $scope.query = $location.search().query;
    }
    //search action performed
    $scope.search = function(){
        if($scope.query){
            $location.url('/loan/list?query='+$scope.query);
        }
        else{
            showAlert.warning("Keyword is required");
        }
    }
    //build Parameters
    var page = 1;
    var url = '/loan/list';
    if($location.search().page){
        page = $location.search().page;
    }
    if($location.search().query){
        url = url + '/'+$scope.query;
    }
    url = url + '/' + page;
    //get customers from database
    var list_data;
        httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    list_data = response.data.data;
                    $scope.total = response.data.meta.total
                    $scope.showing = response.data.meta.list_total
                    $scope.customers = response.data.data;
                    $scope.page = page;
                    //check the number of data in response data
                    if(response.data.meta.list_total < response.data.meta.total){
                        $scope.show_next = true;
                        page > 1 ? $scope.show_prev = true : $scope.show_prev = false;
                    }
                    else {
                        $scope.show_next = false;
                        page > 1 ? $scope.show_prev = true : $scope.show_prev = false;
                    }
                }
            }});
            
    //View btn action performed
    $scope.viewBtn = function(account_no){
        $location.url('/savings/list?query='+account_no);
    }
    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        if($location.search().query){
            $location.url('/loan/list?query=' + $location.search().query + "&page=" + new_page);
        }
        else{
            $location.url('/loan/list?page=' + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().query){
            $location.url('/loan/list?query=' + $location.search().query + "&page=" + new_page);
        }
        else{
            $location.url('/loan/list?page=' + new_page);
        }
    }
});

//Central List Savings controller
user.controller("centralListLoanCtrl", function($scope, $location, httpReq, showAlert){
    $scope.offices = '';
    $scope.show_select_office = true;
    $scope.show_balance = true;

    //check if there is one or more query parameter
    if($location.search().query){
        $scope.query = $location.search().query;
    }
    if($location.search().office){
        $scope.selected_office = $location.search().office;
    }
    else{
        $scope.selected_office = 'all';
    }
    //get offices from localstorage
    $scope.offices = JSON.parse(localStorage.getItem('offices'));
    //search action performed
    $scope.search = function(){
        if($scope.query){
            $location.url('/loan/central/list?office='+$scope.selected_office+'&query='+$scope.query);
        }
        else{
            $location.url('/loan/central/list?office='+$scope.selected_office);
        }
    }
    //build Parameters
    var page = 1;
    var url = '/loan/central/list/'+$scope.selected_office;
    if($location.search().page){
        page = $location.search().page;
    }
    if($location.search().query){
        url = url +'/'+$scope.query;
    }
    url = url + '/' + page;
    //get savings from database

    httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.balance = response.data.meta.balance;
                    $scope.total = response.data.meta.total;
                    $scope.showing = response.data.meta.list_total;
                    $scope.customers = response.data.data;
                    $scope.page = page;
                    //check the number of data in response data
                    if(response.data.meta.list_total < response.data.meta.total){
                        $scope.show_next = true;
                        page > 1 ? $scope.show_prev = true : $scope.show_prev = false;
                    }
                    else {
                        $scope.show_next = false;
                        page > 1 ? $scope.show_prev = true : $scope.show_prev = false;
                    }
                }
            }});

    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        if($location.search().query){
            $location.url('/loan/central/list?query=' + $location.search().query  + "&office=" +  $scope.selected_office + "&page=" + new_page);
        }
        else{
            $location.url('/loan/central/list?office=' +  $scope.selected_office + "&page=" + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().query){
            $location.url('/loan/central/list?query=' + $location.search().query   + "&office="  +  $scope.selected_office + "&page=" + new_page);
        }
        else{
            $location.url('/loan/central/list?office='  +  $scope.selected_office + "&page=" + new_page);
        }
    }

    //View btn action performed
    $scope.viewBtn = function(account_no){
        $location.url('/savings/list?query='+account_no);
    }
});