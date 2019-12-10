/**
 * The Option controller
 */
'use strict';

user.controller("optionsCtrl", function($scope, $location, $route, $window, httpReq){
    //Initialize option variables
    httpReq.send({
        url: '/options/',
        method: 'GET',
        callback: {
            success: response => {
                $scope.login_access = response.data[0].value
                $scope.register_access = response.data[1].value
            }
        }
    });

    $scope.update_login_access = () => {
        swal({
            title: "Confirmation",
            text: "You are about to turn off top level login access, for all customers",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete){
                httpReq.send({
                    url: '/options/',
                    method: 'POST',
                    data: {name: 'login_access', value: $scope.login_access},
                    callback: {
                        success: response => {
                            swal('Succes', response.data.success, 'success');
                            $route.reload()
                        }
                    }
                });
            }
            else{
                //Do nothing
            }
        })
    }

    $scope.update_register_access = () => {
        swal({
            title: "Confirmation",
            text: "You are about to turn off new registration access, for all customers",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete){
                httpReq.send({
                    url: '/options/',
                    method: 'POST',
                    data: {name: 'register_access', value: $scope.register_access},
                    callback: {
                        success: response => {
                            swal('Succes', response.data.success, 'success');
                            $route.reload()
                        }
                    }
                });
            }
            else{
                //Do nothing
            }
        })
    }
});