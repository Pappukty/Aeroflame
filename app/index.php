<?php include_once './includes/header.php';

// $Qcount1 = $DatabaseCo->dbLink->query("SELECT COUNT(id) as count FROM Tickets s") or die(mysqli_error($DatabaseCo->dbLink));
// $count1 = mysqli_fetch_object($Qcount1);
// $Qcount2 = $DatabaseCo->dbLink->query("SELECT COUNT(id) as count FROM Tickets s WHERE week(log_date)=week(now())");
// $count2 = mysqli_fetch_object($Qcount2);
// $Qcount3 = $DatabaseCo->dbLink->query("SELECT COUNT(id) as count FROM Tickets s WHERE month(log_date)=month(now())");
// $count3 = mysqli_fetch_object($Qcount3);

// $Qcount4 = $DatabaseCo->dbLink->query("SELECT COUNT(index_id) as count FROM registration");
// $count4 = mysqli_fetch_object($Qcount4);
// $Qcount5 = $DatabaseCo->dbLink->query("SELECT COUNT(index_id) as count FROM registration WHERE week(log_date)=week(now())");
// $count5 = mysqli_fetch_object($Qcount5);
// $Qcount6 = $DatabaseCo->dbLink->query("SELECT COUNT(index_id) as count FROM registration WHERE month(log_date)=month(now())");
// $count6 = mysqli_fetch_object($Qcount6);

?>

<!--Start Dashboard Content-->
<div>
  <div>
    <h4>Welcome To The Admin Panel</h3>

  </div>


</div>




<div>
  <h5>Total Overview</h5>
</div>

<div class="row mt-3">
  <!-- All Tickets -->
  <div class="col-12 col-lg-6 col-xl-4">
    <div class="card">
      <div class="card-body">
        <div class="media align-items-center">
          <div class="w-icon">
            <i class="fa fa-ticket text-white"></i> <!-- Ticket Icon -->
          </div>
          <div class="media-body ml-3 border-left-xs border-light-3">
            <h4 class="mb-0 ml-3">37</h4>
            <p class="mb-0 ml-3 extra-small-font">All Tickets</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Inventory -->
  <div class="col-12 col-lg-6 col-xl-4">
    <div class="card">
      <div class="card-body">
        <div class="media align-items-center">
          <div class="w-icon">
            <i class="fa fa-box text-white"></i> <!-- Inventory Icon -->
          </div>
          <div class="media-body ml-3 border-left-xs border-light-3">
            <h4 class="mb-0 ml-3">540</h4>
            <p class="mb-0 ml-3 extra-small-font">Total Inventory</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Total Customers -->
  <div class="col-12 col-lg-6 col-xl-4">
    <div class="card">
      <div class="card-body">
        <div class="media align-items-center">
          <div class="w-icon">
            <i class="fa fa-users text-white"></i> <!-- Customer Icon -->
          </div>
          <div class="media-body ml-3 border-left-xs border-light-3">
            <h4 class="mb-0 ml-3">230</h4>
            <p class="mb-0 ml-3 extra-small-font">Total Customers</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><!-- End Row -->

<h5>Existing Statistics</h5>

<div class="row mt-3">
  <!-- Tickets This Week -->
  <div class="col-12 col-lg-6 col-xl-4">
    <div class="card">
      <div class="card-body">
        <div class="media align-items-center">
          <div class="w-icon">
            <i class="fa fa-calendar-week text-white"></i> <!-- Calendar Week Icon -->
          </div>
          <div class="media-body ml-3 border-left-xs border-light-3">
            <h4 class="mb-0 ml-3">35</h4>
            <p class="mb-0 ml-3 extra-small-font">Tickets This Week</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Inventory This Week -->
  <div class="col-12 col-lg-6 col-xl-4">
    <div class="card">
      <div class="card-body">
        <div class="media align-items-center">
          <div class="w-icon">
            <i class="fa fa-box-open text-white"></i> <!-- Inventory Week Icon -->
          </div>
          <div class="media-body ml-3 border-left-xs border-light-3">
            <h4 class="mb-0 ml-3">60</h4>
            <p class="mb-0 ml-3 extra-small-font">Inventory This Week</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Customers This Week -->
  <div class="col-12 col-lg-6 col-xl-4">
    <div class="card">
      <div class="card-body">
        <div class="media align-items-center">
          <div class="w-icon">
            <i class="fa fa-user-plus text-white"></i> <!-- Customers Week Icon -->
          </div>
          <div class="media-body ml-3 border-left-xs border-light-3">
            <h4 class="mb-0 ml-3">15</h4>
            <p class="mb-0 ml-3 extra-small-font">Customers This Week</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><!-- End Row -->

