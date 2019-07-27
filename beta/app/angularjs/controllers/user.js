/**
 * The User angular controller
 */
'use strict';

//User profile controller
user.controller("profileCtrl", function($scope, $location, $route, httpReq, showAlert){
    //get users from database
    var url = "/user/profile/get/" + localStorage.getItem('username');
    httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.user = response.data.data;
                    $scope.image_url = '../../contents/images/users/'+ response.data.data.username + '.jpg' + '?' + new Date().getTime();
                }
            }
        });
    
    //Update button action performed
    $scope.update = function(id){
        if($scope.pwd.old && $scope.pwd.new && $scope.verify_pwd){
            if($scope.pwd.new.length >= 6){
                if($scope.pwd.new === $scope.verify_pwd){
                    //send new password to the server
                    var url = "/user/profile/pwd/" + localStorage.getItem('username');
                    var data = JSON.stringify($scope.pwd);
                    httpReq.send(url,data,'POST',
                    {
                        success: function(response){
                            if(response.status === 200){
                                showAlert.success(response.data.message);
                                $location.url("/");
                            }
                        }
                    });
                }
                else{
                    showAlert.warning('Password do not match');
                }
            }
            else{
                showAlert.warning('Password must be 6 characters and above');
            }
        }
        else{
            showAlert.warning('All fields are required!');
        }
    }
});