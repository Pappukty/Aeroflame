<?php
include_once './class/databaseConn.php';
include_once 'lib/requestHandler.php';

$DatabaseCo = new DatabaseConn();
$response = ['successStatus' => false, 'responseMessage' => ''];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $city_id = isset($_POST['city_id']) ? (int) $_POST['city_id'] : null;
    $city_name = trim($_POST['city_name'] ?? '');
    $state_id = isset($_POST['state_id']) ? (int) $_POST['state_id'] : null;
    $status = $_POST['status'] ?? '';

    // Validate required fields
    if (empty($city_name) || empty($state_id) || empty($status)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    try {
        if ($action === 'update' && $city_id) {
            $sql = "UPDATE cities SET name = ?, status = ?, state_id = ? WHERE id = ?";
            $stmt = $DatabaseCo->dbLink->prepare($sql);
            $stmt->bind_param("ssii", $city_name, $status, $state_id, $city_id);
        } elseif ($action === 'insert') {
            $sql = "INSERT INTO cities (name, status, state_id) VALUES (?, ?, ?)";
            $stmt = $DatabaseCo->dbLink->prepare($sql);
            $stmt->bind_param("ssi", $city_name, $status, $state_id);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid operation.']);
            exit;
        }

        if ($stmt->execute()) {
            $message = ($action === 'update') ? 'City updated successfully.' : 'City added successfully.';
            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database operation failed.']);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}

// Make sure this points to your database configuration file


?>










