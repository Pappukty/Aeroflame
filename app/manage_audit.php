<?php
include_once './class/databaseConn.php';
include_once './lib/requestHandler.php';

include_once './StockAudit.php';
$DatabaseCo = new DatabaseConn();
$stockAudit = new StockAudit($DatabaseCo);

// Fetch all audits
$audits = $stockAudit->getAllAudits();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $spare_part_id = $_POST['spare_part_id'];
    $actual_stock = $_POST['actual_stock'];
    $recorded_stock = $_POST['recorded_stock'];

    if (!$spare_part_id || !$actual_stock || !$recorded_stock) {
        die("All fields are required.");
    }

    if ($id) {
        // Update existing record
        $stockAudit->updateAudit($id, $spare_part_id, $actual_stock, $recorded_stock);
    } else {
        // Insert new record
        $stockAudit->addAudit($spare_part_id, $actual_stock, $recorded_stock);
    }

    header("Location: stock_audit_list.php");
    exit();
}

// Handle deletion
if (isset($_GET['delete_id'])) {
    $stockAudit->deleteAudit($_GET['delete_id']);
    header("Location: stock_audit_list.php");
    exit();
}

?>