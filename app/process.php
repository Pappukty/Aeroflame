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
if($_REQUEST['form_action'] == 'DeleteCountry') {
    $did = $xssClean->clean_input($_REQUEST['delid']);
    $DatabaseCo->dbLink->query("DELETE FROM `countries` WHERE `id`='$did'");
}


if($_REQUEST['form_action'] == 'DeleteState') {
    $did = $xssClean->clean_input($_REQUEST['delid']);
    $DatabaseCo->dbLink->query("DELETE FROM `states` WHERE `id`='$did'");
}
if($_REQUEST['form_action'] == 'DeleteCity') {
    $did = $xssClean->clean_input($_REQUEST['delid']);
    $DatabaseCo->dbLink->query("DELETE FROM `cities` WHERE `id`='$did'");
}
if($_REQUEST['form_action'] == 'DeleteOrder') {
    $did = $xssClean->clean_input($_REQUEST['delid']);
    $DatabaseCo->dbLink->query("DELETE FROM `purchase_order` WHERE `id`='$did'");
}

if($_REQUEST['form_action'] == 'DeleteSpares') {
    $did = $xssClean->clean_input($_REQUEST['delid']);
    $DatabaseCo->dbLink->query("DELETE FROM `issued_spares` WHERE `id`='$did'");
} 

if($_REQUEST['form_action'] == 'DeleteStock_Audit') {
    $did = $xssClean->clean_input($_REQUEST['delid']);
    $DatabaseCo->dbLink->query("DELETE FROM `stock_audits` WHERE `id`='$did'");
} 
if($_REQUEST['form_action'] == 'Deleteamc') {
    $did = $xssClean->clean_input($_REQUEST['delid']);
    $DatabaseCo->dbLink->query("DELETE FROM `amc_contracts` WHERE `id`='$did'");
} 