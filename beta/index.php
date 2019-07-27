<?php

use includes\database\Wrapper;

//include the configuration file
require_once $_SERVER['DOCUMENT_ROOT'] . "/laser/beta/config.php";

//Include altoload function
require_once ROOT_URL.'/includes/__autoload.php';

//Start session
session_start();

//create a global instance of database wrapper
$GLOBALS['db'] = new Wrapper();

//inport url Routes
require_once 'urls.php';

