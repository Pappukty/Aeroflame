<?php
include_once './includes/header.php';


if($_GET['id']) {
    $id=$_GET['id'];
}
$select = "SELECT * FROM staff WHERE id='$id'";//echo $select;
$SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink,$select);
$Row =mysqli_fetch_object($SQL_STATEMENT);



$photos = ($Row->photo !='')?'../uploads/staff/'.$Row->photo:'./assets/images/nophoto.png';

//ID 
// if($Row->id<=9){$staffID='00'.$Row->id;}
// elseif($Row->id>9 && $Row->id<=99){$staffID='0'.$Row->id;}
// else{$staffID=$Row->id;}
?>

<style>
    body {
      color: black;
      background-color: #333; /* Dark background for contrast */
    }
    
    .card {
      /* background-color: rgba(255,255,255,.125); */
      background-color: whitesmoke;
    }
    .card-title{
        color: black;
    }
    .header-title{
        color: black;
    }
    p {
      color: black;
    }
    a.btn-light {
      color: white; /* Ensures button text is readable on a light button */
    }
  </style>

<div class="container mt-5">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="mb-0">Staff Profile</h3>
      <a href="staff.php" class="btn btn-light btn-rounded waves-effect waves-light">
        <i class="mdi mdi-account-group mr-1"></i> Staff List
      </a>
    </div>

    <!-- Profile Content -->
    <div class="row">
      <!-- Avatar Card -->
      <div class="col-sm-4">
        <div class="card card-body text-center">
          <div class="avatar-wrapper mb-3">

            <img class="rounded-circle header-profile-user avatar-xl" src="../uploads/staff/<?php echo $Row->photo; ?>" >
          </div>
          <h4 class="card-title font-size-16 mt-0"><?php echo $Row->name; ?></h4>
          <p class="mb-3"><?php echo $Row->staff_id; ?></p>
        </div>
      </div>
      
      <!-- Detailed Information Card -->
      <div class="col-sm-8">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title"><?php echo $Row->name; ?></h4>
            <p class="card-title-desc" >Staff Profile</p>
            
            <!-- Nav tabs -->
            <ul class="nav nav-pills mb-3" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#profile-tab" role="tab">
                <i class="bi bi-person-fill mr-1"></i> Profile
                </a>
              </li>
            </ul>
            
            <!-- Tab panes -->
            <div class="tab-content">
              <div class="tab-pane active" id="profile-tab" role="tabpanel">
                <div class="row">
                  <div class="col-sm-6">
                    <!-- Name Info -->
                    <div class="info-block mb-3">
                      <strong>Name:</strong>
                      <p><?php echo $Row->username; ?></p>
                    </div>
                    <!-- Email Info -->
                    <div class="info-block mb-3">
                      <strong>Email:</strong>
                      <p><?php echo $Row->email; ?></p>
                    </div>
                    <!-- Contact Info -->
                    <div class="info-block mb-3">
                      <strong>Contact:</strong>
                      <p> <?php echo $Row->contact; ?></p>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <!-- Designation Info -->
                    <div class="info-block mb-3">
                      <strong>Designation:</strong>
                      <p>      <?php echo $Row->designation; ?></p>
                    </div>
                    <!-- Password Info -->
                    <div class="info-block mb-3">
                      <strong>Password:</strong>
                      <p>      <?php echo base64_decode($Row->password); ?></p>
                    </div>
                  </div>
                </div><!-- End row -->
              </div><!-- End tab-pane -->
            </div><!-- End tab-content -->
          </div><!-- End card-body -->
        </div><!-- End card -->
      </div><!-- End col-sm-8 -->
    </div><!-- End row -->
  </div><!-- End container -->

<?php
include_once './includes/footer.php';
$stmt->close();
$conn->close();
?>
