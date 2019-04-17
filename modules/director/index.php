<?php
//The index page for the Administrator module
define('MODULE', 'Managing Director'); //Module name

//include the general required file
require_once '../required.php';

define('APP_PATH', ROOT_DIR.'/modules/director'); //Application path

//create new klein object for URL routing
$request = \Klein\Request::createFromGlobals();
$request->server()->set('REQUEST_URI', substr($_SERVER['REQUEST_URI'],  strlen(APP_PATH)));
$klein = new \Klein\Klein();

//Include OAuth Protocol and Module Auth protocol
require_once '../OAuth.php'; //OAuth

require_once '../module_auth.php'; //Module Authentication

//Klein Route for apps and view
$klein->with('/app', function () use ($klein) {
    //include Header files
    $klein->respond(function ($request, $response, $service) { require_once 'views/header.php'; });

    //Route: include controller for main app view
    $klein->respond(function ($request, $response) {
        // Show all users
    });

    //include footer files
    $klein->respond(function ($request, $response, $service) { require_once 'views/footer.php'; });

});

//Klein Route for api
$klein->with('/api', function () use ($klein) {
    /**
     * Api Route block for init.php
     */
    $klein->respond(array('POST','GET'),'/init', function ($request, $response) {
        //Get initialization variables
        require_once 'controllers/init.php';
    });

    /**
     * Routes for Controllers: user, office
     */
    $klein->respond(array('POST','GET'),'/[a:ctrl]/get', function ($request, $response) {
        //get offices and users type
        $action = 'get';
        if(strcasecmp($request->ctrl, 'user') === 0 ){
            require_once 'controllers/user.php';
        }
        elseif(strcasecmp($request->ctrl, 'office') === 0 ){
            require_once 'controllers/office.php';
        }
        else{
            http_response_code(404);
            exit();
        }
    });

    $klein->respond(array('POST','GET'),'/[a:ctrl]/add', function ($request, $response) {
        //Add
        $action = 'add';
        $data = json_decode(json_decode($request->body()));
        if(strcasecmp($request->ctrl, 'user') === 0 ){
            require_once 'controllers/user.php';
        }
        elseif(strcasecmp($request->ctrl, 'office') === 0 ){
            require_once 'controllers/office.php';
        }
        elseif(strcasecmp($request->ctrl, 'profile') === 0 ){
            require_once 'controllers/profile.php';
        }
        else{
            http_response_code(404);
            exit();
        }
    });
    /**
     * Routes for Controllers: customer, 
     */
    $klein->respond(array('POST','GET'),'/customer/upload/[i:account]', function ($request, $response) {
        //To upload profile images
        $action = 'upload';
        $account_no = $request->account;
        require_once 'controllers/customer.php';
    });
 
    //user profile routes
    $klein->respond(array('POST','GET'),'/user/profile/get/[a:username]', function ($request, $response) {
        //To upload profile images
        $action = 'get';
        $username = $request->username;
        require_once 'controllers/profile.php';
    });
    $klein->respond(array('POST','GET'),'/user/profile/pwd/[i:id]', function ($request, $response) {
        //To upload profile images
        $action = 'pwd';
        $id = $request->id;
        $data = json_decode(json_decode($request->body()));
        require_once 'controllers/profile.php';
    });

    $klein->respond(array('POST','GET'),'/[a:ctrl]/[i:id]', function ($request, $response) {
        //View a single with id
        $action = 'view';
        $id = $request->id;
        $user_id = $request->id;
        if(strcasecmp($request->ctrl, 'customer') === 0 ){
            require_once 'controllers/customer.php';
        }
        elseif(strcasecmp($request->ctrl, 'loan') === 0 ){
            require_once 'controllers/loan-application.php';
        }
        elseif(strcasecmp($request->ctrl, 'savings') === 0 ){
            require_once 'controllers/savings-account.php';
        }
        elseif(strcasecmp($request->ctrl, 'fixed') === 0 ){
            require_once 'controllers/fixed-deposit.php';
        }
        elseif(strcasecmp($request->ctrl, 'transaction') === 0 ){
            require_once 'controllers/transaction.php';
        }
        elseif(strcasecmp($request->ctrl, 'invoice') === 0 ){
            require_once 'controllers/loan-invoice.php';
        }
        elseif(strcasecmp($request->ctrl, 'pending') === 0 ){
            require_once 'controllers/pending.php';
        }
        elseif(strcasecmp($request->ctrl, 'declined') === 0 ){
            require_once 'controllers/declined.php';
        }
        elseif(strcasecmp($request->ctrl, 'debit') === 0 ){
            require_once 'controllers/debit.php';
        }
        elseif(strcasecmp($request->ctrl, 'credit') === 0 ){
            require_once 'controllers/credit.php';
        }
        elseif(strcasecmp($request->ctrl, 'user') === 0 ){
            require_once 'controllers/user.php';
        }
        elseif(strcasecmp($request->ctrl, 'office') === 0 ){
            require_once 'controllers/office.php';
        }
        elseif(strcasecmp($request->ctrl, 'log') === 0 ){
            require_once 'controllers/activity-log.php';
        }
        else{
            http_response_code(404);
            exit();
        }
    });

    $klein->respond(array('POST','GET'),'/[a:ctrl]/[delete|edit|reset:action]/[i:id]', function ($request, $response) {
        //perform action on a single  with id
        $id = $request->id; 
        $user_id = $request->id;
        $action = $request->action;
        $data = json_decode(json_decode($request->body()));
        if(strcasecmp($request->ctrl, 'customer') === 0 ){
            require_once 'controllers/customer.php';
        }
        elseif(strcasecmp($request->ctrl, 'loan') === 0 ){
            require_once 'controllers/loan-application.php';
        }
        elseif(strcasecmp($request->ctrl, 'pending') === 0 ){
            require_once 'controllers/pending.php';
        }
        elseif(strcasecmp($request->ctrl, 'declined') === 0 ){
            require_once 'controllers/declined.php';
        }
        elseif(strcasecmp($request->ctrl, 'debit') === 0 ){
            require_once 'controllers/debit.php';
        }
        elseif(strcasecmp($request->ctrl, 'credit') === 0 ){
            require_once 'controllers/credit.php';
        }
        elseif(strcasecmp($request->ctrl, 'savings') === 0 ){
            require_once 'controllers/savings-account.php';
        }
        elseif(strcasecmp($request->ctrl, 'fixed') === 0 ){
            require_once 'controllers/fixed-deposit.php';
        }
        elseif(strcasecmp($request->ctrl, 'transaction') === 0 ){
            require_once 'controllers/transaction.php';
        }
        elseif(strcasecmp($request->ctrl, 'invoice') === 0 ){
            require_once 'controllers/loan-invoice.php';
        }
        elseif(strcasecmp($request->ctrl, 'user') === 0 ){
            require_once 'controllers/user.php';
        }
        elseif(strcasecmp($request->ctrl, 'office') === 0 ){
            require_once 'controllers/office.php';
        }
        elseif(strcasecmp($request->ctrl, 'log') === 0 ){
            require_once 'controllers/activity-log.php';
        }
        else{
            http_response_code(404);
            exit();
        }
    });
    $klein->respond(array('POST','GET'),'/[a:ctrl]/view', function ($request, $response) {
        //View all without a defined page
        $page = 1;
        $action = 'view';
        if(strcasecmp($request->ctrl, 'customer') === 0 ){
            require_once 'controllers/customer.php';
        }
        elseif(strcasecmp($request->ctrl, 'pending') === 0 ){
            require_once 'controllers/pending.php';
        }
        elseif(strcasecmp($request->ctrl, 'declined') === 0 ){
            require_once 'controllers/declined.php';
        }
        elseif(strcasecmp($request->ctrl, 'debit') === 0 ){
            require_once 'controllers/debit.php';
        }
        elseif(strcasecmp($request->ctrl, 'credit') === 0 ){
            require_once 'controllers/credit.php';
        }
        elseif(strcasecmp($request->ctrl, 'loan') === 0 ){
            require_once 'controllers/loan-application.php';
        }
        elseif(strcasecmp($request->ctrl, 'quick') === 0 ){
            require_once 'controllers/quick.php';
        }
        elseif(strcasecmp($request->ctrl, 'savings') === 0 ){
            require_once 'controllers/savings-account.php';
        }
        elseif(strcasecmp($request->ctrl, 'fixed') === 0 ){
            require_once 'controllers/fixed-deposit.php';
        }
        elseif(strcasecmp($request->ctrl, 'transaction') === 0 ){
            require_once 'controllers/transaction.php';
        }
        elseif(strcasecmp($request->ctrl, 'invoice') === 0 ){
            require_once 'controllers/loan-invoice.php';
        }
        elseif(strcasecmp($request->ctrl, 'user') === 0 ){
            require_once 'controllers/user.php';
        }
        elseif(strcasecmp($request->ctrl, 'office') === 0 ){
            require_once 'controllers/office.php';
        }
        elseif(strcasecmp($request->ctrl, 'log') === 0 ){
            require_once 'controllers/activity-log.php';
        }
        else{
            http_response_code(404);
            exit();
        }
    });
    $klein->respond(array('POST','GET'),'/[a:ctrl]/view/[i:page]', function ($request, $response) {
        //View all with page set
        $page = $request->page;
        $action = 'view';
        if(strcasecmp($request->ctrl, 'customer') === 0 ){
            require_once 'controllers/customer.php';
        }
        elseif(strcasecmp($request->ctrl, 'pending') === 0 ){
            require_once 'controllers/pending.php';
        }
        elseif(strcasecmp($request->ctrl, 'declined') === 0 ){
            require_once 'controllers/declined.php';
        }
        elseif(strcasecmp($request->ctrl, 'debit') === 0 ){
            require_once 'controllers/debit.php';
        }
        elseif(strcasecmp($request->ctrl, 'credit') === 0 ){
            require_once 'controllers/credit.php';
        }
        elseif(strcasecmp($request->ctrl, 'loan') === 0 ){
            require_once 'controllers/loan-application.php';
        }
        elseif(strcasecmp($request->ctrl, 'quick') === 0 ){
            require_once 'controllers/quick.php';
        }
        elseif(strcasecmp($request->ctrl, 'savings') === 0 ){
            require_once 'controllers/savings-account.php';
        }
        elseif(strcasecmp($request->ctrl, 'fixed') === 0 ){
            require_once 'controllers/fixed-deposit.php';
        }
        elseif(strcasecmp($request->ctrl, 'transaction') === 0 ){
            require_once 'controllers/transaction.php';
        }
        elseif(strcasecmp($request->ctrl, 'invoice') === 0 ){
            require_once 'controllers/loan-invoice.php';
        }
        elseif(strcasecmp($request->ctrl, 'user') === 0 ){
            require_once 'controllers/user.php';
        }
        elseif(strcasecmp($request->ctrl, 'office') === 0 ){
            require_once 'controllers/office.php';
        }
        elseif(strcasecmp($request->ctrl, 'log') === 0 ){
            require_once 'controllers/activity-log.php';
        }
        else{
            http_response_code(404);
            exit();
        }
    });
    $klein->respond(array('POST','GET'),'/[a:ctrl]/view/search/[a:key]/[a:value]', function ($request, $response) {
        //View all that matches the search parameter without defined page no
        $key =  str_replace('QQ', '_', $request->key);
        $value = $request->value;
        $page = 1;
        $action = 'view';
        if(strcasecmp($request->ctrl, 'customer') === 0 ){
            require_once 'controllers/customer.php';
        }
        elseif(strcasecmp($request->ctrl, 'pending') === 0 ){
            require_once 'controllers/pending.php';
        }
        elseif(strcasecmp($request->ctrl, 'declined') === 0 ){
            require_once 'controllers/declined.php';
        }
        elseif(strcasecmp($request->ctrl, 'debit') === 0 ){
            require_once 'controllers/debit.php';
        }
        elseif(strcasecmp($request->ctrl, 'credit') === 0 ){
            require_once 'controllers/credit.php';
        }
        elseif(strcasecmp($request->ctrl, 'loan') === 0 ){
            require_once 'controllers/loan-application.php';
        }
        elseif(strcasecmp($request->ctrl, 'savings') === 0 ){
            require_once 'controllers/savings-account.php';
        }
        elseif(strcasecmp($request->ctrl, 'fixed') === 0 ){
            require_once 'controllers/fixed-deposit.php';
        }
        elseif(strcasecmp($request->ctrl, 'transaction') === 0 ){
            require_once 'controllers/transaction.php';
        }
        elseif(strcasecmp($request->ctrl, 'invoice') === 0 ){
            require_once 'controllers/loan-invoice.php';
        }
        elseif(strcasecmp($request->ctrl, 'user') === 0 ){
            require_once 'controllers/user.php';
        }
        elseif(strcasecmp($request->ctrl, 'office') === 0 ){
            require_once 'controllers/office.php';
        }
        elseif(strcasecmp($request->ctrl, 'log') === 0 ){
            require_once 'controllers/activity-log.php';
        }
        else{
            http_response_code(404);
            exit();
        }
    });
    $klein->respond(array('POST','GET'),'/[a:ctrl]/view/search/[a:key]/[*:value]/[i:page]', function ($request, $response) {
        //View all that matches the search parameter with a defined page number
        $key =  str_replace('QQ', '_', $request->key);
        $value = $request->value;
        $page = $request->page;
        $action = 'view';
        if(strcasecmp($request->ctrl, 'customer') === 0 ){
            require_once 'controllers/customer.php';
        }
        elseif(strcasecmp($request->ctrl, 'pending') === 0 ){
            require_once 'controllers/pending.php';
        }
        elseif(strcasecmp($request->ctrl, 'declined') === 0 ){
            require_once 'controllers/declined.php';
        }
        elseif(strcasecmp($request->ctrl, 'debit') === 0 ){
            require_once 'controllers/debit.php';
        }
        elseif(strcasecmp($request->ctrl, 'credit') === 0 ){
            require_once 'controllers/credit.php';
        }
        elseif(strcasecmp($request->ctrl, 'loan') === 0 ){
            require_once 'controllers/loan-application.php';
        }
        elseif(strcasecmp($request->ctrl, 'quick') === 0 ){
            require_once 'controllers/quick.php';
        }
        elseif(strcasecmp($request->ctrl, 'savings') === 0 ){
            require_once 'controllers/savings-account.php';
        }
        elseif(strcasecmp($request->ctrl, 'fixed') === 0 ){
            require_once 'controllers/fixed-deposit.php';
        }
        elseif(strcasecmp($request->ctrl, 'transaction') === 0 ){
            require_once 'controllers/transaction.php';
        }
        elseif(strcasecmp($request->ctrl, 'invoice') === 0 ){
            require_once 'controllers/loan-invoice.php';
        }
        elseif(strcasecmp($request->ctrl, 'user') === 0 ){
            require_once 'controllers/user.php';
        }
        elseif(strcasecmp($request->ctrl, 'office') === 0 ){
            require_once 'controllers/office.php';
        }
        elseif(strcasecmp($request->ctrl, 'log') === 0 ){
            require_once 'controllers/activity-log.php';
        }
        else{
            http_response_code(404);
            exit();
        }
    });

    /**
     * Api Route block for reports
     */
    $klein->respond(array('POST','GET'),'/report/[i:office]/[a:unit]/[a:filter]/[a:key]/[*:timeframe]', function ($request, $response) {
        
        $office = $request->office;
        $unit = $request->unit;
        $filter =  str_replace('QQ', '_', $request->filter);;
        $key = $request->key;
        $timeframe = $request->timeframe;
        require_once 'controllers/report.php';
    });

});
$klein->dispatch($request);