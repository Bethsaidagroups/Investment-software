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

    //User Controllers
    .when('/user/register', {
        templateUrl: 'views/user/register.html',
        controller:  'registerUserCtrl'
    })
    .when('/user/list', {
        templateUrl: 'views/user/list.html',
        controller:  'listUserCtrl'
    })
    .when('/user/edit', {
        templateUrl: 'views/user/edit.html',
        controller:  'editUserCtrl'
    })
    .when('/user/profile', {
        templateUrl: 'views/user/profile.html',
        controller:  'profileCtrl'
    })

    //Customer Controllers
    .when('/customer/list', {
        templateUrl: 'views/customer/list.html',
        controller:  'listCustomerCtrl'
    })

    //savings Controllers
    .when('/savings/list', {
        templateUrl: 'views/savings/list.html',
        controller:  'listSavingsCtrl'
    })
    .when('/savings/record/list', {
        templateUrl: 'views/savings/record-list.html',
        controller:  'listSavingsRecordCtrl'
    })
    .when('/direct/list', {
        templateUrl: 'views/savings/direct-list.html',
        controller:  'listDirectTransCtrl'
    })
    .when('/withdrawal/list', {
        templateUrl: 'views/savings/withdrawal.html',
        controller:  'listWithdrawalCtrl'
    })

    //target savings Controllers
    .when('/target/list', {
        templateUrl: 'views/target/list.html',
        controller:  'listTargetCtrl'
    })
    .when('/target/record/list', {
        templateUrl: 'views/target/record-list.html',
        controller:  'listTargetRecordCtrl'
    })

    //Fixed Deposit Controllers
    .when('/fixed/list', {
        templateUrl: 'views/fixed/list.html',
        controller:  'listFixedCtrl'
    })
    .when('/fixed/record/list', {
        templateUrl: 'views/fixed/record-list.html',
        controller:  'listFixedRecordCtrl'
    })

    //Investment Class Controllers
    .when('/investment/add', {
        templateUrl: 'views/investment/add.html',
        controller:  'addInvestmentCtrl'
    })
    .when('/investment/list', {
        templateUrl: 'views/investment/list.html',
        controller:  'listInvestmentCtrl'
    })
    .when('/investment/edit', {
        templateUrl: 'views/investment/edit.html',
        controller:  'editInvestmentCtrl'
    })

    //Notification Controllers
    .when('/notification/add', {
        templateUrl: 'views/notification/add.html',
        controller:  'addNotificationCtrl'
    })
    .when('/notification/list', {
        templateUrl: 'views/notification/list.html',
        controller:  'listNotificationCtrl'
    })

    //Settings Option Controller
    .when('/settings/options', {
        templateUrl: 'views/settings/options.html',
        controller:  'optionsCtrl'
    })

    $locationProvider.html5Mode(false).hashPrefix('!');
}]);