<div class="row mt-3">
  <!-- Tickets This Month -->
  <div class="col-12 col-lg-6 col-xl-4">
    <div class="card">
      <div class="card-body">
        <div class="media align-items-center">
          <div class="w-icon">
            <i class="fa fa-calendar-alt text-white"></i> <!-- Calendar Month Icon -->
          </div>
          <div class="media-body ml-3 border-left-xs border-light-3">
            <h4 class="mb-0 ml-3">150</h4>
            <p class="mb-0 ml-3 extra-small-font">Tickets This Month</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Inventory This Month -->
  <div class="col-12 col-lg-6 col-xl-4">
    <div class="card">
      <div class="card-body">
        <div class="media align-items-center">
          <div class="w-icon">
            <i class="fa fa-boxes text-white"></i> <!-- Inventory Month Icon -->
          </div>
          <div class="media-body ml-3 border-left-xs border-light-3">
            <h4 class="mb-0 ml-3">240</h4>
            <p class="mb-0 ml-3 extra-small-font">Inventory This Month</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Customers This Month -->
  <div class="col-12 col-lg-6 col-xl-4">
    <div class="card">
      <div class="card-body">
        <div class="media align-items-center">
          <div class="w-icon">
            <i class="fa fa-user-check text-white"></i> <!-- Customers Month Icon -->
          </div>
          <div class="media-body ml-3 border-left-xs border-light-3">
            <h4 class="mb-0 ml-3">45</h4>
            <p class="mb-0 ml-3 extra-small-font">Customers This Month</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><!-- End Row -->

<div class="row mt-3">
  <!-- Completed Tickets -->
  <div class="col-12 col-lg-6 col-xl-4">
    <div class="card">
      <div class="card-body">
        <div class="media align-items-center">
          <div class="w-icon">
            <i class="fa fa-check-circle text-white"></i> <!-- Completed Tickets Icon -->
          </div>
          <div class="media-body ml-3 border-left-xs border-light-3">
            <h4 class="mb-0 ml-3">98</h4>
            <p class="mb-0 ml-3 extra-small-font">Completed Tickets</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Completed Inventory -->
  <div class="col-12 col-lg-6 col-xl-4">
    <div class="card">
      <div class="card-body">
        <div class="media align-items-center">
          <div class="w-icon">
            <i class="fa fa-clipboard-check text-white"></i> <!-- Completed Inventory Icon -->
          </div>
          <div class="media-body ml-3 border-left-xs border-light-3">
            <h4 class="mb-0 ml-3">185</h4>
            <p class="mb-0 ml-3 extra-small-font">Completed Inventory</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Completed Customers -->
  <div class="col-12 col-lg-6 col-xl-4">
    <div class="card">
      <div class="card-body">
        <div class="media align-items-center">
          <div class="w-icon">
            <i class="fa fa-user-check text-white"></i> <!-- Completed Customers Icon -->
          </div>
          <div class="media-body ml-3 border-left-xs border-light-3">
            <h4 class="mb-0 ml-3">120</h4>
            <p class="mb-0 ml-3 extra-small-font">Completed Customers</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><!-- End Row -->


<div class="mt-5 pt-5">&nbsp;</div>
<div class="mt-5 pt-5">&nbsp;</div>

<!--End Dashboard Content-->
<?php
include_once './includes/footer.php';
?>