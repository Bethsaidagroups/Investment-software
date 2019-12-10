user.controller("listCustomerCtrl", function($scope, $location, $route, httpReq, constants){
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
        url: '/user/'+action+'/?' + page_str + search_str,
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
        if(status == true){
            return true;
        }
    }
    $scope.isInactive = status => {
        if(status == false){
            return true;
        }
    }

    $scope.search = () =>{
        var keyword = $scope.keyword;
        if(keyword){
            $location.url('/customer/list/?keyword='+ keyword)
        }
        else{
            $location.url('/customer/list/')
        }
    }

    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        $location.url('/customer/list/?page='+new_page + search_str);
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        $location.url('/customer/list/?page='+new_page + search_str);
    }

    //list button handler
    $scope.view = index => {
        $scope.customer = $scope.users[index];
        $scope.show_modal = true;
    }
    var recipients;
    $scope.send_noti = index => {
        $scope.this_customer = $scope.users[index];
        recipients =  $scope.users[index].account_id;
        $scope.show_modal_noti = true;
    }
    $scope.close = () => {
        $scope.show_modal = false;
        $scope.show_modal_noti = false;
    }
    //activate button handler
    $scope.activate = account_id =>{
        httpReq.send({
            url: '/user/access/',
            method: 'POST',
            data: {account_id:account_id,access:'true'},
            callback:{
                success: response => {
                    swal('Success', response.data.success, 'success')
                    $route.reload()
                }
            }
        })
    }

    //daectivate button handler
    $scope.deactivate = account_id =>{
        httpReq.send({
            url: '/user/access/',
            method: 'POST',
            data: {account_id:account_id,access:'false'},
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

    //send single notification
    $scope.send = () => {
        $scope.data.recipients = recipients
        httpReq.send({
           url: '/notification/create/',
           method: 'POST',
           data: $scope.data,
           callback: {
               success: response => {
                    swal("Success", 'Notification sent successfully', "success");
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
})