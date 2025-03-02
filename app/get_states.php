<?php
 include_once './class/databaseConn.php'; // Ensure the database connection is included
 $DatabaseCo = new DatabaseConn();
$country_code = $_POST['country_code'] ?? '';

if ($country_code) {
    $query = "SELECT * FROM states WHERE country_id = '$country_code' ORDER BY name";
    $result = mysqli_query($DatabaseCo->dbLink, $query);
    
    while ($row = mysqli_fetch_object($result)) {
        echo "<option value='{$row->id}'>{$row->name}</option>";
    }
}
?>
