<?php
/**
 * Url Routes
 */
use libs\altorouter\AltoRouter;
use includes\HttpObject\Request;
use includes\HttpObject\Response;
use includes\HttpObject\RequestProcessor;
use includes\auth\Session;
//Initialize router object for all routes
$router = new AltoRouter();

$router->setBasePath(ROOT_DIR);
//---------------------
//Url route/map block
//---------------------

//CUSTOMER
$router->map('GET|POST','/customer/marketer/get',array(
    'class'=>'\controllers\Customer',
    'method'=>'getMarketers',
    'params'=>Session::get('office')
));
$router->map('GET|POST','/customer/register',array(
    'class'=>'\controllers\Customer',
    'method'=>'save'
));
$router->map('GET|POST','/customer/list/[*:query]/[i:page]',array(
    'class'=>'\controllers\Customer',
    'method'=>'getList'
));
$router->map('GET|POST','/customer/list/[i:page]',array(
    'class'=>'\controllers\Customer',
    'method'=>'getList'
));
$router->map('GET|POST','/customer/get/[i:account]',array(
    'class'=>'\controllers\Customer',
    'method'=>'getCustomer'
));
$router->map('GET|POST','/customer/edit/[i:account]',array(
    'class'=>'\controllers\Customer',
    'method'=>'update'
));
$router->map('GET|POST','/customer/central/list/[*:office]/[*:query]/[i:page]',array(
    'class'=>'\controllers\Customer',
    'method'=>'getCentralList'
));
$router->map('GET|POST','/customer/central/list/[*:office]/[i:page]',array(
    'class'=>'\controllers\Customer',
    'method'=>'getCentralList'
));
$router->map('GET|POST','/savings/get/init/[i:account]',array(
    'class'=>'\controllers\SavingsAccount',
    'method'=>'getPrimaryAccountDetails'
));
$router->map('GET|POST','/savings/withdrawal/init/[i:account]',array(
    'class'=>'\controllers\SavingsAccount',
    'method'=>'initWithdrawal'
));
$router->map('GET|POST','/savings/withdrawal/make/[i:account]',array(
    'class'=>'\controllers\SavingsAccount',
    'method'=>'makeWithdrawal'
));
$router->map('GET|POST','/savings/deposit/init/[i:account]',array(
    'class'=>'\controllers\SavingsAccount',
    'method'=>'initDeposit'
));
$router->map('GET|POST','/savings/deposit/make/[i:account]',array(
    'class'=>'\controllers\SavingsAccount',
    'method'=>'makeDeposit'
));
$router->map('GET|POST','/savings/get/[i:account]',array(
    'class'=>'\controllers\SavingsAccount',
    'method'=>'getSingleAccount'
));
$router->map('GET|POST','/savings/edit/[i:account]',array(
    'class'=>'\controllers\SavingsAccount',
    'method'=>'updateSavings'
));
$router->map('GET|POST','/savings/list/[*:query]/[i:page]',array(
    'class'=>'\controllers\SavingsAccount',
    'method'=>'getList'
));
$router->map('GET|POST','/savings/list/[i:page]',array(
    'class'=>'\controllers\SavingsAccount',
    'method'=>'getList'
));
$router->map('GET|POST','/savings/central/list/[*:office]/[*:query]/[i:page]',array(
    'class'=>'\controllers\SavingsAccount',
    'method'=>'getCentralList'
));
$router->map('GET|POST','/savings/central/list/[*:office]/[i:page]',array(
    'class'=>'\controllers\SavingsAccount',
    'method'=>'getCentralList'
));
$router->map('GET|POST','/transaction/list/[*:query]/[i:page]',array(
    'class'=>'\controllers\Transaction',
    'method'=>'getList'
));
$router->map('GET|POST','/transaction/list/[i:page]',array(
    'class'=>'\controllers\Transaction',
    'method'=>'getList'
));
$router->map('GET|POST','/transaction/get/[i:id]',array(
    'class'=>'\controllers\Transaction',
    'method'=>'getTransactionById'
));
$router->map('GET|POST','/transaction/confirm/[i:id]',array(
    'class'=>'\controllers\Transaction',
    'method'=>'confirm'
));
$router->map('GET|POST','/transaction/decline/[i:id]',array(
    'class'=>'\controllers\Transaction',
    'method'=>'decline'
));
$router->map('GET|POST','/transaction/central/list/[*:office]/[*:query]/[i:page]',array(
    'class'=>'\controllers\Transaction',
    'method'=>'getCentralList'
));
$router->map('GET|POST','/transaction/central/list/[*:office]/[i:page]',array(
    'class'=>'\controllers\Transaction',
    'method'=>'getCentralList'
));
$router->map('GET|POST','/report/summary',array(
    'class'=>'\controllers\Report',
    'method'=>'getSummary'
));
$router->map('GET|POST','/report/soa',array(
    'class'=>'\controllers\Report',
    'method'=>'getSOA'
));
$router->map('GET|POST','/report/branch',array(
    'class'=>'\controllers\Report',
    'method'=>'getBranchReport'
));
$router->map('GET|POST','/report/daily',array(
    'class'=>'\controllers\Report',
    'method'=>'getCentralSummaryReport'
));
$router->map('GET|POST','/report/general',array(
    'class'=>'\controllers\Report',
    'method'=>'getBranchReport'
));
$router->map('GET|POST','/user/profile/get/[*:username]',array(
    'class'=>'\controllers\User',
    'method'=>'getProfile'
));
$router->map('GET|POST','/user/profile/pwd/[*:username]',array(
    'class'=>'\controllers\User',
    'method'=>'updatePassword'
));

//----------------------
//End Url route/map
//----------------------


//match all request url that have been included
 $match = $router->match();
 //call closure or throw 404 status
 if( is_array($match) && is_array($match['target']) ) {
     $req_processor = new RequestProcessor(new Request($match['params']));
    //check if its a class or functiion
    if(isset($match['target']['class'])){
        $req_processor->classController($match['target']['class'],
        isset($match['target']['method'])?$match['target']['method']:null,
        isset($match['target']['params'])?$match['target']['params']:null);
    }
    elseif(isset($match['target']['path'])){
        $req_processor->functionController($match['target']['path'],
        $match['target']['name'],
        isset($match['target']['params'])?$match['target']['params']:null);
    }
    else{
        throw new \Exception('can not identify a target file or class');
    }
 }
 else {
	// no route was matched
    $resp = new Response();
    $resp->addMessage('Resource not found or has been relocated');
    $resp->JsonResponse(null, 404);
 }