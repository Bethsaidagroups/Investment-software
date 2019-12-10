/**
 * The user controller
 */
'use strict';

user.controller("addInvestmentCtrl", function($scope, $location, $window, httpReq, constants){
    $scope.register = () => {
        httpReq.send({
           url: '/investment-class/create/',
           method: 'POST',
           data: $scope.data,
           callback: {
               success: response => {
                    swal("Success", 'New investment class created successfully', "success");
                    $location.url('/investment/list?id='+ response.data.id)
               },
               error: response => {
                   switch (response.status) {
                        case 400:
                           swal("Something Went Wrong", 'An error occured, please check your entries and try again', "error");
                           break;
                        case 401:{
                            swal("Something Went Wrong", response.data.error, "error");
                            break;
                        }
                        case 403:{
                            swal("Something Went Wrong", response.data.error, "error");
                            $window.location = constants.home_url;
                            break;
                        }
                        case 404:{
                            swal("Something Went Wrong", response.data.error, "error");
                            break;
                        }
                        default:{
                            swal("Something Went Wrong", constants.unknownError, "error");
                        }
                   }
               }
           }
        })
    }
});


user.controller("listInvestmentCtrl", function($scope, $location, $route, httpReq, constants){
    //check url for keywords
    httpReq.send({
        url: '/investment-class/list/',
        method: 'GET',
        callback: {
            success: response => {
                $scope.users = response.data;
            }
        }
    });
    $scope.isActive = status => {
        if(status == 'active'){
            return true;
        }
    }
    $scope.isInactive = status => {
        if(status == 'inactive'){
            return true;
        }
    }

    $scope.search = () =>{
        var keyword = $scope.keyword;
        if(keyword){
            $location.url('/investment/list/?keyword='+ keyword)
        }
        else{
            $location.url('/investment/list/')
        }
    }

    //list button handler
    $scope.edit = id => {
        $location.url('/investment/edit?id='+id)
    }

    //delete button handler
    $scope.delete = id =>{
        swal('Operation Denied', 'Can not delete investment class', 'warning')
    }

});

user.controller("editInvestmentCtrl", function($scope, $location, $window, httpReq, constants){
    httpReq.send({
        url: '/investment-class/get/?id='+$location.search().id,
        method: 'GET',
        callback: {
            success: response => {
                $scope.data = response.data;
            }
        }
    });

    $scope.update = () => {
        httpReq.send({
           url: '/investment-class/edit/',
           method: 'POST',
           data: $scope.data,
           callback: {
               success: response => {
                    swal("Success", 'Investment class edited successfully', "success");
                    $location.url('/investment/list?id='+ response.data.id)
               },
               error: response => {
                   switch (response.status) {
                        case 400:
                           swal("Something Went Wrong", 'An error occured, please check your entries and try again', "error");
                           break;
                        case 401:{
                            swal("Something Went Wrong", response.data.error, "error");
                            break;
                        }
                        case 403:{
                            swal("Something Went Wrong", response.data.error, "error");
                            $window.location = constants.home_url;
                            break;
                        }
                        case 404:{
                            swal("Something Went Wrong", response.data.error, "error");
                            break;
                        }
                        default:{
                            swal("Something Went Wrong", constants.unknownError, "error");
                        }
                   }
               }
           }
        })
    }
});