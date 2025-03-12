<?php
include_once './includes/header.php';
include_once './class/fileUploader.php';
error_reporting(1);



if (isset($_REQUEST['submit'])) {
    // Ensure XSS Cleaner is initialized
    if (!isset($xssClean)) {
        die("XSS Cleaner not initialized.");
    }

    // Clean input data
    $username = $xssClean->clean_input($_REQUEST['username']);
    $contact = $xssClean->clean_input($_REQUEST['contact']);
    $address = $xssClean->clean_input($_REQUEST['address']);
    $email = $xssClean->clean_input($_REQUEST['email']);
    $designation = $xssClean->clean_input($_REQUEST['designation']);
    $password=base64_encode($_REQUEST['password']);	

    // Ensure DatabaseCo is initialized
    if (!isset($DatabaseCo)) {
        die("Database connection not initialized.");
    }

    // Check if it's an update operation
    if (!empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
        $d_id = intval($_REQUEST['id']);

        // Fetch existing staff_id
        $stmt = $DatabaseCo->dbLink->prepare("SELECT staff_id FROM staff WHERE id=?");
        $stmt->bind_param("i", $d_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $staff_id = $row['staff_id'];
        $stmt->close();

        // Update existing staff record
        $stmt = $DatabaseCo->dbLink->prepare("UPDATE staff SET username=?, contact=?, address=?, email=?, designation=?, password=? WHERE id=?");
        $stmt->bind_param("ssssssi", $username, $contact, $address, $email, $designation, $password, $d_id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Generate a new unique Staff ID starting from AER001
        $stmt = $DatabaseCo->dbLink->prepare("SELECT MAX(CAST(SUBSTRING(staff_id, 4, 3) AS UNSIGNED)) AS max_id FROM staff WHERE staff_id LIKE 'AER%'");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        $next_id = ($row['max_id'] !== null) ? $row['max_id'] + 1 : 1;

        // Generate the staff ID (e.g., AER001, AER002, etc.)
        $staff_id = "AER" . str_pad($next_id, 3, '0', STR_PAD_LEFT);

        // Insert new staff record
        $stmt = $DatabaseCo->dbLink->prepare("INSERT INTO staff (staff_id, username, contact, address, email, designation, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $staff_id, $username, $contact, $address, $email, $designation, $password);
        $stmt->execute();
        $d_id = $stmt->insert_id; // Get last inserted ID for image update
        $stmt->close();
    }

    // Handle Image Upload
    if (isset($_FILES['photo']) && is_uploaded_file($_FILES['photo']['tmp_name'])) {
        $uploadimage = new ImageUploader($DatabaseCo);
        $photos = $uploadimage->upload($_FILES['photo'], "staff");
        if ($photos) {
            $stmt = $DatabaseCo->dbLink->prepare("UPDATE staff SET photo=? WHERE id=?");
            $stmt->bind_param("si", $photos, $d_id);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Redirect after completion
    header("Location: staff.php");
    exit();
}





if (!empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
    $titl = "Update";
    $stmt = $DatabaseCo->dbLink->prepare("SELECT * FROM staff WHERE id=?");
    $stmt->bind_param("i", $_REQUEST['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $Row = $result->fetch_object();
} else {
    $titl = "Add New";
}
?>

<style>
    /* Custom Styling */
    .select2-container .select2-selection--multiple {
        border: 2px solid #007bff;
        border-radius: 8px;
        padding: 5px;
        color: black;
        border: 0px solid #e5eaef;
        background-color: rgba(255, 255, 255, 0.2);
        /* color: #fff !important; */
    }

    .multiple option {
        color: black !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        color: black;
        border-radius: 5px;
        padding: 3px 8px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: black !important;
    }

    .select2-selection .select2-selection--multiple {
        color: black !important;
    }
</style>
<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title"><?php echo $titl; ?> Add Staff </h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="staff.php"> Staff Listing</a></li>
                <li class="breadcrumb-item active"><?php echo $titl; ?> Add Staff </li>
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
                <form action="" method="POST" enctype="multipart/form-data" class="needs-validation">
                    <input type="hidden" name="update_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="username" placeholder="Enter Name" value="<?php echo $Row->username ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" name="contact" placeholder="Enter Contact Number" value="<?php echo $Row->contact ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" placeholder="Enter Address" value="<?php echo $Row->address ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter Email" value="<?php echo $Row->email ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">

                            <label class="pull-left">Designation</label>
                            <div class="control has-icons-left">
                                <select name="designation" id="designation" required class="form-control">
                                    <option value="">Select Designation</option>
                                    <option value="Director" <?php echo ($Row->designation == "Director") ? "selected" : ""; ?>>Director</option>
                                    <option value="Branch Manager" <?php echo ($Row->designation == "Branch Manager") ? "selected" : ""; ?>>Branch Manager</option>
                                    <option value="Accountant" <?php echo ($Row->designation == "Accountant") ? "selected" : ""; ?>>Accountant</option>
                                    <option value="Supervisor" <?php echo ($Row->designation == "Supervisor") ? "selected" : ""; ?>>Supervisor</option>
                                    <option value="Support Team" <?php echo ($Row->designation == "Support Team") ? "selected" : ""; ?>>Support Team</option>
                                </select>


                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Photo</label>
                            <input type="file" class="form-control" name="photo" value="<?php echo $Row->photo ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Enter Password" value="<?php echo base64_decode($Row->password); ?>" required>

                        </div>
                    </div>
                    <div class="text-center">
                        <input name="submit" type="submit" class="btn btn-primary" value="<?php echo $titl; ?>" />
                        <!-- <button type="reset" class="btn btn-secondary">Reset</button> -->
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
<script>
    $(document).ready(function() {
        $('#Assigned_Areas').select2({
            placeholder: "Select Assigned Areas",
            allowClear: true
        });
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