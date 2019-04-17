<?php
//The index page for the Investment module
define('MODULE', 'Secretary'); //Module name

//include the general required file
require_once '../required.php';

define('APP_PATH', ROOT_DIR.'/modules/secretary'); //Application path

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
     * Search for marketers existence
     */
    $klein->respond(array('POST','GET'),'/marketer/search/[a:username]', function ($request, $response) {
        //View all that matches the search parameter without defined page no
        
        $username = $request->username;

        require_once 'controllers/search_marketer.php';
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

    $klein->respond(array('POST','GET'),'/[a:ctrl]/add', function ($request, $response) {
        //Add
        $action = 'add';
        $data = json_decode(json_decode($request->body()));
        
        if(strcasecmp($request->ctrl, 'customer') === 0 ){
            require_once 'controllers/customer.php';
        }
        elseif(strcasecmp($request->ctrl, 'transaction') === 0 ){
            require_once 'controllers/transaction.php';
        }
        elseif(strcasecmp($request->ctrl, 'savings') === 0 ){
            require_once 'controllers/savings-account.php';
        }
        else{
            http_response_code(404);
            exit();
        }
    });
    $klein->respond(array('POST','GET'),'/[a:ctrl]/[i:id]', function ($request, $response) {
        //View a single with id
        $action = 'view';
        $id = $request->id;
        if(strcasecmp($request->ctrl, 'customer') === 0 ){
            require_once 'controllers/customer.php';
        }
        elseif(strcasecmp($request->ctrl, 'transaction') === 0 ){
            require_once 'controllers/transaction.php';
        }
        elseif(strcasecmp($request->ctrl, 'savings') === 0 ){
            require_once 'controllers/savings-account.php';
        }
        else{
            http_response_code(404);
            exit();
        }
    });
    $klein->respond(array('POST','GET'),'/[a:ctrl]/[delete|edit|cashout|deposit|withdraw:action]/[i:id]', function ($request, $response) {
        //perform action on a single  with id
        $id = $request->id;
        $action = $request->action;
        $data = json_decode(json_decode($request->body()));
        if(strcasecmp($request->ctrl, 'customer') === 0 ){
            require_once 'controllers/customer.php';
        }
        elseif(strcasecmp($request->ctrl, 'transaction') === 0 ){
            require_once 'controllers/transaction.php';
        }
        elseif(strcasecmp($request->ctrl, 'savings') === 0 ){
            require_once 'controllers/savings-account.php';
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
        elseif(strcasecmp($request->ctrl, 'transaction') === 0 ){
            require_once 'controllers/transaction.php';
        }
        elseif(strcasecmp($request->ctrl, 'savings') === 0 ){
            require_once 'controllers/savings-account.php';
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
        elseif(strcasecmp($request->ctrl, 'transaction') === 0 ){
            require_once 'controllers/transaction.php';
        }
        elseif(strcasecmp($request->ctrl, 'savings') === 0 ){
            require_once 'controllers/savings-account.php';
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
        elseif(strcasecmp($request->ctrl, 'transaction') === 0 ){
            require_once 'controllers/transaction.php';
        }
        elseif(strcasecmp($request->ctrl, 'savings') === 0 ){
            require_once 'controllers/savings-account.php';
        }
        else{
            http_response_code(404);
            exit();
        }
    });
    $klein->respond(array('POST','GET'),'/[a:ctrl]/view/search/[a:key]/[a:value]/[i:page]', function ($request, $response) {
        //View all that matches the search parameter with a defined page number
        $key =  str_replace('QQ', '_', $request->key);
        $value = $request->value;
        $page = $request->page;
        $action = 'view';
        if(strcasecmp($request->ctrl, 'customer') === 0 ){
            require_once 'controllers/customer.php';
        }
        elseif(strcasecmp($request->ctrl, 'transaction') === 0 ){
            require_once 'controllers/transaction.php';
        }
        elseif(strcasecmp($request->ctrl, 'savings') === 0 ){
            require_once 'controllers/savings-account.php';
        }
        else{
            http_response_code(404);
            exit();
        }
    });
});

$klein->dispatch($request);