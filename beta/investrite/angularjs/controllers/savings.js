/**
 * Savings controllers
 */

user.controller("listSavingsCtrl", function($scope, $location, $route, httpReq, constants){
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
        url: '/savings/'+action+'/?' + page_str + search_str,
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
            $location.url('/savings/list/?keyword='+ keyword)
        }
        else{
            $location.url('/savings/list/')
        }
    }

    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        $location.url('/savings/list/?page='+new_page + search_str);
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        $location.url('/savings/list/?page='+new_page + search_str);
    }

    //list button handler
    $scope.profile= account_id => {
        $location.url('/customer/list/?keyword='+account_id)
    } 
});

//Savings Transaction record controller
user.controller("listSavingsRecordCtrl", function($scope, $location, $route, httpReq, constants){
    //check url for keywords
    var page = 1;
    var action = ''
    var search_str = ''
    var page_str = '' 
    if($location.search().page){
        page = $location.search().page;
        var page_str = '&page='+page;
    }
    if($location.search().keyword){
        var keyword = $location.search().keyword;
        $scope.keyword = keyword;
        search_str = '&keyword='+keyword;
        action = 'search/'
    }
    httpReq.send({
        url: '/savings/record/'+action+'?order_by=-timestamp' + page_str + search_str,
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
            $location.url('/savings/record/list/?keyword='+ keyword)
        }
        else{
            $location.url('/savings/record/list/')
        }
    }

    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        $location.url('/savings/record/list/?page='+new_page + search_str);
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        $location.url('/savings/record/list/?page='+new_page + search_str);
    }

    //list button handler
    $scope.profile= account_id => {
        $location.url('/customer/list/?keyword='+account_id)
    } 
});

//Direct Transaction record controller
user.controller("listDirectTransCtrl", function($scope, $location, $route, httpReq, constants){
    //check url for keywords
    var page = 1;
    var action = 'list'
    var search_str = ''
    var page_str = '' 
    if($location.search().page){
        page = $location.search().page;
        var page_str = '&page='+page;
    }
    if($location.search().keyword){
        var keyword = $location.search().keyword;
        $scope.keyword = keyword;
        search_str = '&keyword='+keyword;
        action = 'search/'
    }
    httpReq.send({
        url: '/direct/'+action+'?order_by=-id' + page_str + search_str,
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
            $location.url('/direct/list/?keyword='+ keyword)
        }
        else{
            $location.url('/direct/list/')
        }
    }

    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        $location.url('/direct/list/?page='+new_page + search_str);
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        $location.url('/direct/list/?page='+new_page + search_str);
    }

    //list button handler
    $scope.profile= account_id => {
        $location.url('/customer/list/?keyword='+account_id)
    } 
});

//Direct Transaction record controller
user.controller("listWithdrawalCtrl", function($scope, $location, $route, httpReq, constants){
    //check url for keywords
    var page = 1;
    var action = 'list'
    var search_str = ''
    var page_str = '' 
    if($location.search().page){
        page = $location.search().page;
        var page_str = '&page='+page;
    }
    if($location.search().keyword){
        var keyword = $location.search().keyword;
        $scope.keyword = keyword;
        search_str = '&keyword='+keyword;
        action = 'search/'
    }
    httpReq.send({
        url: '/savings/withdrawal/'+action+'?order_by=-timestamp' + page_str + search_str,
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
            $location.url('/withdrawal/list/?keyword='+ keyword)
        }
        else{
            $location.url('/withdrawal/list/')
        }
    }

    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        $location.url('/withdrawal/list/?page='+new_page + search_str);
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        $location.url('/withdrawal/list/?page='+new_page + search_str);
    }

    //list button handler
    $scope.profile= account_id => {
        $location.url('/customer/list/?keyword='+account_id)
    }

    $scope.approve = id => {
        swal({
            title: "Confirmation",
            text: "Are you sure you want to approve this withdrawal",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willApprove) => {
            if (willApprove){
                httpReq.send({
                    url: '/savings/withdrawal/list/',
                    method: 'POST',
                    data: {id:id,status:'completed'},
                    callback:{
                        success: response => {
                            swal('Success', response.data.success, 'success')
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

    $scope.decline = id => {
        swal({
            title: "Confirmation",
            text: "Are you sure you want to decline this withdrawal",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willApprove) => {
            if (willApprove){
                httpReq.send({
                    url: '/savings/withdrawal/list/',
                    method: 'POST',
                    data: {id:id,status:'declined'},
                    callback:{
                        success: response => {
                            swal('Success', response.data.success, 'success')
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