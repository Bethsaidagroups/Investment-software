/**
 * Fixed Deposit controllers
 */

user.controller("listFixedCtrl", function($scope, $location, $route, httpReq, constants){
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
    if($location.search().id){
        var id = $location.search().id;
        search_str = 'search_by=id&keyword='+id;
        action = 'search'
    }
    httpReq.send({
        url: '/fixed/'+action+'/?' + page_str + search_str,
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
            $location.url('/fixed/list/?keyword='+ keyword)
        }
        else{
            $location.url('/fixed/list/')
        }
    }

    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        $location.url('/fixed/list/?page='+new_page + search_str);
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        $location.url('/fixed/list/?page='+new_page + search_str);
    }
    $scope.record = id => {
        $location.url('/fixed/record/list/?keyword='+id);
    }

    //list button handler
    $scope.profile= account_id => {
        $location.url('/customer/list/?keyword='+account_id)
    } 
});

//Fixed Deposit record controller
user.controller("listFixedRecordCtrl", function($scope, $location, $route, httpReq, constants){
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
        url: '/fixed/record/'+action+'?order_by=-timestamp' + page_str + search_str,
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
            $location.url('/fixed/record/list/?keyword='+ keyword)
        }
        else{
            $location.url('/fixed/record/list/')
        }
    }

    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        $location.url('/fixed/record/list/?page='+new_page + search_str);
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        $location.url('/fixed/record/list/?page='+new_page + search_str);
    }

    //list button handler
    $scope.view= id => {
        $location.url('/fixed/list/?id='+id)
    } 
});