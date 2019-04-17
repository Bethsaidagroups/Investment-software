/**
 * The Office angular controller
 */
'use strict';

user.controller("addOfficeCtrl", function($scope, httpReq, showAlert){
    //submit button action performed
    $scope.submit = function(){
        //add default values to data
        var data = new Object();
        data = $scope.officeList;
        data.id = "";
        httpReq.send('/office/add',JSON.stringify(data),'POST',
    {
        success: function(response){
            if(response.status === 200){
                showAlert.success(response.data.success);
            }
        },
        error: function(response){
            switch(response.status){
                case 400:{
                    showAlert.warning(response.data.error);
                    break;
                }
                case 403:{
                    showAlert.danger(response.data.auth + ' | ' +  response.data.access);
                    break;
                }
                default:{
                    showAlert.warning("unknown internal error, workspace might be empty. Hint: Reload browser window");
                }
            }
        }
    });
   }
});

//Office management controller
user.controller("manageOfficeCtrl", function($scope, $location, $route, httpReq, showAlert){
    //set default search keys
    var keyParams = {id:'Office Id', location:'Location'};
    var data_no = 0;
    $scope.keys = keyParams;
    //Key Change Action performed
    $scope.keyChange = function(){
            $scope.value = "";
            $scope.value_input = true;
            $scope.selected_key = keyParams[$scope.key];
    }
    if($location.search().key && $location.search().value){
        $scope.key = $location.search().key;
        $scope.value = $location.search().value;
        $scope.value_input = true;
        $scope.selected_key = keyParams[$scope.key];
    }
    //search action performed
    $scope.searchBtn = function(){
        if($scope.key != null && $scope.value != null){
            $location.url('/manage-office?key=' + $scope.key + '&value=' + $scope.value);
        }
        else{
            showAlert.warning("Select correct search parameters");
        }
    }
    //build Parameters
    var page = 1;
    var key = "";
    var value = "";
    var url = '/office/view';
    if($location.search().page){
        page = $location.search().page;
    }
    if($location.search().key && $location.search().value){
        key = $location.search().key;
        value = $location.search().value;
        url = url + '/search/' + key + '/' + value;
    }
    url = url + '/' + page;
    //get office from database
    httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.officeLists = response.data;
                    //check the number of data in response data
                    for(var data in response.data){
                        data_no++;
                    }
                    data_no < 15 ? $scope.show_next = false : $scope.show_next = true;
                    page > 1 ? $scope.show_prev = true : $scope.show_prev = false;
                }
            },
            error: function(response){
                switch(response.status){
                    case 400:{
                        showAlert.warning(response.data.error);
                        break;
                    }
                    case 403:{
                        showAlert.danger(response.data.auth + ' | ' +  response.data.access);
                        break;
                    }
                    default:{
                        showAlert.warning("unknown internal error, workspace might be empty. Hint: Reload browser window");
                    }
                }
            }
        });
    //Edit action performed
    $scope.editBtn = function(id){
        $location.url('/edit-office?id=' + id);
    }
    //Delete btn action performed
    $scope.deleteBtn = function(id){
        if(confirm("Are sure you want to delete this Office ?")){
            var url = '/office/delete/' + id
            httpReq.send(url,null,'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        showAlert.success('Office deleted successfully');
                        $route.reload();
                    }
                },
                error: function(response){
                    switch(response.status){
                        case 400:{
                            showAlert.warning(response.data.error);
                            break;
                        }
                        case 403:{
                            showAlert.danger(response.data.auth + ' | ' +  response.data.access);
                            break;
                        }
                        default:{
                            showAlert.warning("unknown internal error, workspace might be empty. Hint: Reload browser window");
                        }
                    }
                }
            });
        }
    }
    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        if($location.search().value && $location.search().key){
            $location.url('/manage-office?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/manage-office?page=' + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().value && $location.search().key){
            $location.url('/manage-office?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/manage-office?page=' + new_page);
        }
    }
});

//Edit  Office controller
user.controller("editOfficeCtrl", function($scope, $location, $route, httpReq, showAlert){
    //get offices from database
    var url = "/office/" + $location.search().id;
    httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.officeList = response.data;
                }
            },
            error: function(response){
                switch(response.status){
                    case 400:{
                        showAlert.warning(response.data.error);
                        break;
                    }
                    case 403:{
                        showAlert.danger(response.data.auth + ' | ' +  response.data.access);
                        break;
                    }
                    default:{
                        showAlert.warning("unknown internal error, workspace might be empty. Hint: Reload browser window");
                    }
                }
            }
        });
    
    //Update button action performed
    $scope.update = function(id){
        var url = "/office/edit/" + id;
        var data = JSON.stringify($scope.officeList);
        httpReq.send(url,data,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    showAlert.success("Office updated successfully");
                    $route.reload();
                }
            },
            error: function(response){
                switch(response.status){
                    case 400:{
                        showAlert.warning(response.data.error);
                        break;
                    }
                    case 403:{
                        showAlert.danger(response.data.auth + ' | ' +  response.data.access);
                        break;
                    }
                    default:{
                        showAlert.warning("unknown internal error, workspace might be empty. Hint: Reload browser window");
                    }
                }
            }
        });
    }
});