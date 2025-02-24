<?php
include_once './includes/header.php';
include_once './class/fileUploader.php';
error_reporting(1);

if (isset($_REQUEST['submit'])) {
    $technician_name = $xssClean->clean_input($_REQUEST['technician_name']);
    $email = $xssClean->clean_input($_REQUEST['email']);
    $skills = $xssClean->clean_input($_REQUEST['skills']);
    $contact = $xssClean->clean_input($_REQUEST['contact']);
    $availability_status = $xssClean->clean_input($_REQUEST['availability_status']);
    $work_schedule = $xssClean->clean_input($_REQUEST['work_schedule']);
    $performance_metrics = $xssClean->clean_input($_REQUEST['performance_metrics']);
    $dob = $xssClean->clean_input($_REQUEST['dob']);
    $address = $xssClean->clean_input($_REQUEST['address']);
    $experience = $xssClean->clean_input($_REQUEST['experience']);
    $log_date = date("Y-m-d H:i:s");

    // Handle assigned areas as an array
    $assigned_areas = isset($_REQUEST['assigned_areas']) ? $_REQUEST['assigned_areas'] : [];
    $assigned_areas_str = implode(',', array_map([$xssClean, 'clean_input'], $assigned_areas));

    // Check if it's an update operation
    if (!empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
        $d_id = intval($_REQUEST['id']);

        // Fetch the existing employee_id
        $stmt = $DatabaseCo->dbLink->prepare("SELECT employee_id FROM technician WHERE id=?");
        $stmt->bind_param("i", $d_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $employee_id = $row['employee_id']; // Keep the existing employee ID

        // Update existing technician
        $stmt = $DatabaseCo->dbLink->prepare("UPDATE technician SET technician_name=?,email=?,dob=?, skills=?, contact=?, assigned_areas=?, availability_status=?, work_schedule=?, performance_metrics=?,address=?,experience=? WHERE id=?");
        $stmt->bind_param("sssssssssssi", $technician_name, $email, $dob, $skills, $contact, $assigned_areas_str, $availability_status, $work_schedule, $performance_metrics, $address, $experience, $d_id);
        $stmt->execute();
    } else {
        // Generate a new unique Employee ID
        $stmt = $DatabaseCo->dbLink->prepare("SELECT MAX(id) AS max_id FROM technician");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $next_id = $row['max_id'] + 1;
        $employee_id = "TECH" . str_pad($next_id, 3, '0', STR_PAD_LEFT); // Format: TECH001, TECH002, etc.

        // Insert new technician
        $stmt = $DatabaseCo->dbLink->prepare("INSERT INTO technician (employee_id, technician_name, email,dob, skills, contact, assigned_areas, availability_status, work_schedule, performance_metrics, log_date,address,experience) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssss", $employee_id, $technician_name, $email, $dob, $skills, $contact, $assigned_areas_str, $availability_status, $work_schedule, $performance_metrics, $log_date, $address, $experience);
        $stmt->execute();
        $d_id = $stmt->insert_id;
    }

    // Handle Image Upload
    $uploadimage = new ImageUploader($DatabaseCo);

    if (isset($_FILES['technician_image']) && is_uploaded_file($_FILES['technician_image']['tmp_name'])) {
        $photos = $uploadimage->upload($_FILES['technician_image'], "technician/technician_image");
        if ($photos) {
            $stmt = $DatabaseCo->dbLink->prepare("UPDATE technician SET technician_image=? WHERE id=?");
            $stmt->bind_param("si", $photos, $d_id);
            $stmt->execute();
        }
    }

    if (isset($_FILES['certifications']) && is_uploaded_file($_FILES['certifications']['tmp_name'])) {
        $certifications = $uploadimage->upload($_FILES['certifications'], "technician/certifications");
        if ($certifications) {
            $stmt = $DatabaseCo->dbLink->prepare("UPDATE technician SET certifications=? WHERE id=?");
            $stmt->bind_param("si", $certifications, $d_id);
            $stmt->execute();
        }
    }

    header("Location: technician.php");
    exit();
}


if (!empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
    $titl = "Update";
    $stmt = $DatabaseCo->dbLink->prepare("SELECT * FROM technician WHERE id=?");
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
.select2-selection .select2-selection--multiple{
    color: black !important;
}
</style>
<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title"><?php echo $titl; ?> Add Technician </h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="technician_add.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="technician .php"> Technician Listing</a></li>
                <li class="breadcrumb-item active"><?php echo $titl; ?> Add Technician </li>
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

                    <!-- Row 1: Name & Employee ID -->
                    <div class="row mb-3">
                        <div class="col-md-6 col-12 mb-3">
                            <label class="form-label">Technician Image</label>
                            <input type="file" class="form-control" name="technician_image" value="<?php echo $Row->technician_image; ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="technician_name" class="form-label">Technician Name</label>
                            <input type="text" class="form-control" id="technician_name" name="technician_name" value="<?php echo $Row->technician_name; ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="dob" class="form-label">Technician Name</label>
                            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $Row->dob; ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $Row->email; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?php echo $Row->address; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="experience" class="form-label">Experience</label>
                            <input type="text" class="form-control" id="experience" name="experience" value="<?php echo $Row->experience; ?>" required>
                        </div>
                        <!-- Row 2: Certifications & Skills -->
                        <div class="col-md-6 col-12 mb-3">
                            <label class="form-label">Certifications</label>
                            <input type="file" class="form-control" name="certifications" value="<?php echo $Row->certifications; ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="skills" class="form-label">Skills</label>
                            <input type="text" class="form-control" id="skills" name="skills" value="<?php echo $Row->skills; ?>">
                        </div>


                        <!-- Row 3: Contact & Assigned Areas -->

                        <div class="col-md-6">
                            <label for="contact" class="form-label">Contact</label>
                            <input type="tel" class="form-control" maxlength="10" id="contact" name="contact" value="<?php echo $Row->contact; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="Assigned_Areas" class="form-label fw-bold">Assigned Areas</label>
                            <select class="form-control" id="Assigned_Areas" name="assigned_areas[]" multiple style="color: black;">
    <?php
    // Simulating edit mode: Fetch assigned areas from DB
    $selected_areas = isset($Row->assigned_areas) ? explode(',', $Row->assigned_areas) : [];

    // Define available areas
    $areas = ["Chennai", "Coimbatore", "Madurai", "Tiruchirappalli", "Salem", "Erode"];

    foreach ($areas as $area) {
        $selected = in_array($area, $selected_areas) ? "selected" : "";
        echo "<option value=\"$area\" $selected style='color: black;'>$area</option>";
    }
    ?>
</select>

                        </div>



                        <!-- Row 4: Availability Status & Work Schedule -->

                        <div class="col-md-6">
                            <label for="availability_status" class="form-label">Availability Status</label>
                            <select class="form-control" id="availability_status" name="availability_status">
                                <option value="Active" <?php echo (isset($Row->availability_status) && $Row->availability_status == "Active") ? "selected" : ""; ?>>Active</option>
                                <option value="Inactive" <?php echo (isset($Row->availability_status) && $Row->availability_status == "Inactive") ? "selected" : ""; ?>>Inactive</option>
                            </select>
                        </div>

                        <!-- <div class="col-md-6">
                            <label for="work_schedule" class="form-label">Work Schedule</label>
                            <input type="text" class="form-control" id="work_schedule" name="work_schedule" value="<?php echo $Row->work_schedule; ?>">
                        </div> -->


                        <!-- Row 5: Performance Metrics -->
                        <div class="mb-3 col-md-12">
                            <label for="performance_metrics" class="form-label">Performance Metrics</label>
                            <textarea
                                class="form-control"
                                id="performance_metrics"
                                name="performance_metrics"><?php echo $Row->performance_metrics; ?></textarea>

                        </div>

                        <!-- Submit & Cancel Buttons -->
                        <div class="text-center">
                            <input name="submit" type="submit" class="btn btn-primary" value="<?php echo $titl; ?>" />
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