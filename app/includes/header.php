<?php
ob_start();

error_reporting(0);
include_once './class/databaseConn.php';
include_once './lib/requestHandler.php';
$DatabaseCo = new DatabaseConn();

include_once './class/XssClean.php';
$xssClean = new xssClean();

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
  echo "<script>window.location='login.php';</script>";
  exit();
}

// Initialize default values
$user_role = "Unknown";
$user_name = "Guest";
$user_email = "No Email";
$user_photos = ""; // Default empty photo

// Check if the session role is set
if (isset($_SESSION['role'])) {
  switch ($_SESSION['role']) {
    case 'admin':
      // Admin logic
      $user_role = "Admin";
      $user_name = "Aeroflame"; // Default admin name
      $user_email = "Aeroflame@gmail.com"; // Default admin email
      break;

    case 'staff':
      // Staff logic - Fetch staff details dynamically
      $staff_query = $DatabaseCo->dbLink->query("SELECT username, photo, email FROM `staff` WHERE staff_id='" .
        $DatabaseCo->dbLink->real_escape_string($_SESSION["staff_id"]) . "'");
      $staff_data = mysqli_fetch_assoc($staff_query);

      if ($staff_data) {
        $user_role = "Staff";
        $user_name = $staff_data['username'] ?? "Unknown Staff"; // Staff name
        $user_email = $staff_data['email'] ?? "No Email"; // Staff email
        $user_photos = $staff_data['photo'] ?? ""; // Staff photo
      }
      break;

    case 'supervisor':
      // Supervisor logic - Fetch details dynamically (if stored in a separate table)
      $supervisor_query = $DatabaseCo->dbLink->query("SELECT username, photo, email FROM `supervisor` WHERE supervisor_id='" .
        $DatabaseCo->dbLink->real_escape_string($_SESSION["supervisor_id"]) . "'");
      $supervisor_data = mysqli_fetch_assoc($supervisor_query);

      if ($supervisor_data) {
        $user_role = "Supervisor";
        $user_name = $supervisor_data['username'] ?? "Unknown Supervisor"; // Supervisor name
        $user_email = $supervisor_data['email'] ?? "No Email"; // Supervisor email
        $user_photos = $supervisor_data['photo'] ?? ""; // Supervisor photo
      }
      break;
  }
}

