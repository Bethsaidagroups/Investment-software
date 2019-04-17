/**
 * The Customer angular controller
 */
'use strict';

user.controller("addCustomerCtrl", function($scope, httpReq, showAlert){
    //default settings
    $scope.marketer = true;
    //next Marketer action performed
    $scope.nextMarketer = function(){
        //get customers from server
        httpReq.send('/marketer/search/' + $scope.form.marketer,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    showAlert.success('Marketer: "' + $scope.form.marketer + '" was selected');
                    $scope.marketer = false;
                    $scope.bio_data = true;
                }
            },
            error: function(response){
                switch(response.status){
                    case 400:{
                        showAlert.warning("Marketer does not seem to exist");
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
    //nextBio action performed
    $scope.nextBio = function(){
        $scope.bio_data = false;
        $scope.id_data = true;
    }
    //backBio action performed
    $scope.backBio = function(){
        $scope.bio_data = false;
        $scope.marketer = true;
    }

    //nextId action performed
    $scope.nextId = function(){
        $scope.id_data = false;
        $scope.employment_data = true;
    }
    //backId action performed
    $scope.backId = function(){
        $scope.bio_data = true;
        $scope.id_data = false;
    }

    //nextEmploy action performed
    $scope.nextEmploy = function(){
        $scope.employment_data = false;
        $scope.kin_data = true;
    }
    //backEmploy action performed
    $scope.backEmploy = function(){
        $scope.employment_data = false;
        $scope.id_data = true;
    }

    //nextKin action performed
    $scope.nextKin = function(){
        $scope.savings_plan = true;
        $scope.kin_data = false;
    }
    //backKin action performed
    $scope.backKin = function(){
        $scope.kin_data = false;
        $scope.employment_data = true;
    }

    //backKin action performed
    $scope.backPlan = function(){
        $scope.savings_plan = false;
        $scope.kin_data = true;
    }
    //submit button action performed
    $scope.submit = function(){
        var data = $scope.form;
        data.id = "";
        data.account_no = "";
        data.office = "";
        data.registered_by = "";
        data.registration_date = "";
        httpReq.send('/customer/add',JSON.stringify(data),'POST',
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

//Customer management controller
user.controller("manageCustomerCtrl", function($scope, $location, $route, httpReq, showAlert){
    //set default search keys
    var keyParams = {accountQQno:'Customer Account No', marketer:'Marketer Username', registeredQQby:'Registered By', office:'Registration Branch'};
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
            $location.url('/manage-customer?key=' + $scope.key + '&value=' + $scope.value);
        }
        else{
            showAlert.warning("Select correct search parameters");
        }
    }
    //build Parameters
    var page = 1;
    var key = "";
    var value = "";
    var url = '/customer/view';
    if($location.search().page){
        page = $location.search().page;
    }
    if($location.search().key && $location.search().value){
        key = $location.search().key;
        value = $location.search().value;
        url = url + '/search/' + key + '/' + value;
    }
    url = url + '/' + page;
    //get customers from database
    httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.customers = response.data;
                    $scope.page = page;
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
                        showAlert.warning(response.data);
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
        $location.url('/edit-customer?id=' + id);
    }
    //View btn action performed
    $scope.viewBtn = function(account_no){
        $location.url('/manage-savings?key=accountQQno&value=' + account_no);
    }
    //Delete btn action performed
    $scope.deleteBtn = function(id){
        /**
        if(confirm("Are sure you want to delete this customer ?")){
            var url = '/customer/delete/' + id
            httpReq.send(url,null,'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        showAlert.success('Customer deleted successfully');
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
        */
       showAlert.warning('cannot delete customer account');
    }
    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        if($location.search().value && $location.search().key){
            $location.url('/manage-customer?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/manage-customer?page=' + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().value && $location.search().key){
            $location.url('/manage-customer?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/manage-customer?page=' + new_page);
        }
    }
});

//Edit  user controller
user.controller("editCustomerCtrl", function($scope, $location, $route, httpReq, showAlert){
    //get customer from database
    var url = "/customer/" + $location.search().id;
    httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.form = response.data;
                    $scope.image_url = '../contents/images/customers/'+ response.data.account_no + '.jpg' + '?' + new Date().getTime();
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
        httpReq.uploadFileToUrl($scope.profile_img, '/customer/upload/' + $scope.form.account_no,
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
        var url = "/customer/edit/" + id;
        var data = JSON.stringify($scope.form);
        httpReq.send(url,data,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    showAlert.success("Customer updated successfully");
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

//The customers controller for Director
user.controller("customersCtrl", function($scope, $location, $route, httpReq, showAlert){
    //set default search keys
    var keyParams = {accountQQno:'Customer Account No', marketer:'Marketer Username', registeredQQby:'Registered By', office:'Registration Branch'};
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
            $location.url('/customers?key=' + $scope.key + '&value=' + $scope.value);
        }
        else{
            showAlert.warning("Select correct search parameters");
        }
    }
    //build Parameters
    var page = 1;
    var key = "";
    var value = "";
    var url = '/customer/view';
    if($location.search().page){
        page = $location.search().page;
    }
    if($location.search().key && $location.search().value){
        key = $location.search().key;
        value = $location.search().value;
        url = url + '/search/' + key + '/' + value;
    }
    url = url + '/' + page;
    //get customers from database
    httpReq.send(url,null,'POST',
        {
            success: function(response){
                if(response.status === 200){
                    $scope.customers = response.data;
                    $scope.page = page;
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
                        showAlert.warning(response.data);
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
    //Set next and previous action performed
    $scope.next = function(){
        var new_page = parseInt(page) + 1;
        if($location.search().value && $location.search().key){
            $location.url('/customers?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/customers?page=' + new_page);
        }
    }
    $scope.prev = function(){
        var new_page = parseInt(page) - 1;
        if($location.search().value && $location.search().key){
            $location.url('/customers?key=' + key + '&value=' + value + "&page=" + new_page);
        }
        else{
            $location.url('/customers?page=' + new_page);
        }
    }
});
