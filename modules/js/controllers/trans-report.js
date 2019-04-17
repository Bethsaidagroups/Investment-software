/**
 * The Transaction Report angular controller
 */
'use strict';

user.controller("transReportCtrl", function($scope, $location, $route, httpReq, showAlert){
    //set default search keys
    var keyParams = {all:'All', accountQQno:'Savings Account No',category:'Transaction Category',status:'Transaction Status', type:'Transaction Type', channel:'Transaction Channel'};
    var categories = ['Savings Deposit', 'Savings Withdrawal', 'Loan Payout', 'Loan Excess', 'Invoice Payment', 'Fixed Deposit', 'Savings Invoice Payment', 'Target Savings'];
    var types = {credit:'Credit', debit:'Debit'};
    var statuses = {completed:'Completed', pending:'Pending', declined:'Declined'};
    var channels = {savings:'Savings Account', cheque:'Cheque', transfer:'Transfer', cash:'Cash'};
    $scope.list = JSON.parse(localStorage.getItem('offices')); //set offices
    $scope.keys = keyParams;
    $scope.categories = categories
    $scope.types = types;
    $scope.statuses = statuses;
    $scope.channels = channels;

    $scope.office = '1';
    $scope.key = 'all';
    $scope.value = "all";
    $scope.unit = 'invest';
    $scope.invest = true;
    //Unit action performed
    $scope.reportUnit = function(){
        if($scope.unit == 'invest'){
            $scope.invest = true;
            $scope.eng = false;
            $scope.estate = false;
        }
        else if($scope.unit == 'eng'){
            $scope.invest = false;
            $scope.eng = true;
            $scope.estate = false;
        }
        else{
            $scope.invest = false;
            $scope.eng = false;
            $scope.estate = true;
        }
    }
    //Sub category Key Change Action performed
    $scope.keyChange = function(){
        if($scope.key == 'category'){
            $scope.value = "";
            $scope.value_input = false;
            $scope.date_input = false;
            $scope.category_select = true;
            $scope.type_select = false;
            $scope.status_select = false;
            $scope.channel_select = false;
        }
        else if($scope.key == 'type'){
            $scope.value = "";
            $scope.value_input = false;
            $scope.date_input = false;
            $scope.category_select = false;
            $scope.type_select = true;
            $scope.status_select = false;
            $scope.channel_select = false;
        }
        else if($scope.key == 'status'){
            $scope.value = "";
            $scope.value_input = false;
            $scope.date_input = false;
            $scope.category_select = false;
            $scope.type_select = false;
            $scope.status_select = true;
            $scope.channel_select = false;
        }
        else if($scope.key == 'channel'){
            $scope.value = "";
            $scope.value_input = false;
            $scope.date_input = false;
            $scope.category_select = false;
            $scope.type_select = false;
            $scope.status_select = false;
            $scope.channel_select = true;
        }
        else if($scope.key == 'all'){
            $scope.value = "all";
            $scope.value_input = false;
            $scope.date_input = false;
            $scope.category_select = false;
            $scope.type_select = false;
            $scope.status_select = false;
            $scope.channel_select = false;
        }
        else{
            $scope.value = "";
            $scope.value_input = true;
            $scope.category_select = false;
            $scope.date_input = false;
            $scope.type_select = false;
            $scope.status_select = false;
            $scope.channel_select = false;
            $scope.selected_key = keyParams[$scope.key];
        }
    }

    //get button action performed
    $scope.getBtn = function(){
        if($scope.unit && $scope.key && $scope.value && $scope.office && $scope.frame.from_date && $scope.frame.to_date){
            var url = '/report/' + $scope.office + '/' + $scope.unit + '/' + $scope.key + '/' + $scope.value + '/' + $scope.frame.from_date + 'HH' + $scope.frame.to_date

            //get Savings accounts from database
            httpReq.send(url,null,'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        $scope.report = true;
                        $scope.total_trans = response.data[1].total_trans;
                        $scope.total_amount = response.data[1].total_amount;
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
            showAlert.warning('Invalid report parameter. Make sure you select appropriate parameters');
        }
    }
});