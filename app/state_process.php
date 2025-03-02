<?php
include_once './class/databaseConn.php';
include_once 'lib/requestHandler.php';

$DatabaseCo = new DatabaseConn();
$response = ['successStatus' => false, 'responseMessage' => ''];





if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    $country_code = strtoupper(trim($_POST['country_code']));
    $state_name = htmlspecialchars(trim($_POST['state_name']));
    $state_status = $_POST['status'] ?? 'inactive';
    $state_id = $_POST['state_id'] ?? null; // Only for updates

    // Validate Inputs
    // if (strlen($country_code) !== 5) {
    //     echo json_encode(['success' => false, 'message' => 'Country code must be exactly 2 characters.']);
    //     exit;
    // }
    if (empty($state_name)) {
        echo json_encode(['success' => false, 'message' => 'State name is required.']);
        exit;
    }

    try {
        if ($action === 'update' && !empty($state_id)) {
            // Update statement
            $sql = "UPDATE `states` SET country_id = ?, name = ?, status = ? WHERE id = ?";
            $stmt = $DatabaseCo->dbLink->prepare($sql);
            $stmt->bind_param("sssi", $country_code, $state_name, $state_status, $state_id);
        } elseif ($action === 'insert') {
            // Insert statement
            $sql = "INSERT INTO `states` (country_id, name, status) VALUES (?, ?, ?)";
            $stmt = $DatabaseCo->dbLink->prepare($sql);
            $stmt->bind_param("sss", $country_code, $state_name, $state_status);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid action or missing state ID for update.']);
            exit;
        }

        if ($stmt->execute()) {
            $message = ($action === 'update') ? 'State updated successfully.' : 'State added successfully.';
            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database operation failed: ' . $stmt->error]);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

// Make sure this points to your database configuration file


?>










