/**
 * The Quick Access angular controller
 */
'use strict';

user.controller("quickAccessCtrl", function($scope, $location, $route, httpReq, showAlert){
    //set default search keys
    var keyParams = {id:'Transaction Id',accountQQno:'Savings Account No',category:'Transaction Category',office:'Registration Branch', date:'Transaction Date'};
    var categories = ['Savings Deposit', 'Savings Withdrawal', 'Loan Payout', 'Loan Excess', 'Invoice Payment', 'Fixed Deposit', 'Savings Invoice Payment', 'Target Savings'];
    var data_no = 0;
    $scope.keys = keyParams;
    $scope.categories = categories
    //Key Change Action performed
    $scope.list = JSON.parse(localStorage.getItem('offices')); //set offices
    $scope.keyChange = function(){
        if($scope.key == 'office'){
            $scope.value = "";
            $scope.value_input = false;
            $scope.value_select = true;
            $scope.date_input = false;
            $scope.category_select = false;
        }
        else if($scope.key == 'date'){
            $scope.value = new Object();
            $scope.value_input = false;
            $scope.value_select = false;
            $scope.date_input = true;
            $scope.category_select = false;
        }
        else if($scope.key == 'category'){
            $scope.value = "";
            $scope.value_input = false;
            $scope.value_select = false;
            $scope.date_input = false;
            $scope.category_select = true;
        }
        else{
            $scope.value = "";
            $scope.value_input = true;
            $scope.category_select = false;
            $scope.value_select = false;
            $scope.date_input = false;
            $scope.selected_key = keyParams[$scope.key];
        }
    }
    if($location.search().key && $location.search().value){
        $scope.key = $location.search().key;
        if($scope.key == 'date' ){
            $scope.value = new Object();
            var date_pair = $location.search().value.split('HH');
            $scope.value.from_date = date_pair[0];
            $scope.value.to_date = date_pair[1];
        }
        else{
            $scope.value = $location.search().value;
        }
        //set appropriate forms
        if($scope.key == 'office'){
            $scope.value_select = true;
        }
        else if($scope.key == 'date'){
            $scope.date_input = true;
        }
        else if($scope.key == 'category'){
            $scope.category_select = true;
        }
        else{
            $scope.value_input = true;
            $scope.selected_key = keyParams[$scope.key];
        }
    }
    //search action performed
    $scope.searchBtn = function(){
        if($scope.key != null && $scope.value != null){
            if($scope.key == 'date'){
                $location.url('/quick-access?key=' + $scope.key + '&value=' + $scope.value.from_date + 'HH' + $scope.value.to_date );
            }
            else{
                $location.url('/quick-access?key=' + $scope.key + '&value=' + $scope.value);
            }
        }
        else{
            showAlert.warning("Select correct search parameters");
        }
    }
    //build Parameters
    var page = 1;
    var key = "";
    var value = "";
    var url = '/quick/view';
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
                    if($location.search().key && $location.search().value){
                        $scope.trans = response.data;
                        $scope.page = page;
                        $scope.search_result = true;
                    }
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
    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        if($location.search().value && $location.search().key){
            $location.url('/quick-access?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/quick-access?page=' + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().value && $location.search().key){
            $location.url('/quick-access?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/quick-access?page=' + new_page);
        }
    }
});