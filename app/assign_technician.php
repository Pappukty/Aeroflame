<?php
include 'db_connection.php'; // Ensure database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["customer_id"], $_POST["technician_id"])) {
    $customer_id = $_POST["customer_id"];
    $technician_id = $_POST["technician_id"];
    $status = "In-Progress"; // Default status update

    // Update technician assignment
    $assignQuery = "INSERT INTO technician_assignments (customer_id, technician_id, status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($assignQuery);
    $stmt->bind_param("iis", $customer_id, $technician_id, $status);

    if ($stmt->execute()) {
        // Update technician status
        $updateTechStatus = "UPDATE technicians SET status = 'Assigned' WHERE id = ?";
        $stmt2 = $conn->prepare($updateTechStatus);
        $stmt2->bind_param("i", $technician_id);
        $stmt2->execute();

        echo json_encode(["status" => "success", "message" => "Technician assigned successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Assignment failed."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
