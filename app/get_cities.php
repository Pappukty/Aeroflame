<?php
 include_once './class/databaseConn.php';// Ensure the database connection is included
 $DatabaseCo = new DatabaseConn();
 $state_code = $_POST['state_code'] ?? '';

 if ($state_code) {
     $query = "SELECT * FROM cities WHERE state_id = '$state_code' ORDER BY name";
     $result = mysqli_query($DatabaseCo->dbLink, $query);
     
     while ($row = mysqli_fetch_object($result)) {
         echo "<option value='{$row->id}'>{$row->name}</option>";
     }
 }
?>
