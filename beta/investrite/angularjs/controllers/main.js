/**
 * The main angular controller for menu initialization that
 * sets the correct menu for the user
 */
'use strict';

user.controller("mainCtrl", function($scope, $location, $route, $window, httpReq, constants){
    //set header variables
    var user = JSON.parse(localStorage.getItem('user'));
    $scope.user = user;
    if(user.user_type.toLowerCase() === 'admin'){
        $scope.admin = true;
    }
    else if(user.user_type.toLowerCase() === 'manager'){
        $scope.manager = true;
    }
    else if(user.user_type.toLowerCase() === 'customer_support'){
        $scope.support = true;
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
    //Investment App
    $scope.investment_app = () =>{
        window.location.assign(constants.home_url)
    }
    //logout action performed
    $scope.logout = function(){
        httpReq.send({
            url: '/logout',
            method: 'GET',
            callback: {
                success: response => {
                    window.location.assign(constants.main_login)
                }
            }
         })
    }

    //------------------------------
    //Section for Drop down menu action performed
    //------------------------------
    $scope.dropDownMenuAction = function(link){
        $location.url(link);
    }
    $scope.search = {};
    $scope.dropSearch = function(ctrl){
        
    }
});