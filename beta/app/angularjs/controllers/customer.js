/**
 * The Customer angular controller
 */
'use strict';

user.controller("addCustomerCtrl", function($scope, $location, httpReq, showAlert){
    //default settings
    $scope.marketer = true;

    //get customers from server - immediately after page load
        httpReq.send('/customer/marketer/get',null,'GET',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.marketers = response.data.data;
                }
            }
        });
    $scope.nextMarketer = function(){
        if(('form' in $scope && $scope.form.marketer)){
            $scope.marketer = false;
            $scope.bio_data = true;
        }
        else{
            showAlert.warning('You must select a marketer before you can proceed');
        }
    }
    //nextBio action performed
    $scope.nextBio = function(){
        $scope.bio_data = false;
        $scope.id_data = true;
    }
    //backBio action performed
    $scope.backBio = function(){
        $scope.bio_data = false;
        $scope.marketer = true;
    }

    //nextId action performed
    $scope.nextId = function(){
        $scope.id_data = false;
        $scope.employment_data = true;
    }
    //backId action performed
    $scope.backId = function(){
        $scope.bio_data = true;
        $scope.id_data = false;
    }

    //nextEmploy action performed
    $scope.nextEmploy = function(){
        $scope.employment_data = false;
        $scope.kin_data = true;
    }
    //backEmploy action performed
    $scope.backEmploy = function(){
        $scope.employment_data = false;
        $scope.id_data = true;
    }

    //nextKin action performed
    $scope.nextKin = function(){
        $scope.invest_data = true;
        $scope.kin_data = false;
    }
    //backKin action performed
    $scope.backKin = function(){
        $scope.kin_data = false;
        $scope.employment_data = true;
    }

    //nextInvest action performed
    $scope.nextInvest = function(){
        $scope.savings_plan = true;
        $scope.invest_data = false;
    }
    //backInvest action performed
    $scope.backInvest = function(){
        $scope.invest_data = false;
        $scope.kin_data = true;
    }

    //backKin action performed
    $scope.backPlan = function(){
        $scope.savings_plan = false;
        $scope.invest_data = true;
    }
    //submit button action performed
    $scope.submit = function(){
        var data = $scope.form;
        httpReq.send('/customer/register',JSON.stringify(data),'POST',
        {
            success: function(response){
                if(response.status === 200){
                    showAlert.success(response.data.message);
                    //redirect to customers list
                    $location.url('/customer/list?query='+response.data.data.account_no);
                }
            }
        });
    }
});

//List customers controller
user.controller("listCustomerCtrl", function($scope, $location, httpReq, showAlert){
    //check if there is one or more query parameter
    if($location.search().query){
        $scope.query = $location.search().query;
    }
    //search action performed
    $scope.search = function(){
        if($scope.query){
            $location.url('/customer/list?query='+$scope.query);
        }
        else{
            showAlert.warning("Keyword is required");
        }
    }
    //build Parameters
    var page = 1;
    var url = '/customer/list';
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
    //edit action perform
    //Edit action performed
    $scope.editBtn = function(account_no){
        $location.url('/customer/edit/?account_no=' + account_no);
    }
    //View btn action performed
    $scope.viewBtn = function(account_no){
        $location.url('/savings/list?query='+account_no);
    }

    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        if($location.search().query){
            $location.url('/customer/list?query=' + $location.search().query + "&page=" + new_page);
        }
        else{
            $location.url('/customer/list?page=' + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().query){
            $location.url('/customer/list?query=' + $location.search().query + "&page=" + new_page);
        }
        else{
            $location.url('/customer/list?page=' + new_page);
        }
    }
});
//edit customer controller
user.controller("editCustomerCtrl", function($scope, $location, httpReq, showAlert){
    httpReq.send('/customer/get/'+$location.search().account_no,null,'POST',
    {
        success: function(response){
            if(response.status === 200){
                $scope.form = response.data.data;
            }
        }
    });
    //update action performed
    $scope.update = function(){
        httpReq.send('/customer/edit/'+$location.search().account_no,JSON.stringify($scope.form),'POST',
        {
            success: function(response){
                if(response.status === 200){
                    showAlert.success(response.data.message);
                }
            }
        });
    }
});

//List customers controller
user.controller("centralListCustomerCtrl", function($scope, $location, httpReq, showAlert){
    $scope.offices = '';
    $scope.show_select_office = true;

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
            $location.url('/customer/central/list?office='+$scope.selected_office+'&query='+$scope.query);
        }
        else{
            $location.url('/customer/central/list?office='+$scope.selected_office);
        }
    }
    //build Parameters
    var page = 1;
    var url = '/customer/central/list/'+$scope.selected_office;
    if($location.search().page){
        page = $location.search().page;
    }
    if($location.search().query){
        url = url +'/'+$scope.query;
    }
    url = url + '/' + page;
    //get customers from database
    httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.balance = response.data.meta.balance
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
        $location.url('/savings/central/list?&office=all&query='+account_no);
    }

    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        if($location.search().query){
            $location.url('/customer/central/list?query=' + $location.search().query  + "&office=" +  $scope.selected_office + "&page=" + new_page);
        }
        else{
            $location.url('/customer/central/list?office=' +  $scope.selected_office + "&page=" + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().query){
            $location.url('/customer/central/list?query=' + $location.search().query   + "&office="  +  $scope.selected_office + "&page=" + new_page);
        }
        else{
            $location.url('/customer/central/list?office='  +  $scope.selected_office + "&page=" + new_page);
        }
    }
});