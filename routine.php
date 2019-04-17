<?php
/**
 *The routine script that runs every 24 hours to automate timely updates.
 */

//include the general required file
require_once 'modules/required.php';

//create new database connection object
$db = new DatabaseConnection();

//Get todays date
$todays_date = date("Y-m-d"); //Get the current local date;
//Do Routine update for Fixed Deposits
$fixed = new database\FixedDepositAccess(new database\SQLHandler($db->conn)); //Create instance of fixed deposits
$deposits = $fixed->select_multiple(null, array("start_date" => $todays_date), null); //get all fixed deposits from data that has today as start date
//loop through all selected deposits and update there status to active
for($i=0; $i<count($deposits); $i++){
    $loop_inst = new database\FixedDepositAccess(new database\SQLHandler($db->conn), $deposits[$i]['id']);
    $loop_inst->column_update(array("status" => "active"));
}
$fixed->close();

//do for start date
$fixed = new database\FixedDepositAccess(new database\SQLHandler($db->conn)); //Create instance of fixed deposits
$deposits = $fixed->select_multiple(null, array("end_date" => $todays_date), null); //get all fixed deposits from data that has today as end date
//loop through all selected deposits and update there status to completed
for($i=0; $i<count($deposits); $i++){
    $loop_inst = new database\FixedDepositAccess(new database\SQLHandler($db->conn), $deposits[$i]['id']);
    $loop_inst->column_update(array("status" => "completed"));
}
$fixed->close();

//Do routine update for loan invoices
//add the start time limit of : 00:00:00 to date
$start_date_time = $todays_date . ' 00:00:00';
$end_date_time = $todays_date . ' 23:59:59';
$query = "SELECT * FROM `ls_loan_invoice` WHERE `status` <> 'paid' AND `due_date` BETWEEN '$start_date_time' AND '$end_date_time'";
$db_handle = new database\SQLHandler($db->conn);
$invoices = $db_handle->rawQuery($query);
$db_handle->close();
for($i=0; $i<count($invoices); $i++){
    $loop_inst = new database\LoanInvoiceAccess(new database\SQLHandler($db->conn), $invoices[$i]['id']);
    $loop_inst->column_update(array("status" => "overdue"));
}

//Do Routine update for target Savings invoice
//add the start time limit of : 00:00:00 to date
$start_date_time = $todays_date . ' 00:00:00';
$end_date_time = $todays_date . ' 23:59:59';
$query = "SELECT * FROM `ls_target_savings_invoice` WHERE `status` <> 'paid' AND `due_date` BETWEEN '$start_date_time' AND '$end_date_time'";
$db_handle = new database\SQLHandler($db->conn);
$invoices = $db_handle->rawQuery($query);
$db_handle->close();
for($i=0; $i<count($invoices); $i++){
    $loop_inst = new database\LoanInvoiceAccess(new database\SQLHandler($db->conn), $invoices[$i]['id']);
    $loop_inst->column_update(array("status" => "overdue"));
}

//Do Routine update for target savings
$fixed = new database\TargetSavingsAccess(new database\SQLHandler($db->conn)); //Create instance of fixed deposits
$deposits = $fixed->select_multiple(null, array("start_date" => $todays_date), null); //get all fixed deposits from data that has today as start date
//loop through all selected deposits and update there status to active
for($i=0; $i<count($deposits); $i++){
    $loop_inst = new database\TargetSavingsAccess(new database\SQLHandler($db->conn), $deposits[$i]['id']);
    $loop_inst->column_update(array("status" => "active"));
}
$fixed->close();

//do for end date
$fixed = new database\TargetSavingsAccess(new database\SQLHandler($db->conn)); //Create instance of fixed deposits
$deposits = $fixed->select_multiple(null, array("end_date" => $todays_date), null); //get all fixed deposits from data that has today as end date
//loop through all selected deposits and update there status to completed
for($i=0; $i<count($deposits); $i++){
    $loop_inst = new database\TargetSavingsAccess(new database\SQLHandler($db->conn), $deposits[$i]['id']);
    $loop_inst->column_update(array("status" => "completed"));
}
$fixed->close();