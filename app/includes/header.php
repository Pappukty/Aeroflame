<?php ob_start();
error_reporting(0);
include_once './class/databaseConn.php';
include_once './lib/requestHandler.php';
$DatabaseCo = new DatabaseConn();

include_once './class/XssClean.php';
$xssClean = new xssClean();

if (($_SESSION['user_id']) == '') {
  echo "<script>window.location='login.php';</script>";
  exit();
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
  <title>Aeroflame</title>
  <!--favicon-->
  <link rel="icon" href="assets/images/logo.png" type="image/x-icon">
  <!-- Vector CSS -->
  <!-- Vector CSS -->
  <link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
  <!-- simplebar CSS-->
  <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
  <!-- Bootstrap core CSS-->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
  <!-- animate CSS-->
  <link href="assets/css/animate.css" rel="stylesheet" type="text/css" />
  <!-- Icons CSS-->
  <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
  <!-- Sidebar CSS-->
  <link href="assets/css/sidebar-menu.css" rel="stylesheet" />
  <link href="../../css/style2.css">
  <!-- Custom Style-->
  <link href="assets/css/app-style.css" rel="stylesheet" />
  <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <style type="text/css">
    .card .table td,
    .card .table th {
      padding-right: 0.5rem;
      padding-left: 0.5rem;
    }

    .sidebar-menu .sidebar-submenu>li>a {
      padding: 10px 5px 15px 40px;
    }

    .uploadlink {
      position: relative;
      cursor: pointer;
    }

    a.uploadlink {
      padding: 7px 10px;
      color: #fff;
    }

    .upload {
      position: absolute;
      top: 0;
      left: 0;
      margin: 0;
      cursor: pointer;
      opacity: 0;
      height: 33px;
    }
  </style>

  <!-- Bootstrap core JavaScript-->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/popper.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <!--notification js -->
  <link rel="stylesheet" href="assets/plugins/notifications/css/lobibox.min.css" />
  <script src="assets/plugins/notifications/js/lobibox.min.js"></script>
  <script src="assets/plugins/notifications/js/notifications.min.js"></script>
  <script src="assets/plugins/notifications/js/notification-custom-script.js"></script>
  <!-- Toastr CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
  <!-- Toastr JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</head>

<body class="bg-theme bg-theme4">

  <!-- start loader -->
  <div id="pageloader-overlay" class="visible incoming d-none">
    <div class="loader-wrapper-outer">
      <div class="loader-wrapper-inner">
        <div class="loader"></div>
      </div>
    </div>
  </div>
  <!-- end loader -->

  <!-- Start wrapper-->
  <div id="wrapper">

    <!--Start sidebar-wrapper-->
    <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
      <div class="brand-logo">
        <a href="index.php">
          <img src="assets/images/logo.png" class="logo-icon" alt="logo icon">
          <span class="logo-text">Aeroflame</span>
        </a>
      </div>
      <ul class="sidebar-menu">
        <li class="sidebar-header">MAIN NAVIGATION</li>
        <li>
          <a href="index.php" class="waves-effect">
            <i class="zmdi zmdi-view-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li>
          <!-- <a href="javaScript:void();" class="waves-effect">
        <i class="zmdi zmdi-money gold-icon"></i> <span>Tickets s</span>
        </a> -->
        </li>

        <li>
          <a href="javaScript:void();" class="waves-effect">

            <i class="zmdi zmdi-ticket-star gold-icon" style="font-size: 19px;"></i> <span>Tickets</span>
          </a>
          <ul class="sidebar-submenu">
            <li><a href="tickets.php"><i class="zmdi zmdi-dot-circle-alt"></i>All Tickets</a></li>
            <li><a href="ticket_add.php"><i class="zmdi zmdi-dot-circle-alt"></i>Add New Tickets </a></li>
          </ul>
        </li>






        <li>
          <a href="javaScript:void();" class="waves-effect">

            <i class="zmdi zmdi-account-circle gold-icon" style="font-size: 19px;"></i> <span>Customers</span>
          </a>
          <ul class="sidebar-submenu">
            <li><a href="customer_list.php"><i class="zmdi zmdi-dot-circle-alt"></i>All Customers</a></li>
            <li><a href="customer_add.php"><i class="zmdi zmdi-dot-circle-alt"></i>Add New Customers</a></li>
          </ul>
        </li>

        <li><a href="#">
            <i class="zmdi zmdi-store gold-icon" style="font-size: 19px;"></i> Vendors
          </a></li>

        <li>
          <a href="javaScript:void();" class="waves-effect">

            <i class="zmdi zmdi-apps gold-icon" style="font-size: 19px;"></i> <span>Inventory</span>
          </a>
          <ul class="sidebar-submenu">
            <li><a href="inventory_list.php"><i class="zmdi zmdi-dot-circle-alt"></i>All Inventory</a></li>
            <li><a href="inventory_add.php"><i class="zmdi zmdi-dot-circle-alt"></i>Add New Inventory </a></li>
          </ul>
        </li>

        <li>
          <a href="javaScript:void();" class="waves-effect">

          <i class="zmdi zmdi-wrench gold-icon mr-2" style="font-size: 19px;"></i><span>Technician</span>
          </a>
          <ul class="sidebar-submenu">
            <li><a href="technician.php"><i class="zmdi zmdi-dot-circle-alt"></i>All Technician</a></li>
            <li><a href="technician_add.php"><i class="zmdi zmdi-dot-circle-alt"></i>Add New Technician </a></li>
          </ul>
        </li>
        <li>
          <a href="javaScript:void();" class="waves-effect">

          <i class="zmdi zmdi-accounts gold-icon mr-2" style="font-size: 19px;"></i>
<span>Staff</span>
          </a>
          <ul class="sidebar-submenu">
            <li><a href="staff.php"><i class="zmdi zmdi-dot-circle-alt"></i>All Staff</a></li>
            <li><a href="staff_add.php"><i class="zmdi zmdi-dot-circle-alt"></i>Add New Staff </a></li>
          </ul>
        </li>
        <!-- <li>
        <a href="javaScript:void();" class="waves-effect">
          <i class="zmdi zmdi-file"></i> <span>Customers</span>
          <i class="fa fa-angle-left float-right"></i>
        </a>
        <ul class="sidebar-submenu">
          <li><a href="forum.php"><i class="zmdi zmdi-dot-circle-alt"></i>All Customers</a></li>
          <li><a href="forum-add.php"><i class="zmdi zmdi-dot-circle-alt"></i>Add New Customers</a></li>
        </ul>
       </li> -->
        <!-- <li>
        <a href="javaScript:void();" class="waves-effect">
          <i class="zmdi zmdi-account"></i> <span>Staff</span>
          <i class="fa fa-angle-left float-right"></i>
        </a>
        <ul class="sidebar-submenu">
          <li><a href="leader.php"><i class="zmdi zmdi-dot-circle-alt"></i>All Staff</a></li>
          <li><a href="leader-add.php"><i class="zmdi zmdi-dot-circle-alt"></i>Add New Staff</a></li>
        </ul>
       </li> -->
        <!-- <li>
        <a href="javaScript:void();" class="waves-effect">
          <i class="zmdi zmdi-book"></i> <span>Setting</span>
          <i class="fa fa-angle-left float-right"></i>
        </a>
        <ul class="sidebar-submenu">
         <li><a href=""><i class="zmdi zmdi-dot-circle-alt"></i>profile </a></li>
          <li><a href=""><i class="zmdi zmdi-dot-circle-alt"></i>privacy policy</a></li>
        </ul>
       </li> -->
        <!-- <li>
        <a href="javaScript:void();" class="waves-effect">
          <i class="zmdi zmdi-book"></i> <span>Events</span>
          <i class="fa fa-angle-left float-right"></i>
        </a>
        <ul class="sidebar-submenu">
          <li><a href=""><i class="zmdi zmdi-dot-circle-alt"></i>All Events</a></li>
          <li><a href=""><i class="zmdi zmdi-dot-circle-alt"></i>Add New Event</a></li>
        </ul>
       </li> -->
        <li>
          <!-- <a href="javaScript:void();" class="waves-effect">
          <i class="zmdi zmdi-view-dashboard"></i> <span>Testimonies</span>
          <i class="fa fa-angle-left float-right"></i>
        </a> -->
          <!-- <ul class="sidebar-submenu">
          <li><a href=""><i class="zmdi zmdi-dot-circle-alt"></i>All Videos</a></li>
          <li><a href=""><i class="zmdi zmdi-dot-circle-alt"></i>Add New Video</a></li>
          <li><a href=""><i class="zmdi zmdi-dot-circle-alt"></i>All Testimonies</a></li>
          <li><a href=""><i class="zmdi zmdi-dot-circle-alt"></i>Add New Text</a></li>
        </ul> -->
        </li>
        <!-- <li>
        <a href="javaScript:void();" class="waves-effect">
          <i class="zmdi zmdi-image"></i> <span>Photo Gallery</span>
          <i class="fa fa-angle-left float-right"></i>
        </a>
        <ul class="sidebar-submenu">
          <li><a href="gallery.php"><i class="zmdi zmdi-dot-circle-alt"></i>Gallery List</a></li>
          <li><a href="galleryadd.php"><i class="zmdi zmdi-dot-circle-alt"></i>Gallery Add</a></li>
          <li><a href="gallerycategory.php"><i class="zmdi zmdi-dot-circle-alt"></i>Gallery Categories</a></li>
        </ul>
       </li> -->
        <!-- <li>
        <a href="javaScript:void();" class="waves-effect">
          <i class="zmdi zmdi-play"></i> <span>Video Gallery</span>
          <i class="fa fa-angle-left float-right"></i>
        </a>
        <ul class="sidebar-submenu">
          <li><a href="videogallery.php"><i class="zmdi zmdi-dot-circle-alt"></i>Video Gallery List</a></li>
          <li><a href="videogalleryadd.php"><i class="zmdi zmdi-dot-circle-alt"></i>Video Gallery Add</a></li>
          <li><a href="videogallerycategory.php"><i class="zmdi zmdi-dot-circle-alt"></i>Video Gallery Categories</a></li>
        </ul>
       </li> -->
        <li>
          <a href="logout.php" class="waves-effect">
            <i class="icon-power mr-2"></i> <span>Logout</span>
          </a>
        </li>
      </ul>

    </div>
    <!--End sidebar-wrapper-->

    <!--Start topbar header-->
    <header class="topbar-nav">
      <nav class="navbar navbar-expand fixed-top">
        <ul class="navbar-nav mr-auto align-items-center">
          <li class="nav-item">
            <a class="nav-link toggle-menu" href="javascript:void();">
              <i class="icon-menu menu-icon"></i>
            </a>
          </li>
        </ul>

        <ul class="navbar-nav align-items-center right-nav-link">
          <li class="nav-item">
            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown" href="#">
              <span class="user-profile"><img src="assets/images/logo.png" class="img-circle" alt="user avatar"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
              <li class="dropdown-item user-details">
                <a href="javaScript:void();">
                  <div class="media">
                    <div class="avatar"><img class="align-self-start mr-3" src="assets/images/logo.png" alt="user avatar"></div>
                    <div class="media-body">
                      <?php if ($_SESSION["user_id"] == 1) { ?>
                        <h6 class="mt-2 user-title">Admin</h6>
                      <?php } else { ?>
                        <h6 class="mt-2 user-title">user</h6>
                      <?php } ?>
                      <p class="user-subtitle">admin@gmail.com</p>
                    </div>
                  </div>
                </a>
              </li>
              <!-- <li class="dropdown-item"><i class="icon-settings mr-2"></i> Setting</li> -->
              <li class="dropdown-divider"></li>
              <li class="dropdown-item"><i class="icon-power mr-2"></i>
                <a href="logout.php">Logout</a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
    </header>
    <!--End topbar header-->

    <div class="clearfix"></div>

    <div class="content-wrapper">
      <div class="container-fluid">