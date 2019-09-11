/**
 * The Customer angular controller
 */
'use strict';

user.controller("initWithdrawalCtrl", function($scope, $location, $route, httpReq, showAlert){
    $scope.hideSummary = function(){
        $scope.show_summary = false;
    }
    $scope.getDetails = function(){
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
            && confirm('Are you sure you want to initialize this transaction?')){
            var post_data = {account_no:$scope.account_no,amount:$scope.amount,channel:$scope.channel,narration:$scope.narration};
            httpReq.send('/savings/withdrawal/init/'+$scope.account_no,JSON.stringify(post_data),'POST',
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

user.controller("initDepositCtrl", function($scope, $location, $route, httpReq, showAlert){
    $scope.hideSummary = function(){
        $scope.show_summary = false;
    }
    $scope.getDetails = function(){
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
    $scope.continue = function(){
        if($scope.account_no && $scope.amount && $scope.channel){
            $scope.summary = {account_no:'',account_name:'',amount:'',channel:'',narration:''};
            $scope.show_summary = true;
            $scope.summary.account_no = $scope.account_no;
            $scope.summary.account_name = $scope.account_name;
            $scope.summary.amount = $scope.amount;
            $scope.summary.channel = $scope.channel;
            $scope.summary.narration = ($scope.narration)?$scope.narration:'none';
        }
        else{
            showAlert.warning('Incorrect details filled in, please check and try again')
        }
    }
    $scope.confirm = function(){
        if($scope.account_no && $scope.amount && $scope.channel 
            && confirm('Are you sure you want to initialize this deposit transaction?')){
            var post_data = {account_no:$scope.account_no,amount:$scope.amount,channel:$scope.channel,narration:$scope.summary.narration};
            httpReq.send('/savings/deposit/init/'+$scope.account_no,JSON.stringify(post_data),'POST',
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
user.controller("listSavingsCtrl", function($scope, $location, httpReq, showAlert){
    //check if there is one or more query parameter
    if($location.search().query){
        $scope.query = $location.search().query;
    }
    //search action performed
    $scope.search = function(){
        if($scope.query){
            $location.url('/savings/list?query='+$scope.query);
        }
        else{
            showAlert.warning("Keyword is required");
        }
    }
    //build Parameters
    var page = 1;
    var url = '/savings/list';
    if($location.search().page){
        page = $location.search().page;
    }
    if($location.search().query){
        url = url + '/'+$scope.query;
    }
    url = url + '/' + page;
    //get customers from database
    httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
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
    //credit action performed
    $scope.creditBtn = function(account_no){
        $location.url('/savings/make-deposit?account=' + account_no);
    }
    //debit action performed
    $scope.debitBtn = function(account_no){
        $location.url('/savings/make-withdrawal?account=' + account_no);
    }
     //loan action performed
     $scope.loanBtn = function(account_no){
        $location.url('/loan/add?account=' + account_no);
    }
    //Edit action performed
    $scope.editBtn = function(account_no){
        $location.url('/savings/edit/?account_no=' + account_no);
    }
    //View btn action performed
    $scope.viewBtn = function(account_no){
        $location.url('/customer/list?query='+account_no);
    }
    //print statement of account
    $scope.soaBtn = function(account_no){
        $location.url('/report/detailed?init='+account_no);
    }

    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        if($location.search().query){
            $location.url('/savings/list?query=' + $location.search().query + "&page=" + new_page);
        }
        else{
            $location.url('/savings/list?page=' + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().query){
            $location.url('/savings/list?query=' + $location.search().query + "&page=" + new_page);
        }
        else{
            $location.url('/savings/list?page=' + new_page);
        }
    }
});
//edit Savings Account controller
user.controller("editSavingsCtrl", function($scope, $location, httpReq, showAlert){
    $scope.account_no = $location.search().account_no;
    httpReq.send('/savings/get/'+$location.search().account_no,null,'POST',
    {
        success: function(response){
            if(response.status === 200){
                $scope.form = response.data.data;
            }
        }
    });
    //update action performed
    $scope.update = function(){
        httpReq.send('/savings/edit/'+$location.search().account_no,JSON.stringify($scope.form),'POST',
        {
            success: function(response){
                if(response.status === 200){
                    showAlert.success(response.data.message);
                    $location.url('/savings/list')
                }
            }
        });
    }
});

user.controller("makeWithdrawalCtrl", function($scope, $location, $route, httpReq, showAlert){
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
            && confirm('Are you sure you want to continue with this transaction? Transaction made can not be reversed!')){
            var post_data = {account_no:$scope.account_no,amount:$scope.amount,channel:$scope.channel,narration:$scope.narration};
            httpReq.send('/savings/withdrawal/make/'+$scope.account_no,JSON.stringify(post_data),'POST',
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

//make deposit controller
user.controller("makeDepositCtrl", function($scope, $location, $route, httpReq, showAlert){
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
        if($scope.account_no && $scope.amount && $scope.channel){
            $scope.summary = {account_no:'',account_name:'',amount:'',channel:'',narration:''};
            $scope.show_summary = true;
            $scope.summary.account_no = $scope.account_no;
            $scope.summary.account_name = $scope.account_name;
            $scope.summary.amount = $scope.amount;
            $scope.summary.channel = $scope.channel;
            $scope.summary.narration = ($scope.narration)?$scope.narration:'none';
        }
        else{
            showAlert.warning('Incorrect details filled in, please check and try again')
        }
    }
    $scope.confirm = function(){
        if($scope.account_no && $scope.amount && $scope.channel 
            && confirm('Are you sure you want to continue with this transaction? Transaction made can not be reversed!')){
            var post_data = {account_no:$scope.account_no,amount:$scope.amount,channel:$scope.channel,narration:$scope.summary.narration};
            httpReq.send('/savings/deposit/make/'+$scope.account_no,JSON.stringify(post_data),'POST',
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


//Central List Savings controller
user.controller("centralListSavingsCtrl", function($scope, $location, httpReq, showAlert){
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
            $location.url('/savings/central/list?office='+$scope.selected_office+'&query='+$scope.query);
        }
        else{
            $location.url('/savings/central/list?office='+$scope.selected_office);
        }
    }
    //build Parameters
    var page = 1;
    var url = '/savings/central/list/'+$scope.selected_office;
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
    //View btn action performed
    $scope.viewBtn = function(account_no){
        $location.url('/customer/central/list?office=all&query='+account_no);
    }
    //print statement of account
    $scope.soaBtn = function(account_no){
        $location.url('/report/central/detailed?init='+account_no);
    }

    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        if($location.search().query){
            $location.url('/savings/central/list?query=' + $location.search().query  + "&office=" +  $scope.selected_office + "&page=" + new_page);
        }
        else{
            $location.url('/savings/central/list?office=' +  $scope.selected_office + "&page=" + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().query){
            $location.url('/savings/central/list?query=' + $location.search().query   + "&office="  +  $scope.selected_office + "&page=" + new_page);
        }
        else{
            $location.url('/savings/central/list?office='  +  $scope.selected_office + "&page=" + new_page);
        }
    }
});