/**
 * The main angular controller for menu initialization that
 * sets the correct menu for the user
 */
'use strict';

user.controller("mainCtrl", function($scope, $location, $route, $window, httpReq, showAlert){
    //set header variables
    $scope.username = localStorage.getItem('username');
    $scope.user_type = localStorage.getItem('module');
    $scope.office = localStorage.getItem('office');

    //set include controller variable
    var level = localStorage.getItem('module')
    if(level.toLowerCase() === 'branch secretary'){
        $scope.isLevel1 = true;
    }
    else if(level.toLowerCase() === 'branch manager'){
        $scope.isLevel2 = true;
    }
    else if(level.toLowerCase() === 'central management unit'){
        $scope.isLevel3 = true;
    }
    
    $scope.home = function(){
        $location.url("/");
    }
    //refresh action performed
    $scope.refresh = function(){
        $route.reload();
    }
    //profile action performed
    $scope.profile = function(){
        $location.url("user/profile");
    }
    //logout action performed
    $scope.logout = function(){
        httpReq.sendNoContext('http://localhost/laser/logout.php',null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    showAlert.success(response.data.success);
                    //remove all catched data
                    localStorage.removeItem('username');
                    localStorage.removeItem('module');
                    localStorage.removeItem('office');

                    //redirect to login page
                    showAlert.danger('Logout in progress...');
                }
            }
        });
    }

    //------------------------------
    //Section for Drop down menu action performed
    //------------------------------
    $scope.dropDownMenuAction = function(link){
        $location.url(link);
    }
    $scope.search = {};
    $scope.dropSearch = function(ctrl){
        if((ctrl == 'customer') && $scope.search.customer){
            $location.url('/customer/list?query='+ $scope.search.customer);
        }
        if((ctrl == 'savings') && $scope.search.savings){
            $location.url('/savings/list?query='+$scope.search.savings);
        }
        if((ctrl == 'transaction') && $scope.search.transaction){
            $location.url('/transaction/list?query='+$scope.search.transaction);
        }
        if((ctrl == 'report') && $scope.search.report){
            $location.url('/report/detailed?init='+$scope.search.report);
        }
    }
});