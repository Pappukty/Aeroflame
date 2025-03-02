<?php
include_once './class/databaseConn.php';
include_once 'lib/requestHandler.php';

$DatabaseCo = new DatabaseConn();
header('Content-Type: application/json');

$query = "SELECT id, name FROM cities ORDER BY name";
$result = mysqli_query($DatabaseCo->dbLink, $query);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Database error"]);
    exit;
}

$cities = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cities[] = ["id" => $row["id"], "name" => $row["name"]];
}

echo json_encode(["status" => "success", "data" => $cities]);
exit;
?>
