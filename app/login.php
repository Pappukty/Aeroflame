<?php
error_reporting(0);
include_once './class/databaseConn.php';
include_once './lib/requestHandler.php';
$DatabaseCo = new DatabaseConn();

include_once './class/XssClean.php';
$xssClean = new xssClean();

if (isset($_REQUEST['login'])) {
	$username = isset($_POST['username']) ? strtolower(trim($_POST['username'])) : "";
	$password = isset($_POST['password']) ? trim($_POST['password']) : "";

	$STATUS_MESSAGE = "";

	$query = "select * from admin where ( LOWER(username)='" . $username . "') and password='" . md5($password) . "'";

	$SQL_STATEMENT = $DatabaseCo->dbLink->query($query);
	$num = mysqli_num_rows($SQL_STATEMENT);
	if ($num > 0) {
		$Row = mysqli_fetch_object($SQL_STATEMENT);
		$_SESSION["user_id"] = $Row->id;
		echo "<script>window.location='index.php'</script>";
	} else {
		echo "<script>window.location='login.php?failed=1'</script>";
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	<title>ADMIN</title>
	<!--favicon-->
	<link rel="icon" href="" type="image/x-icon">
	<!-- Bootstrap core CSS-->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
	<!-- animate CSS-->
	<link href="assets/css/animate.css" rel="stylesheet" type="text/css" />
	<!-- Icons CSS-->
	<link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
	<!-- Custom Style-->
	<link href="assets/css/app-style.css" rel="stylesheet" />

</head>

<body class="bg-theme bg-theme4">

	<!-- start loader -->
	<div id="pageloader-overlay" class="visible incoming">
		<div class="loader-wrapper-outer">
			<div class="loader-wrapper-inner">
				<div class="loader"></div>
			</div>
		</div>
	</div>
	<!-- end loader -->

	<!-- Start wrapper-->
	<div id="wrapper">

		<div class="card card-authentication1 mx-auto my-5">
			<div class="card-body " style="padding-bottom: 5.5rem;">
				<div class="card-content p-2">
					<div class="text-center">
						<!-- <div>
							<img src="./assets/images/login-logo.png" class="w-100 rounded" alt="logo icon">
						</div> -->
					</div>
					<div class="card-title text-uppercase text-center py-3">Admin - Log In</div>
					<form action="" name="login_form" method="post" class="text-left">
						<div class="form-group">
							<label for="exampleInputUsername" class="sr-only">Username</label>
							<div class="position-relative has-icon-right">
								<input id="username" name="username" type="text" class="form-control input-shadow p-4" placeholder="Enter Username" required="">
								<div class="form-control-position">
									<i class="icon-user"></i>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="exampleInputPassword" class="sr-only">Password</label>
							<div class="position-relative has-icon-right">
								<input id="password" name="password" type="password" class="form-control input-shadow p-4" placeholder="Enter Password" required="">
								<div class="form-control-position">
									<i class="icon-lock"></i>
								</div>
							</div>
						</div>
						<input type="submit" class="btn btn-light btn-block p-3" value="Log In" name="login">
						<!-- <div class="text-center mt-3">Sign In With</div>
			  
			 <div class="form-row mt-4">
			  <div class="form-group mb-0 col-6">
			   <button type="button" class="btn btn-light btn-block"><i class="fa fa-facebook-square"></i> Facebook</button>
			 </div>
			 <div class="form-group mb-0 col-6 text-right">
			  <button type="button" class="btn btn-light btn-block"><i class="fa fa-twitter-square"></i> Twitter</button>
			 </div> -->
				</div>

				</form>
			</div>
		</div>
	</div>

	</div><!--wrapper-->

	<!-- Bootstrap core JavaScript-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/popper.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<!-- sidebar-menu js -->
	<script src="assets/js/sidebar-menu.js"></script>
	<!-- Custom scripts -->
	<script src="assets/js/app-script.js"></script>
	<script src="assets/plugins/alerts-boxes/js/sweetalert.min.js"></script>
	<?php error_reporting(0);
	if ($_REQUEST['failed'] != '') { ?>
		<script type="text/javascript">
			swal("Failed!", "Login failed due to invalid login details provided.", "error");
		</script>
	<?php } ?>
</body>

</html>