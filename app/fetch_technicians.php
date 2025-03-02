<?php
include_once './class/databaseConn.php';
include_once 'lib/requestHandler.php';
error_reporting(1);
$DatabaseCo = new DatabaseConn();

header('Content-Type: application/json'); // Ensure JSON response

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["area_id"])) {
    $area_ids = explode(",", $_POST["area_id"]); // Split area IDs into an array

    // Validate input and sanitize each ID
    $area_ids = array_map('trim', $area_ids); // Trim spaces
    $area_ids = array_map('intval', $area_ids); // Convert to integers (prevents SQL injection)

    if (empty($area_ids)) {
        echo json_encode(["status" => "error", "message" => "Invalid Area ID"]);
        exit;
    }

    // Dynamically generate placeholders (?,?,?) based on the count of area IDs
    $placeholders = implode(',', array_fill(0, count($area_ids), '?'));

    // Query to fetch technicians for multiple area IDs
    $query = "SELECT id,employee_id, technician_name FROM technician WHERE city IN ($placeholders) ORDER BY technician_name";
    $stmt = $DatabaseCo->dbLink->prepare($query);

    // Bind parameters dynamically
    $stmt->bind_param(str_repeat("i", count($area_ids)), ...$area_ids);
    $stmt->execute();
    $result = $stmt->get_result();

    $technicians = [];

    while ($row = $result->fetch_assoc()) {
        $technicians[] = [
            "id" => $row["id"],
            "name" => $row["technician_name"],
            "employee_id" => $row["employee_id"]
        ];
    }

    $stmt->close();

    echo json_encode(["status" => "success", "data" => $technicians]);
    exit;
} else {
    echo json_encode(["status" => "error", "message" => "Invalid Area ID"]);
    exit;
}



?>
