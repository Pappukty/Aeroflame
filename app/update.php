<?php
// Assuming your database connection is already included
error_reporting(0);
include_once './class/databaseConn.php';
include_once './lib/requestHandler.php';
$DatabaseCo = new DatabaseConn();

include_once './class/XssClean.php';
$xssClean = new xssClean();



header('Content-Type: application/json'); // Ensure JSON response

if (isset($_POST['id']) && isset($_POST['status'])) {
  

    $id = intval($_POST['id']); // Ensure ID is an integer
    $status = trim($_POST['status']); // Trim unnecessary spaces

    // Debug: Check if data is received correctly
    error_log("Received Data - ID: $id, Status: $status");

    // SQL query to update the status
    $query = "UPDATE resolve SET status_process = ? WHERE index_id = ?";

    // Prepare and execute the query
    if ($stmt = $DatabaseCo->dbLink->prepare($query)) {
        $stmt->bind_param("si", $status, $id);  // "si" means string for status, integer for id

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Ticket marked as completed successfully!"]);
        } else {
            error_log("Execution Failed: " . $stmt->error);
            echo json_encode(["status" => "error", "message" => "Failed to update ticket. Please try again!"]);
        }
        $stmt->close();
    } else {
        error_log("Statement Preparation Failed: " . $DatabaseCo->dbLink->error);
        echo json_encode(["status" => "error", "message" => "Database error. Please contact support!"]);
    }
} else {
    error_log("Missing POST data: id or status not set.");
    echo json_encode(["status" => "error", "message" => "Invalid request. Missing required data!"]);
}




 // Set technician status to active or inactive
// Suppress PHP errors in output (useful in production)








?>



