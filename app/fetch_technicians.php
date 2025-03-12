<?php
include_once './class/databaseConn.php';
include_once 'lib/requestHandler.php';
error_reporting(1);
$DatabaseCo = new DatabaseConn();

header('Content-Type: application/json'); // Ensure JSON response

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["area_id"])) {

    // Convert area_id string into an array and clean it
    $area_ids = explode(",", $_POST["area_id"]); 
    $area_ids = array_map('trim', $area_ids);
    $area_ids = array_unique(array_filter($area_ids)); // Remove empty values & duplicates

    if (empty($area_ids)) {
        echo json_encode(["status" => "error", "message" => "Invalid Area ID"]);
        exit;
    }

    // Define a single merged key
    $merged_key = "merged_" . implode("_", $area_ids);

    // Prepare placeholders (?, ?, ?) dynamically
    $placeholders = implode(',', array_fill(0, count($area_ids), '?'));

    // Fetch technicians based on area_id
    $query = "SELECT id, employee_id, technician_name FROM technician WHERE city IN ($placeholders) ORDER BY technician_name";
    $stmt = $DatabaseCo->dbLink->prepare($query);

    // Bind parameters dynamically (assuming city is an integer field)
    $stmt->bind_param(str_repeat("i", count($area_ids)), ...$area_ids);
    $stmt->execute();
    $result = $stmt->get_result();

    // Store unique technicians
    $merged_technicians = [];

    while ($row = $result->fetch_assoc()) {
        // Store unique technicians using their ID
        if (!isset($merged_technicians[$row["id"]])) {
            $merged_technicians[$row["id"]] = [
                "id" => $row["id"],
                "name" => $row["technician_name"],
                "employee_id" => $row["employee_id"]
            ];
        }
    }

    $stmt->close();

    // Convert merged technicians to an indexed array under the single merged key
    $technicians = [$merged_key => array_values($merged_technicians)];

    echo json_encode(["status" => "success", "data" => $technicians]);
    exit;
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit;
}



?>
