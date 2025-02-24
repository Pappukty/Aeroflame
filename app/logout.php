<?php ob_start();
session_start();
$_SESSION['user_id']="";
session_destroy();
header("location:index.php");
?>

