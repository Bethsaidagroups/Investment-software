/**
 * The Investrite Login controller
 */
'use strict';

user.controller("investriteCtrl", function($scope, $location, $route, $window, httpReq, showAlert){

    $scope.login = function(){
        if ($scope.username && $scope.password){
            $('.login100-form-btn').empty();
            $('.login100-form-btn').append('<i class="fa fa-spinner fa-spin"></i>');
            $('.login100-form-btn').attr('disabled','disabled')
            $.ajax({
                url: "http://127.0.0.1:5000/staff/api/auth/",
                cache: false,
                type: "POST",
                data: $('#investrite-form').serialize(),
                success: function(data){
                    $('.login100-form-btn').empty();
                    $('.login100-form-btn').append('LOGIN');
                    $('.login100-form-btn').removeAttr('disabled')
                    //login successful, create session for this client machine
                    localStorage.setItem('token', data.token)
                    localStorage.setItem('user',JSON.stringify(data.user))
                    //redirect
                    $window.location = '/laser/beta/investrite';

                },
                error: function(data){
                    data = JSON.parse(data.responseText)
                    showAlert.warning(data.error)
                    $('.login100-form-btn').empty();
                    $('.login100-form-btn').append('LOGIN');
                    $('.login100-form-btn').removeAttr('disabled')
                }
            })
        }
        else{
            showAlert.warning("Invalid username or password");
        }
    }
    //initialize home data
    httpReq.send('/report/summary',null,'GET',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.summary = response.data.data
                    if(response.data.data.offices){
                        localStorage.setItem('offices',JSON.stringify(response.data.data.offices));
                    }
                }
            }
        });

        //Icon Menu action performed
        $scope.iconMenu = function(link){
            $location.url(link);
        }
});