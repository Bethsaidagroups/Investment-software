/**
 * The Savings Plan angular controller
 */
'use strict';

//User management controller
user.controller("manageSavingsCtrl", function($scope, $location, $route, httpReq, showAlert){
    //set default search keys
    var keyParams = {accountQQno:'Savings Account No',office:'Registration Branch'};
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
            $location.url('/manage-savings?key=' + $scope.key + '&value=' + $scope.value);
        }
        else{
            showAlert.warning("Select correct search parameters");
        }
    }
    //build Parameters
    var page = 1;
    var key = "";
    var value = "";
    var url = '/savings/view';
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
                    $scope.savings = response.data;
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
        $location.url('/edit-savings?id=' + id);
    }
    //View btn action performed
    $scope.viewBtn = function(account_no){
        $location.url('/manage-customer?key=accountQQno&value=' + account_no);
    }
    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        if($location.search().value && $location.search().key){
            $location.url('/manage-savings?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/manage-savings?page=' + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().value && $location.search().key){
            $location.url('/manage-savings?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/manage-savings?page=' + new_page);
        }
    }
});

//Edit  savings controller
user.controller("editSavingsCtrl", function($scope, $location, $route, httpReq, showAlert){
    //get customer from database
    var url = "/savings/" + $location.search().id;
    httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.form = response.data;
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
        var url = "/savings/edit/" + id;
        var data = JSON.stringify($scope.form);
        httpReq.send(url,data,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    showAlert.success("Savings Account updated successfully");
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