// Output user email (for testing)

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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

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
        <li class="sidebar-header">Ticket Management</li>
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

            <i class="zmdi zmdi-comment-text" style="font-size: 19px;"></i>
            <span>Customer Feedback</span>
          </a>
          <ul class="sidebar-submenu">
            <li><a href="view_feedback.php"><i class="zmdi zmdi-dot-circle-alt"></i>Feedback View</a></li>
          
          </ul>
        </li>


        <li class="sidebar-header">Annual Maintenance Contract</li>
        <li>
          <a href="javaScript:void();" class="waves-effect">

            <i class="zmdi zmdi-ticket-star gold-icon" style="font-size: 19px;"></i> <span>Annual Maintenance</span>
          </a>
          <ul class="sidebar-submenu">
            <li><a href="list_amc.php"><i class="zmdi zmdi-dot-circle-alt"></i> All AMC Contracts</a></li>
            <li><a href="create_amc.php"><i class="zmdi zmdi-dot-circle-alt"></i>Add AMC Contract  </a></li>
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

        <!-- <li><a href="#">
            <i class="zmdi zmdi-store gold-icon" style="font-size: 19px;"></i> Vendors
          </a></li> -->
        <li class="sidebar-header">Spares Inventory Management</li>
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
          <a href="javascript:void(0);" class="waves-effect">
            <i class="zmdi zmdi-store gold-icon" style="font-size: 19px;"></i> <span>Purchase Management</span>
          </a>
          <ul class="sidebar-submenu">
            <li><a href="create_order.php"><i class="zmdi zmdi-plus-circle"></i> Add Purchase Order</a></li>
            <li><a href="view_orders.php"><i class="zmdi zmdi-receipt"></i> Purchase Orders</a></li>
            <li><a href="low_stock_alert.php"><i class="zmdi zmdi-alert-circle"></i> Low Stock Alert</a></li>
          </ul>
        </li>

        <li>
          <a href="javascript:void(0);" class="waves-effect">
            <i class="zmdi zmdi-assignment gold-icon" style="font-size: 19px;"></i> <span>Issue Tracking</span>
          </a>
          <ul class="sidebar-submenu">
            <li><a href="assigning_spares.php"><i class="zmdi zmdi-wrench"></i> Create Issued Spare</a></li>
            <li><a href="issued_spares_list.php"><i class="zmdi zmdi-format-list-bulleted"></i> Issued Spares List</a></li>
          </ul>
        </li>

        <li>
          <a href="javascript:void(0);" class="waves-effect">
            <i class="zmdi zmdi-check-circle gold-icon" style="font-size: 19px;"></i> <span>Stock Auditing</span>
          </a>
          <ul class="sidebar-submenu">
            <li><a href="add_audit.php"><i class="zmdi zmdi-file-plus"></i> Add Stock Audit</a></li>
            <li><a href="stock_audit_list.php"><i class="zmdi zmdi-collection-item"></i> Stock Audit List</a></li>
          </ul>
        </li>

        <li class="sidebar-header">Technician Management</li>
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
            <i class="bi bi-person-workspace gold-icon mr-2" style="font-size: 19px;"></i> <span>Assign Technician</span>


          </a>
          <ul class="sidebar-submenu">
            <li><a href="technician_assign.php"><i class="zmdi zmdi-dot-circle-alt"></i>Technician Assign</a></li>

          </ul>
        </li>
        <!-- <li>
          <a href="javaScript:void();" class="waves-effect">

            <i class="zmdi zmdi-wrench gold-icon mr-2" style="font-size: 19px;"></i><span>Technician Assignment</span>
          </a>
          <ul class="sidebar-submenu">
            <li><a href="technician_assign.php"><i class="zmdi zmdi-dot-circle-alt"></i>Technician Assign</a></li>

          </ul>
        </li> -->
        <li class="sidebar-header">Supervisor Management</li>
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
        <li>
          <a href="javascript:void(0);" class="waves-effect">
            <i class="zmdi zmdi-assignment-check gold-icon mr-2" style="font-size: 19px;"></i>
            <span>Ticket Resolutions</span>
          </a>
          <ul class="sidebar-submenu">
            <li><a href="ticket_resolutions.php"><i class="zmdi zmdi-format-list-bulleted"></i> All Resolutions</a></li>
          </ul>
        </li>

        <li>
          <a href="javascript:void(0);" class="waves-effect">
            <i class="zmdi zmdi-trending-up gold-icon mr-2" style="font-size: 19px;"></i>
            <span>Staff KPI Tracking</span>
          </a>
          <ul class="sidebar-submenu">
            <li><a href="staff_track.php"><i class="zmdi zmdi-bar-chart"></i> Staff KPIs</a></li>
          </ul>
        </li>

        <li class="sidebar-header">Master Management</li>
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
          <a href="javaScript:void();" class="waves-effect">

            <i class="fa fa-globe" style="font-size: 19px;"></i>
            <span>Country</span>
          </a>
          <ul class="sidebar-submenu">
            <li><a href="country.php"><i class="zmdi zmdi-dot-circle-alt"></i>All Country</a></li>
            <li><a href="country_add.php"><i class="zmdi zmdi-dot-circle-alt"></i>Add New Country </a></li>
          </ul>
        </li>
        <li>
          <a href="javascript:void(0);" class="waves-effect">
            <i class="bi bi-map gold-icon mr-2" style="font-size: 19px;"></i>


            <span>State</span>
          </a>

          <ul class="sidebar-submenu">
            <li><a href="state.php"><i class="zmdi zmdi-dot-circle-alt"></i>All State</a></li>
            <li><a href="state_add.php"><i class="zmdi zmdi-dot-circle-alt"></i>Add New State </a></li>
          </ul>
        </li>
        <li>
          <a href="javascript:void(0);" class="waves-effect">
            <i class="bi bi-buildings gold-icon mr-2" style="font-size: 19px;"></i>



            <span>City</span>
          </a>



          <ul class="sidebar-submenu">
            <li><a href="city.php"><i class="zmdi zmdi-dot-circle-alt"></i>All City</a></li>
            <li><a href="city_add.php"><i class="zmdi zmdi-dot-circle-alt"></i>Add New City </a></li>
          </ul>
        </li>
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
              <?php if ($user_role === 'Admin'): ?>
                <span class="user-profile"><img src="assets/images/logo.png" class="img-circle" alt="user avatar"></span>
              <?php else: ?>
                <span class="user-profile"><img src="../uploads/staff/<?php echo ucfirst($user_photos); ?>" class="img-circle" alt="user avatar"></span>
              <?php endif; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
              <li class="dropdown-item user-details">
                <a href="javaScript:void();">
                  <div class="media">
                    <?php if ($user_role === 'Admin'): ?>
                      <div class="avatar"><img class="align-self-start mr-3" src="assets/images/logo.png" alt="user avatar"></div>
                    <?php else: ?>
                      <div class="avatar"><img class="align-self-start mr-3" src="../uploads/staff/<?php echo ucfirst($user_photos); ?>" alt="user avatar"></div>
                    <?php endif; ?>
                    <div class="media-body">
                      <h6 class="mt-2 user-title"><?php echo htmlspecialchars($user_name); ?></h6>


                      <p class="user-subtitle"><?php echo htmlspecialchars($user_email); ?></p>
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