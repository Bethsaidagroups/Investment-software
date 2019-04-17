/**
 * The User angular controller
 */
'use strict';

user.controller("addUserCtrl", function($scope, httpReq, showAlert){
    //default settings
    $scope.user_login = true;
    //get offices and user types from server
    httpReq.send('/user/get',null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.types = response.data.types;
                    $scope.offices = response.data.offices;

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
        //set continue action performed
        $scope.continue = function(){
            if($scope.user && $scope.user.username && $scope.user.user_type && $scope.user.office && $scope.user.password){
                $scope.user_bio = true;
                $scope.user_login = false;
            }
            else{
                showAlert.warning("Please fill all the form fields correctly");
            }
        }
        //set back action performed
        $scope.back = function(){
            $scope.user_bio = false;
            $scope.user_login = true;
        }

        //submit button action performed
        $scope.submit = function(){
            //add default values to data
            var data = new Object();
            data = $scope.user;
            data.id = "";
            data.access = "0";
            data.last_login = "";
            httpReq.send('/user/add',JSON.stringify(data),'POST',
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
});

//User management controller
user.controller("manageUserCtrl", function($scope, $location, $route, httpReq, showAlert){
    //set default search keys
    var keyParams = {id:'User Id', username:'Username', office:'Office Location'};
    var data_no = 0;
    $scope.keys = keyParams;
    //Key Change Action performed
    $scope.list = JSON.parse(localStorage.getItem('offices')); //set offices
    $scope.keyChange = function(){
        if($scope.key == 'office'){
            $scope.value = "";
            $scope.value_input = false;
            $scope.value_select = true;
        }
        else{
            $scope.value = "";
            $scope.value_input = true;
            $scope.value_select = false;
            $scope.selected_key = keyParams[$scope.key];
        }
    }
    if($location.search().key && $location.search().value){
        $scope.key = $location.search().key;
        $scope.value = $location.search().value;
        //set appropriate forms
        if($scope.key == 'office'){
            $scope.value_select = true;
        }
        else{
            $scope.value_input = true;
            $scope.selected_key = keyParams[$scope.key];
        }
    }
    //search action performed
    $scope.searchBtn = function(){
        if($scope.key != null && $scope.value != null){
            $location.url('/manage-user?key=' + $scope.key + '&value=' + $scope.value);
        }
        else{
            showAlert.warning("Select correct search parameters");
        }
    }
    //build Parameters
    var page = 1;
    var key = "";
    var value = "";
    var url = '/user/view';
    if($location.search().page){
        page = $location.search().page;
    }
    if($location.search().key && $location.search().value){
        key = $location.search().key;
        value = $location.search().value;
        url = url + '/search/' + key + '/' + value;
    }
    url = url + '/' + page;
    //get users from database
    httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.users = response.data;
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
                        showAlert.danger(response.data.auth + ' | ' + response.data.access);
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
        $location.url('/edit-user?id=' + id);
    }
    //Reset btn action performed
    $scope.resetBtn = function(id){
        if(confirm("Are sure you want to reset this user's password ? New password will be generated immediately")){
            var url = '/user/reset/' + id
            httpReq.send(url,null,'POST',
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
    }
    //Delete btn action performed
    $scope.deleteBtn = function(id){
        if(confirm("Are sure you want to delete this user ?")){
            var url = '/user/delete/' + id
            httpReq.send(url,null,'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        showAlert.success('User deleted successfully');
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
    }
    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        if($location.search().value && $location.search().key){
            $location.url('/manage-user?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/manage-user?page=' + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().value && $location.search().key){
            $location.url('/manage-user?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/manage-user?page=' + new_page);
        }
    }
});

//Edit  user controller
user.controller("editUserCtrl", function($scope, $location, $route, httpReq, showAlert){
    //get users from database
    var url = "/user/" + $location.search().id;
    httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.types = JSON.parse(localStorage.getItem('types'));
                    $scope.offices = JSON.parse(localStorage.getItem('offices'));
                    $scope.user = response.data;
                    $scope.image_url = '../contents/images/users/'+ response.data.username + '.jpg' + '?' + new Date().getTime();
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
    //file change action performed
    $scope.getFileName = function(){
        $("#selected-file").each(function() {
            var fileName = $(this).val().split('/').pop().split('\\').pop();
            $scope.file_name = fileName;
        });
    }
    //File upload
    $scope.doUpload = function(){
        httpReq.uploadFileToUrl($scope.profile_img, '/user/upload/' + $scope.user.username,
        {
            success: function(response){
                showAlert.success('Image uploaded successfully');
                $route.reload();
            },
            error: function(response){
                showAlert.success('Image upload failed');
            }
        }
      );
    }
    //Update button action performed
    $scope.update = function(id){
        var url = "/user/edit/" + id;
        var data = JSON.stringify($scope.user);
        httpReq.send(url,data,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    showAlert.success("User updated successfully");
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
});

//User profile controller
user.controller("profileCtrl", function($scope, $location, $route, httpReq, showAlert){
    //get users from database
    var url = "/user/profile/get/" + localStorage.getItem('username');
    httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.types = JSON.parse(localStorage.getItem('types'));
                    $scope.offices = JSON.parse(localStorage.getItem('offices'));
                    $scope.user = response.data;
                    $scope.image_url = '../contents/images/users/'+ response.data.username + '.jpg' + '?' + new Date().getTime();
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
    
    //Update button action performed
    $scope.update = function(id){
        if($scope.pwd.old && $scope.pwd.new && $scope.verify_pwd){
            if($scope.pwd.new.length >= 6){
                if($scope.pwd.new === $scope.verify_pwd){
                    //send new password to the server
                    var url = "/user/profile/pwd/" + id;
                    var data = JSON.stringify($scope.pwd);
                    httpReq.send(url,data,'POST',
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