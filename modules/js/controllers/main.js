/**
 * The main angular controller for menu initialization that
 * sets the correct menu for the user
 */
'use strict';

user.controller("mainCtrl", function($scope, $location, $route, $window, httpReq, showAlert){
    httpReq.send('/init',null,'POST', 
        {success: function(response){
            if(response.status === 200){
                //set header variables
                $scope.username = localStorage.getItem('username');
                $scope.module = localStorage.getItem('module');
                $scope.office = localStorage.getItem('office');
                //Store Filters for offices and user type in localstorage
                localStorage.setItem('offices', JSON.stringify(response.data.res.offices));
                localStorage.setItem('types', JSON.stringify(response.data.res.types));
                //set menu data
                var menus = response.data.menu;
                $scope.menus = menus;
                //menu link click action performed
                $scope.link = function(key){
                    $location.url("/" + key);
                }
                //refresh action performed
                $scope.refresh = function(){
                    $route.reload();
                }
                //profile action performed
                $scope.profile = function(){
                    $location.url("/profile");
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
            }}, 
        error: function(response){
            switch(response.status){
                case 400:{
                    showAlert.warning(response.data.auth + ' | ' +  response.data.access);
                    break;
                }
                case 403:{
                    showAlert.danger(response.data.auth + ' | ' +  response.data.access);
                    break;
                }
                default:{
                    showAlert.warning("unknown internal error, workspace might be empty. Hint: Reload browser window");
                }
        }}
    });
    //set continue action performed
    //Start retrieving data from form

});