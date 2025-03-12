<?php
include_once './class/databaseConn.php';
include_once 'lib/requestHandler.php';
error_reporting(1);
$DatabaseCo = new DatabaseConn();

header('Content-Type: application/json'); // Ensure JSON response


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get POST Data
    $customer_id = isset($_POST['customer_id']) ? trim($_POST['customer_id']) : "";
    $technician_id = isset($_POST['technician_id']) ? trim($_POST['technician_id']) : "";
    $admin_id = isset($_POST['admin_id']) ? trim($_POST['admin_id']) : "";
    $staff_id = isset($_POST['staff_id']) ? trim($_POST['staff_id']) : "";
    $customer_name = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : "";
    $customer_address = isset($_POST['address']) ? trim($_POST['address']) : "";
    $customer_number = isset($_POST['customer_number']) ? trim($_POST['customer_number']) : "";
    $service = isset($_POST['service']) ? trim($_POST['service']) : "";
    $service_amount = isset($_POST['service_amount']) ? trim($_POST['service_amount']) : "";
    $feedback = isset($_POST['feedback']) ? trim($_POST['feedback']) : "";

    // Define status (Modify as per your requirement)
    $status_process = "In Progress";

    // Validate required fields
    // if (
    //     empty($customer_id) || empty($technician_id) || empty($admin_id) || empty($staff_id) ||
    //     empty($customer_name) || empty($customer_address) || empty($customer_number) ||
    //     empty($service) || empty($service_amount) || empty($feedback)
    // ) {
    //     echo json_encode(["status" => "error", "message" => "All fields are required."]);
    //     exit;
    // }

    // ✅ 1. UPDATE `resolve` table to assign a technician
    $sql1 = "UPDATE resolve SET technician_id = ?, status_process = ? WHERE index_id = ?";
    $stmt1 = $DatabaseCo->dbLink->prepare($sql1);
    $stmt1->bind_param("ssi", $technician_id, $status_process, $customer_id);
    $stmt1->execute();
    $stmt1->close();

    // ✅ 2. Check if the record exists in `technician_assignment`
    $check_sql = "SELECT id FROM technician_assignment WHERE customer_id = ?";
    $check_stmt = $DatabaseCo->dbLink->prepare($check_sql);
    $check_stmt->bind_param("i", $customer_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $check_stmt->close();

    if ($result->num_rows > 0) {
        // ✅ 3. If record exists, UPDATE `technician_assignment`
        $sql2 = "UPDATE technician_assignment SET 
                    admin_id = ?, 
                    staff_id = ?, 
                    customer_name = ?, 
                    customer_address = ?, 
                    customer_number = ?, 
                    status_process = ?, 
                    technican_id = ?, 
                    service = ?, 
                    service_amount = ?, 
                    feedback = ? 
                 WHERE customer_id = ?";
        $stmt2 = $DatabaseCo->dbLink->prepare($sql2);
        $stmt2->bind_param("ssssssssssi", $admin_id, $staff_id, $customer_name, $customer_address, $customer_number, $status_process, $technician_id, $service, $service_amount, $feedback, $customer_id);
    } else {
        // ✅ 4. If record does NOT exist, INSERT into `technician_assignment`
        $sql2 = "INSERT INTO technician_assignment 
                    (admin_id, staff_id, customer_id, customer_name, customer_address, customer_number, status_process, technican_id, service, service_amount, feedback) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt2 = $DatabaseCo->dbLink->prepare($sql2);
        $stmt2->bind_param("sssssssssss", $admin_id, $staff_id, $customer_id, $customer_name, $customer_address, $customer_number, $status_process, $technician_id, $service, $service_amount, $feedback);
    }

    // ✅ 5. Execute INSERT or UPDATE statement
    if ($stmt2->execute()) {
        echo json_encode(["status" => "success", "message" => "Work schedule updated successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating work schedule."]);
    }

    // ✅ 6. Close statements
    $stmt2->close();
}
