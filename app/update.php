<?php
// Assuming your database connection is already included
error_reporting(0);
include_once './class/databaseConn.php';
include_once './lib/requestHandler.php';
$DatabaseCo = new DatabaseConn();

include_once './class/XssClean.php';
$xssClean = new xssClean();
if(isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Debug: Check if data is received correctly
    error_log("ID: " . $id . " Status: " . $status);

    // SQL query to update the status
    $query = "UPDATE resolve SET status_process = ? WHERE index_id = ?";

    // Prepare and execute the query
    if($stmt = $DatabaseCo->dbLink->prepare($query)) {
        $stmt->bind_param("si", $status, $id);  // "si" means string for status, integer for id

        // Debug: Check if the statement executed successfully
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Ticket marked as completed!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update ticket."]);
        }
        $stmt->close();
    } else {
        error_log("Statement Preparation Failed: " . $DatabaseCo->dbLink->error); // Log any issues with preparing the statement
        echo 'error';  // Respond with error if the query preparation fails
    }
} else {
    error_log("Missing POST data: id or status not set.");
    echo 'error';  // Respond with error if ID or status is not set
}






 // Set technician status to active or inactive
// Suppress PHP errors in output (useful in production)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status_id'], $_POST['availability_status'])) {
    ob_clean(); // Remove unwanted output

  

    $id = intval($_POST['status_id']);
    $status = $_POST['availability_status'];

    // Validate status value
    if (!in_array($status, ['Active', 'Inactive', 'Completed'])) { // Include 'Completed' if needed
        echo json_encode(['status' => 'error', 'message' => 'Invalid status value!']);
        exit;
    }

    // Prepare and execute the update query
    $query = "UPDATE technician SET availability_status = ? WHERE id = ?";
    $stmt = $DatabaseCo->dbLink->prepare($query);

    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $DatabaseCo->dbLink->error]);
        exit;
    }

    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Status updated successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed!']);
    }

    $stmt->close();

} else {
    ob_clean();
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method or missing parameters!']);
}




if (isset($_POST['technician_id'])) {
    $technician_id = $_POST['technician_id'];

    $query = "SELECT * FROM resolve WHERE index_id != '0' ORDER BY index_id DESC";
    
    $stmt = $DatabaseCo->dbLink->prepare($query);
    $stmt->bind_param("i", $technician_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['name']}</option>";
        }
    } else {
        echo "<option value=''>No customers assigned</option>";
    }
}



?>



