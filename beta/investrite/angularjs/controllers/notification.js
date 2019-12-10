/**
 * The user controller
 */
'use strict';

user.controller("addNotificationCtrl", function($scope, $location, $window, httpReq, constants){
    $scope.send = () => {
        httpReq.send({
           url: '/notification/create/',
           method: 'POST',
           data: $scope.data,
           callback: {
               success: response => {
                    swal("Success", 'Notification created successfully', "success");
                    $location.url('/notification/list?keyword='+ $scope.data.subject)
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

user.controller("listNotificationCtrl", function($scope, $location, $route, httpReq, constants){
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
        url: '/notification/'+action+'/?' + page_str + search_str,
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

    $scope.search = () =>{
        var keyword = $scope.keyword;
        if(keyword){
            $location.url('/notification/list/?keyword='+ keyword)
        }
        else{
            $location.url('/notification/list/')
        }
    }

    $scope.view = index => {
        $scope.view_data = $scope.users[index];
        $scope.show_modal = true;
    }
    $scope.close = () => {
        $scope.show_modal = false;
    }

    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        $location.url('/notification/list/?page='+new_page + search_str);
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        $location.url('/notification/list/?page='+new_page + search_str);
    }

    //list button handlers

    $scope.delete = id => {
        swal({
            title: "Confirmation",
            text: "Are you sure you want to delete this Notification",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete){
                httpReq.send({
                    url: '/notification/delete/',
                    method: 'POST',
                    data: {key: 'id', value: id},
                    callback: {
                        success: response => {
                            swal('Succes', 'Notification deleted successfully', 'success');
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

    $scope.delete_bulk = () => {
        swal({
            title: "Bulk Action Confirmation",
            text: "Are you sure you want to delete all notification in this category",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete){
                //check if key and key word is set
                if($scope.key && $scope.keyword){
                    httpReq.send({
                        url: '/notification/delete/',
                        method: 'POST',
                        data: {key: $scope.key, value: $scope.keyword},
                        callback: {
                            success: response => {
                                swal('Succes', response.data.success, 'success');
                                $location.url('/notification/list')
                            }
                        }
                    });
                }
                else{
                    swal('Wrong Entry', "Incorrect Entry, set 'keyword' and 'using' parameters", 'error')
                }
            }
            else{
                //Do nothing
            }
        })
    } 
});