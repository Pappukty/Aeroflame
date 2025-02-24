<?php 
error_reporting(0);
include_once './class/databaseConn.php';
include_once './lib/requestHandler.php';
$DatabaseCo = new DatabaseConn();

include_once './class/XssClean.php';
$xssClean = new xssClean();

if($_REQUEST['form_action'] == 'DeleteProduct') {
    $did = $xssClean->clean_input($_REQUEST['delid']);
    $DatabaseCo->dbLink->query("DELETE FROM `products` WHERE id='$did'");
}
if($_REQUEST['form_action'] == 'DeleteCustomer') {
    $did = $xssClean->clean_input($_REQUEST['delid']);
    $DatabaseCo->dbLink->query("DELETE FROM `customer` WHERE `id`='$did'");
}
if($_REQUEST['form_action'] == 'DeleteTickets') {
    $did = $xssClean->clean_input($_REQUEST['delid']);
    $DatabaseCo->dbLink->query("DELETE FROM `resolve` WHERE `index_id`='$did'");
}
if($_REQUEST['form_action'] == 'DeleteTechnician') {
    $did = $xssClean->clean_input($_REQUEST['delid']);
    $DatabaseCo->dbLink->query("DELETE FROM `technician` WHERE `id`='$did'");
}
if($_REQUEST['form_action'] == 'DeleteStaff') {
    $did = $xssClean->clean_input($_REQUEST['delid']);
    $DatabaseCo->dbLink->query("DELETE FROM `staff` WHERE `id`='$did'");
}