/**
 * The angular app configuration script for routing
 */
'use strict';

user.config(['$routeProvider', '$locationProvider', 
function ($routeProvider,$locationProvider){
    $routeProvider.
    //Default controller
    when('/', {
        templateUrl: 'views/common/home.html',
        controller:  'homeCtrl'
    })
    //Route Provider for Investment
    .when('/customer/register', {
        templateUrl: 'views/customer/register.html',
        controller:  'addCustomerCtrl'
    })
    .when('/customer/list', {
        templateUrl: 'views/customer/list.html',
        controller:  'listCustomerCtrl'
    })
    .when('/customer/central/list', {
        templateUrl: 'views/customer/central-list.html',
        controller:  'centralListCustomerCtrl'
    })
    .when('/customer/edit', {
        templateUrl: 'views/customer/edit.html',
        controller:  'editCustomerCtrl'
    })
    .when('/savings/init-withdrawal', {
        templateUrl: 'views/savings/init-withdrawal.html',
        controller:  'initWithdrawalCtrl'
    })
    .when('/savings/make-withdrawal', {
        templateUrl: 'views/savings/make-withdrawal.html',
        controller:  'makeWithdrawalCtrl'
    })
    .when('/savings/init-deposit', {
        templateUrl: 'views/savings/init-deposit.html',
        controller:  'initDepositCtrl'
    })
    .when('/savings/make-deposit', {
        templateUrl: 'views/savings/make-deposit.html',
        controller:  'makeDepositCtrl'
    })
    .when('/savings/edit', {
        templateUrl: 'views/savings/edit.html',
        controller:  'editSavingsCtrl'
    })
    .when('/savings/list', {
        templateUrl: 'views/savings/list.html',
        controller:  'listSavingsCtrl'
    })
    .when('/savings/central/list', {
        templateUrl: 'views/savings/central-list.html',
        controller:  'centralListSavingsCtrl'
    })
    .when('/loan/add', {
        templateUrl: 'views/loan/add.html',
        controller:  'addLoanCtrl'
    })
    .when('/loan/list', {
        templateUrl: 'views/loan/list.html',
        controller:  'listLoanRecordCtrl'
    })
    .when('/loan/central/list', {
        templateUrl: 'views/loan/central-list.html',
        controller:  'centralListLoanCtrl'
    })
    .when('/transaction/list', {
        templateUrl: 'views/transaction/list.html',
        controller:  'listTransactionCtrl'
    })
    .when('/transaction/central/list', {
        templateUrl: 'views/transaction/central-list.html',
        controller:  'centralListTransactionCtrl'
    })
    .when('/user/profile', {
        templateUrl: 'views/user/profile.html',
        controller:  'profileCtrl'
    })
    .when('/report/detailed', {
        templateUrl: 'views/report/detailed.html',
        controller:  'detailedReportCtrl'
    })
    .when('/report/daily', {
        templateUrl: 'views/report/daily.html',
        controller:  'dailyReportCtrl'
    })
    .when('/report/central/detailed', {
        templateUrl: 'views/report/central-detailed.html',
        controller:  'centralDetailedReportCtrl'
    })
    .when('/investrite/login', {
        templateUrl: 'views/investrite-login.html',
        controller:  'investriteCtrl'
    })

    $locationProvider.html5Mode(false).hashPrefix('!');
}]);