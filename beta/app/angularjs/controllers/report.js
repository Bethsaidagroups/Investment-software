/**
 * The User angular controller
 */
'use strict';

user.controller("detailedReportCtrl", function($scope, $location, $route, httpReq, showAlert){
    $scope.soa = {};
    if($location.search().init){
        $scope.soa.account_no = $location.search().init;
    }
    $scope.generateSOA = function(){
        if($scope.soa.account_no && $scope.soa.from_date && $scope.soa.to_date){
            var from = 
            httpReq.send('/report/soa',JSON.stringify($scope.soa),'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        $scope.period = $scope.soa.from_date + " - " + $scope.soa.to_date;
                        $scope.meta = response.data.meta;
                        $scope.lists = response.data.data;
                        $scope.show_soa_box = true;
                    }
                }
            });
        }
        else{
            showAlert.warning('You are required to fill out all fields to generate Stetement of Account');
        }
    }
    
    $scope.generateBranchReport = function(){
        if($scope.branch.category && $scope.branch.type && $scope.branch.from_date && $scope.branch.to_date){
            var from = 
            httpReq.send('/report/branch',JSON.stringify($scope.branch),'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        $scope.period = $scope.branch.from_date + " - " + $scope.branch.to_date;
                        $scope.meta = response.data.meta;
                        $scope.lists = response.data.data;
                        $scope.show_branch_box = true;
                    }
                }
            });
        }
        else{
            showAlert.warning('You are required to fill out all fields to generate Stetement of Account');
        }
    }


    $scope.printSoa = function(){
        $('#print-area-soa').printThis({
            importCSS: true,
            importStyle: true,
            loadCSS: "/laser/view-lib/bootstrap/css/bootstrap.min.css",
            pageTitle: 'Bethsaida Investment Partners'
        });
    }
    $scope.printBranch = function(){
        $('#print-area-branch').printThis({
            importCSS: true,
            importStyle: true,
            loadCSS: "/laser/view-lib/bootstrap/css/bootstrap.min.css",
            pageTitle: 'Bethsaida Investment Partners'
        });
    }
    $scope.closeModalBox = function(){
        $scope.show_soa_box = false;
        $scope.show_branch_box = false;
    }
});

/**
 * The User angular controller
 */
'use strict';

user.controller("dailyReportCtrl", function($scope, $location, $route, httpReq, showAlert){
    httpReq.send('/report/daily',JSON.stringify($scope.soa),'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        $scope.today = response.data.meta.date;
                        $scope.report = response.data.data;
                    }
                }
            });
});

user.controller("centralDetailedReportCtrl", function($scope, $location, $route, httpReq, showAlert){
    $scope.soa = {};
    if($location.search().init){
        $scope.soa.account_no = $location.search().init;
    }
    //get offices from localstorage
    $scope.offices = JSON.parse(localStorage.getItem('offices'));
    
    $scope.generateSOA = function(){
        if($scope.soa.account_no && $scope.soa.from_date && $scope.soa.to_date){
            var from = 
            httpReq.send('/report/soa',JSON.stringify($scope.soa),'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        $scope.period = $scope.soa.from_date + " - " + $scope.soa.to_date;
                        $scope.meta = response.data.meta;
                        $scope.lists = response.data.data;
                        $scope.show_soa_box = true;
                    }
                }
            });
        }
        else{
            showAlert.warning('You are required to fill out all fields to generate Stetement of Account');
        }
    }
    
    $scope.generateBranchReport = function(){
        if($scope.branch.office && $scope.branch.category && $scope.branch.type && $scope.branch.from_date && $scope.branch.to_date){
            var from = 
            httpReq.send('/report/general',JSON.stringify($scope.branch),'POST',
            {
                success: function(response){
                    if(response.status === 200){
                        $scope.period = $scope.branch.from_date + " - " + $scope.branch.to_date;
                        $scope.meta = response.data.meta;
                        $scope.lists = response.data.data;
                        $scope.show_branch_box = true;
                    }
                }
            });
        }
        else{
            showAlert.warning('You are required to fill out all fields to generate Stetement of Account');
        }
    }


    $scope.printSoa = function(){
        $('#print-area-soa').printThis({
            importCSS: true,
            importStyle: true,
            loadCSS: "/laser/view-lib/bootstrap/css/bootstrap.min.css",
            pageTitle: 'Bethsaida Investment Partners'
        });
    }
    $scope.printBranch = function(){
        $('#print-area-branch').printThis({
            importCSS: true,
            importStyle: true,
            loadCSS: "/laser/view-lib/bootstrap/css/bootstrap.min.css",
            pageTitle: 'Bethsaida Investment Partners'
        });
    }
    $scope.closeModalBox = function(){
        $scope.show_soa_box = false;
        $scope.show_branch_box = false;
    }
});