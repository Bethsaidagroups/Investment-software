/**
 * The Transaction angular controller
 */
'use strict';

//List customers controller
user.controller("listTransactionCtrl", function($scope, $location, $route, httpReq, showAlert){
    //check if there is one or more query parameter
    if($location.search().query){
        $scope.query = $location.search().query;
    }
    //search action performed
    $scope.search = function(){
        if($scope.query){
            $location.url('/transaction/list?query='+$scope.query);
        }
        else{
            showAlert.warning("Keyword is required");
        }
    }
    //build Parameters
    var page = 1;
    var url = '/transaction/list';
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
    //print action performed
    $scope.printBtn = function(index){
        $scope.reciept = list_data[index];
        $scope.show_reciept_box = true;
    }
    $scope.print = function(){
        $('#print-area').printThis({
            importCSS: true,
            importStyle: true,
            loadCSS: "/laser/view-lib/bootstrap/css/bootstrap.min.css",
            pageTitle: 'Bethsaida Investment Partners'
        });
    }
    $scope.closeRecieptBox = function(){
        $scope.show_reciept_box = false;
    }

    $scope.isCompleted = function(status){
        if(status.toLowerCase() === 'pending'){
            return false;
        }
        else{
            return true;
        }
    }
    //confirm action performed
    $scope.confirmBtn = function(id){
        $scope.message = 'Are you sure you want to CONFIRM this transaction'
        $scope.yes = function(){
            $scope.dialog_box = false;
            httpReq.send('/transaction/confirm/'+id,null,'GET',
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
        $scope.no = function(){
            $scope.dialog_box = false;
        }
        $scope.dialog_box = true;
    }
    //decline action performed
    $scope.declineBtn = function(id){
        $scope.message = 'Are you sure you want to DECLINE this transaction'
        $scope.yes = function(){
            $scope.dialog_box = false;
            httpReq.send('/transaction/decline/'+id,null,'GET',
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
        $scope.no = function(){
            $scope.dialog_box = false;
        }
        $scope.dialog_box = true;
    }
    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        if($location.search().query){
            $location.url('/transaction/list?query=' + $location.search().query + "&page=" + new_page);
        }
        else{
            $location.url('/transaction/list?page=' + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().query){
            $location.url('/transaction/list?query=' + $location.search().query + "&page=" + new_page);
        }
        else{
            $location.url('/transaction/list?page=' + new_page);
        }
    }
});