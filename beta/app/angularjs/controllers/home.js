/**
 * The default angular controller for default actions and default views that
 * sets the correct menu for the user
 */
'use strict';

user.controller("homeCtrl", function($scope, $location, $route, $window, httpReq, showAlert){
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