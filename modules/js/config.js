/**
 * The angular app configuration script for routing
 */
'use strict';

user.config(['$routeProvider', '$locationProvider', 
function ($routeProvider,$locationProvider){
    $routeProvider.
    //Default controller
    when('/', {
        templateUrl: 'default.html',
        controller:  'defaultCtrl'
    })
    //Router for Administartor
    .when('/add-user', {
        templateUrl: 'administrator/views/add-user.html',
        controller:  'addUserCtrl'
    }).when('/manage-user', {
        templateUrl: 'administrator/views/manage-user.html',
        controller:  'manageUserCtrl'
    }).when('/edit-user', {
        templateUrl: 'administrator/views/edit-user.html',
        controller:  'editUserCtrl'
    }).when('/add-office', {
        templateUrl: 'administrator/views/add-office.html',
        controller:  'addOfficeCtrl'
    }).when('/manage-office', {
        templateUrl: 'administrator/views/manage-office.html',
        controller:  'manageOfficeCtrl'
    }).when('/edit-office', {
        templateUrl: 'administrator/views/edit-office.html',
        controller:  'editOfficeCtrl'
    }).when('/activity-log', {
        templateUrl: 'administrator/views/activity-log.html',
        controller:  'activityLogCtrl'
    }).when('/profile', {
        templateUrl: 'administrator/views/profile.html',
        controller:  'profileCtrl'
    })
    //Route Provider for Investment
    .when('/add-customer', {
        templateUrl: 'investment/views/add-customer.html',
        controller:  'addCustomerCtrl'
    }).when('/manage-customer', {
        templateUrl: 'investment/views/manage-customer.html',
        controller:  'manageCustomerCtrl'
    }).when('/edit-customer', {
        templateUrl: 'investment/views/edit-customer.html',
        controller:  'editCustomerCtrl'
    }).when('/manage-savings', {
        templateUrl: 'investment/views/manage-savings.html',
        controller:  'manageSavingsCtrl'
    }).when('/edit-savings', {
        templateUrl: 'investment/views/edit-savings.html',
        controller:  'editSavingsCtrl'
    }).when('/add-fixed', {
        templateUrl: 'investment/views/add-fixed.html',
        controller:  'addFixedCtrl'
    }).when('/manage-fixed', {
        templateUrl: 'investment/views/manage-fixed.html',
        controller:  'manageFixedCtrl'
    }).when('/edit-fixed', {
        templateUrl: 'investment/views/edit-fixed.html',
        controller:  'editFixedCtrl'
    }).when('/cash-out-fixed', {
        templateUrl: 'investment/views/cash-out-fixed.html',
        controller:  'cashOutFixedCtrl'
    }).when('/add-target', {
        templateUrl: 'investment/views/add-target.html',
        controller:  'addTargetCtrl'
    }).when('/manage-target', {
        templateUrl: 'investment/views/manage-target.html',
        controller:  'manageTargetCtrl'
    }).when('/target-invoice', {
        templateUrl: 'investment/views/target-invoice.html',
        controller:  'targetInvoiceCtrl'
    }).when('/make-deposit', {
        templateUrl: 'investment/views/deposit.html',
        controller:  'depositCtrl'
    }).when('/make-withdrawal', {
        templateUrl: 'investment/views/withdraw.html',
        controller:  'withdrawCtrl'
    }).when('/awaiting-trans', {
        templateUrl: 'investment/views/awaiting-trans.html',
        controller:  'awaitingCtrl'
    }).when('/acct-trans', {
        templateUrl: 'investment/views/trans-history.html',
        controller:  'manageTransCtrl'
    }).when('/add-loan', {
        templateUrl: 'investment/views/add-loan.html',
        controller:  'addLoanCtrl'
    }).when('/manage-loan', {
        templateUrl: 'investment/views/manage-loan.html',
        controller:  'manageLoanCtrl'
    }).when('/edit-loan', {
        templateUrl: 'investment/views/edit-loan.html',
        controller:  'editLoanCtrl'
    }).when('/generate-invoice', {
        templateUrl: 'investment/views/loan-invoice.html',
        controller:  'loanInvoiceCtrl'
    }).when('/manage-invoice', {
        templateUrl: 'investment/views/manage-invoice.html',
        controller:  'manageInvoiceCtrl'
    })

    //Accountant module route
    .when('/pending-trans', {
        templateUrl: 'accountant/views/pending-trans.html',
        controller:  'pendingCtrl'
    }).when('/declined-trans', {
        templateUrl: 'accountant/views/declined-trans.html',
        controller:  'declinedCtrl'
    }).when('/debit-trans', {
        templateUrl: 'accountant/views/debit-trans.html',
        controller:  'debitCtrl'
    }).when('/credit-trans', {
        templateUrl: 'accountant/views/credit-trans.html',
        controller:  'creditCtrl'
    }).when('/quick-access', {
        templateUrl: 'accountant/views/quick-access.html',
        controller:  'quickAccessCtrl'
    }).when('/trans-report', {
        templateUrl: 'accountant/views/trans-report.html',
        controller:  'transReportCtrl'
    })

    //Director Module Routes
    .when('/customers', {
        templateUrl: 'director/views/customers.html',
        controller:  'customersCtrl'
    }).when('/loans', {
        templateUrl: 'director/views/loans.html',
        controller:  'loansCtrl'
    })

    $locationProvider.html5Mode(false).hashPrefix('!');
}]);