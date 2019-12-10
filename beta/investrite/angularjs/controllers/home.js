/**
 * The default angular controller for default actions and default views that
 * sets the correct menu for the user
 */
'use strict';

user.controller("homeCtrl", function($scope, $location, $route, $window, httpReq){
    //set include controller variable
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

    //Get reports
    httpReq.send({
        url: '/reports',
        method: 'GET',
        callback: {
            success: response => {
                $scope.summary = response.data;
            }
        }
     })
        //Icon Menu action performed
        $scope.iconMenu = function(link){
            $location.url(link);
        }
});