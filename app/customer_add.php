<?php 
include_once './includes/header.php';
include_once './class/fileUploader.php';
error_reporting(1);

if (isset($_REQUEST['submit'])) {
    // Collect POST data and sanitize
    $customerType = $_POST['customer_type'];
    $companyName = isset($_POST['company_name']) ? $_POST['company_name'] : null; // Optional field
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $amcStatus = $_POST['amc_status'];
    $feedback = $_POST['feedback'];

    // Check if updating or inserting
    if ($_REQUEST['id'] > 0) {
        $d_id = $_REQUEST['id'];

        // Use prepared statement for updating data to prevent SQL injection
        $stmt = $DatabaseCo->dbLink->prepare("UPDATE `customer` SET `customer_type` = ?, `company_name` = ?, `first_name` = ?, `last_name` = ?, `email` = ?, `phone_number` = ?, `address` = ?, `city` = ?, `amc_status` = ? WHERE `id` = ?");
        $stmt->bind_param("sssssssssi", $customerType, $companyName, $firstName, $lastName, $email, $phoneNumber, $address, $city, $amcStatus, $d_id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Use prepared statement for inserting data
        $stmt = $DatabaseCo->dbLink->prepare("INSERT INTO `customer` (customer_type, company_name, first_name, last_name, email, phone_number, address, city, amc_status, feedback) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $customerType, $companyName, $firstName, $lastName, $email, $phoneNumber, $address, $city, $amcStatus, $feedback);
        $stmt->execute();
        $d_id = $stmt->insert_id;  // Get the last inserted ID
        $stmt->close();
    }

    // If you have file uploads, use the following (commented out code was added to handle file uploads)
    /*
    $uploadimage = new ImageUploader($DatabaseCo);
    $photos = is_uploaded_file($_FILES['photos']["tmp_name"]) ? $uploadimage->upload($_FILES['photos'], "Tickets ") : '';

    if ($photos != '') {
        $stmt = $DatabaseCo->dbLink->prepare("UPDATE `Tickets` SET photos = ? WHERE id = ?");
        $stmt->bind_param("si", $photos, $d_id);
        $stmt->execute();
        $stmt->close();
    }
    */

    // Redirect to customer list page after insert/update
    header("location: customer_list.php");
    exit(); // Make sure to stop the script execution here.
}

// If editing an existing customer, retrieve the data
if ($_REQUEST['id'] > 0) {
    $titl = "Update ";
    $stmt = $DatabaseCo->dbLink->prepare("SELECT * FROM customer WHERE id = ?");
    $stmt->bind_param("i", $_REQUEST['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $Row = $result->fetch_object();
    $stmt->close();
} else {
    $titl = "Add New ";
}
?>

<!-- Page Header -->
<div class="page-header">
  <div class="row">
    <div class="col-sm-12">
      <h3 class="page-title"><?php echo $titl; ?> Customer </h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="Tickets .php">Customer Listing</a></li>
        <li class="breadcrumb-item active"><?php echo $titl; ?> Customer </li>
      </ul>
    </div>
  </div>
</div>
<!-- /Page Header -->

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <!--<p class="card-title-desc">Please fill the required leader details.</p>-->
        <form action="" method="post" name="finish-form" enctype="multipart/form-data" class="needs-validation">
    <div class="row">
        <!-- Customer Type -->
        <div class="col-md-6 col-12 mb-3">
    <label class="form-label">Customer Type</label>
    <select class="form-control" name="customer_type" id="customerType" required>
        <option value="B2C" <?php if($Row->customer_type == "B2C") echo "selected"; ?>>B2C</option>
        <option value="B2B" <?php if($Row->customer_type == "B2B") echo "selected"; ?>>B2B</option>
    </select>
</div>

        <!-- Company Name (For B2B) -->
        <div class="col-md-6 col-12 mb-3 d-none" id="companyField">
            <label class="form-label">Company Name</label>
            <input type="text" class="form-control" name="company_name" value="<?php echo $Row->company_name; ?>" placeholder="Enter Company Name" required>
        </div>
        <!-- First Name -->
        <div class="col-md-6 col-12 mb-3">
            <label class="form-label">First Name</label>
            <input type="text" class="form-control" name="first_name" value="<?php echo $Row->first_name; ?>" placeholder="Enter First Name" required>
        </div>
        <!-- Last Name -->
        <div class="col-md-6 col-12 mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" class="form-control" name="last_name" value="<?php echo $Row->last_name; ?>" placeholder="Enter Last Name">
        </div>
        <!-- Email -->
        <div class="col-md-6 col-12 mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo $Row->email; ?>" placeholder="Enter Email" required>
        </div>
        <!-- Phone Number -->
        <div class="col-md-6 col-12 mb-3">
            <label class="form-label">Phone Number</label>
            <input type="tel" class="form-control" name="phone_number" value="<?php echo $Row->phone_number; ?>" placeholder="Enter Phone Number" required>
        </div>
        <!-- Address -->
        <div class="col-md-6 col-12 mb-3">
            <label class="form-label">Address</label>
            <input type="text" class="form-control" name="address" value="<?php echo $Row->address; ?>" placeholder="Enter Address">
        </div>
        <!-- City -->
        <div class="col-md-6 col-12 mb-3">
            <label class="form-label">City</label>
            <input type="text" class="form-control" name="city" value="<?php echo $Row->city; ?>" placeholder="Enter City" required>
        </div>
        <!-- AMC Status -->
        <div class="col-md-6 col-12 mb-3">
    <label class="form-label">AMC Status</label>
    <select class="form-control" name="amc_status" required>
        <option value="Active" <?php if(isset($Row->amc_status) && $Row->amc_status == "Active") echo "selected"; ?>>Active</option>
        <option value="Expired" <?php if(isset($Row->amc_status) && $Row->amc_status == "Expired") echo "selected"; ?>>Expired</option>
        <option value="Not Enrolled" <?php if(isset($Row->amc_status) && $Row->amc_status == "Not Enrolled") echo "selected"; ?>>Not Enrolled</option>
    </select>
</div>

        <!-- Feedback -->
        <div class="col-md-6 col-12 mb-3">
            <label class="form-label">Feedback</label>
            <textarea class="form-control" name="feedback" value="" placeholder="Enter Feedback"><?php echo $Row->feedback; ?></textarea>
        </div>
        <!-- Submit Button -->
        <div class="col-12 text-center mt-3">
        <input name="submit" type="submit" class="btn btn-primary" value="<?php echo $titl; ?> Details" />
        </div>
    </div>
</form>

      </div>
    </div>


  </div>

</div>
<!-- end row -->

<?php
include_once './includes/footer.php';
?>
<script src="https://cdn.ckeditor.com/4.16.2/standard-all/ckeditor.js"></script>
<script type="text/javascript">
  CKEDITOR.replace('editor1', {
    extraPlugins: 'editorplaceholder',
    removeButtons: 'PasteFromWord'
  });
</script>


<style>
  .is-invalid {
    border-color: #dc3545;
  }

  .invalid-feedback {
    color: #dc3545;
    font-size: 0.875em;
    margin-top: 0.25rem;
  }
</style>
 
<script>
        document.getElementById("customerType").addEventListener("change", function () {
            let companyField = document.getElementById("companyField");
            if (this.value === "B2B") {
                companyField.classList.remove("d-none");
            } else {
                companyField.classList.add("d-none");
            }
        });
    </script>
      <script>
 $(document).ready(function() {
    $("#feedbackForm").submit(function(e) {
        e.preventDefault();

        // Check if any input fields are empty
        var isValid = true;
        $(".form-control").each(function() {
            if ($(this).val().trim() === "") {
                isValid = false;
                $(this).css("border-color", "#ff0000"); // Highlight empty fields in red
            } else {
                $(this).css("border-color", "#d1d9e6"); // Reset to original color
            }
        });

        // If any field is empty, show toastr error message
        if (!isValid) {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                showMethod: "fadeIn",
                hideMethod: "fadeOut",
                timeOut: 3000, // 3 seconds
                positionClass: "toast-top-right"
            };
            toastr.error("Please fill all the required fields.");
            return; // Stop form submission if validation fails
        }

        var formData = $(this).serialize();

        $.ajax({
            type: "POST",
            url: "process.php",
            data: formData,
            dataType: "json",
            success: function(response) {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: "fadeIn",
                    hideMethod: "fadeOut",
                    timeOut: 3000, // 3 seconds
                    positionClass: "toast-top-right"
                };

                if (response.status === "success") {
                    toastr.success("Tickets Successfully Submitted!");
                    $("#feedbackForm")[0].reset();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: "fadeIn",
                    hideMethod: "fadeOut",
                    timeOut: 3000,
                    positionClass: "toast-top-right"
                };
                toastr.error("An error occurred while processing your request.");
            }
        });
    });
});


    </script>
