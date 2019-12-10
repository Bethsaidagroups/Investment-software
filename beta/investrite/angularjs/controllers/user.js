/**
 * The user controller
 */
'use strict';

user.controller("registerUserCtrl", function($scope, $location, $window, httpReq, constants){
    $scope.register = () => {
        httpReq.send({
           url: '/register/',
           method: 'POST',
           data: $scope.data,
           callback: {
               success: response => {
                    swal("Success", 'New user created successfully', "success");
                    $location.url('/user/list?id='+ response.data.id)
               },
               error: response => {
                   switch (response.status) {
                        case 400:
                           swal("Something Went Wrong", 'Username, Email or Phone Number is currently in use', "error");
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

user.controller("listUserCtrl", function($scope, $location, $route, httpReq, constants){
    //check url for keywords
    var page = 1;
    var action = 'list'
    var search_str = ''
    var page_str = '' 
    if($location.search().page){
        page = $location.search().page;
        var page_str = 'page='+page;
    }
    if($location.search().keyword){
        var keyword = $location.search().keyword;
        $scope.keyword = keyword;
        search_str = '&keyword='+keyword;
        action = 'search'
    }
    httpReq.send({
        url: '/staff/'+action+'/?' + page_str + search_str,
        method: 'GET',
        callback: {
            success: response => {
                $scope.users = response.data;
                //check the number of data in response data
                if(Object.keys(response.data).length >= 20){
                    $scope.show_next = true;
                    page > 1 ? $scope.show_prev = true : $scope.show_prev = false;
                }
                else {
                    $scope.show_next = false;
                    page > 1 ? $scope.show_prev = true : $scope.show_prev = false;
                }
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
            $location.url('/user/list/?keyword='+ keyword)
        }
        else{
            $location.url('/user/list/')
        }
    }

    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        $location.url('/user/list/?page='+new_page + search_str);
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        $location.url('/user/list/?page='+new_page + search_str);
    }

    //list button handler
    $scope.edit = username => {
        $location.url('/user/edit?username='+username)
    }

    //activate button handler
    $scope.activate = username =>{
        httpReq.send({
            url: '/staff/status/',
            method: 'POST',
            data: {username:username,status:'active'},
            callback:{
                success: response => {
                    swal('Success', response.data.success, 'success')
                    $route.reload()
                }
            }
        })
    }

    //daectivate button handler
    $scope.deactivate = username =>{
        httpReq.send({
            url: '/staff/status/',
            method: 'POST',
            data: {username:username,status:'inactive'},
            callback:{
                success: response => {
                    swal('Success', response.data.success, 'success')
                    $route.reload()
                }
            }
        })
    }

    //reset button handler
    $scope.reset = username =>{
        httpReq.send({
            url: '/staff/reset/',
            method: 'POST',
            data: {username:username},
            callback:{
                success: response => {
                    swal('Success', response.data.success, 'success')
                    $route.reload()
                }
            }
        })
    }
});

user.controller("editUserCtrl", function($scope, httpReq, $location){
    //Initialize data
        httpReq.send({
           url: '/staff/get/?username='+$location.search().username,
           method: 'GET',
           callback: {
               success: response => {
                $scope.data = response.data;
               }
           }
        })

    $scope.edit = () => {
        httpReq.send({
           url: '/staff/edit/?username='+$location.search().username,
           method: 'POST',
           data: $scope.data,
           callback: {
               success: response => {
                    swal("Success", 'User edited successfully', "success");
               }
           }
        });
    }
});

//User profile controller
user.controller("profileCtrl", function($scope, httpReq, constants){
    //Initialize data
    httpReq.send({
        url: '/staff/get/',
        method: 'GET',
        callback: {
            success: response => {
             $scope.data = response.data;
            }
        }
     })
    
    //Update button action performed
    $scope.update = function(){
        if($scope.pwd.old && $scope.pwd.new && $scope.verify_pwd){
            if($scope.pwd.new.length >= 8){
                if($scope.pwd.new === $scope.verify_pwd){
                    httpReq.send({
                        url: '/change-password/',
                        method: 'POST',
                        data: {old_password:$scope.pwd.old, password:$scope.pwd.new, retype_password:$scope.verify_pwd},
                        callback: {
                            success: response => {
                                 swal("Success", 'Password changed successfully', "success");
                                 window.location.assign(constants.main_login)
                            }
                        }
                     })
                }
                else{
                    swal("Warning", 'Password does not match', "warning");
                }
            }
            else{
                swal("Warning", 'Password must be 8 characters or more', "warning");
            }
        }
        else{
            swal("Warning", 'All fields are required', "warning");
        }
    }
});