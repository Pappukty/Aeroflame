<?php ob_start();
include_once './includes/header.php';
include_once './class/fileUploader.php';
error_reporting(1);




$titl = "";
$state_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

if ($state_id > 0) {
    $titl = "Update ";

    // Use prepared statements to prevent SQL injection
    $query = "SELECT * FROM `states` WHERE id = ?";
    $stmt = $DatabaseCo->dbLink->prepare($query);
    $stmt->bind_param("i", $state_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $Row = $result->fetch_object();
    } else {
        $Row = null; // No record found
    }

    $stmt->close();
} else {
    $Row = null; // No state_id provided
}


?>

<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title"><?php echo $titl; ?> Add State</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="state.php"> State Listing</a></li>
                <li class="breadcrumb-item active"><?php echo $titl; ?> Add State </li>
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
         
                            <form method="post" id="state-form" class="needs-validation">
                                <div id="basic-layout" class="pt-30 pl-30 pr-30 pb-30">
                                    <div id="message-container" class="mb-3"></div> <!-- Alert messages -->

                                    <div class="row is-multiline">
                                        <div class="mb-4">
                                            <div class="card-header position-relative">
                                                <h6 class="fs-17 fw-semi-bold mb-0">State</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-4">
                                                    <div class="col-sm-6">
                                                        <!-- start form group -->
                                                        <div class="">
                                                            <label class="required fw-medium mb-2">Country</label>

                                                            <select class="form-control mb-3" name="country_id" id="country_code" required>
                                                                <option disabled>Select Country</option>
                                                                <?php
                                                                // Fetch countries from the database
                                                                $Vselect = "SELECT * FROM countries ORDER BY name";
                                                                $VSQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $Vselect);

                                                                // Loop through each country and mark the selected one
                                                                while ($VRow = mysqli_fetch_object($VSQL_STATEMENT)) {
                                                                    // Combine the country code with the name (e.g., "US - United States")
                                                                    $displayText = "{$VRow->name}";

                                                                    // Check if this is the selected country during edit
                                                                    $selected = ($VRow->id == $Row->id) ? 'selected' : '';
                                                                    echo "<option value='{$VRow->id}' $selected>$displayText</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <!-- end /. form group -->
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="country_name"><b>State Name</b></label>
                                                            <input type="text" name="name" class="form-control" id="state_name"
                                                                placeholder="Enter State name" value="<?php echo $Row->name; ?>" required>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="state_code"><b>State Code</b></label>
                                                            <input type="number" name="state_code" class="form-control" id="state_code"
                                                                placeholder="Enter State code" value="<?php echo $Row->state_code; ?>" required>
                                                        </div>
                                                    </div> -->
                                                    <div class="col-sm-6">
                                                        <label><b>Status</b></label>
                                                        <div class="radio">
                                                            <input id="optionsRadios1" class="status" type="radio" value="active" name="status"
                                                                <?php echo ($Row->status === 'active') ? 'checked' : ''; ?>>
                                                            <label for="optionsRadios1"><b>Active</b></label>

                                                            <input id="optionsRadios2" class="country_status" type="radio" value="inactive" name="status"
                                                                <?php echo ($Row->status === 'inactive') ? 'checked' : ''; ?>>
                                                            <label for="optionsRadios2"><b>Inactive</b></label>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>

                                            <div class="col-sm-12 text-center">
                                                <input type="hidden" name="state_id" id="state_id" value="<?php echo $Row->id  ?? ''; ?>">
                                                <button type="submit" class="btn btn-primary" id="save">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
            </div>
        </div>


    </div>

</div>

<script src="https://cdn.ckeditor.com/4.25.0/standard/ckeditor.js"></script>


<?php
include_once './includes/footer.php';

?>
<script>
    $(document).ready(function() {
        $('#state-form').on('submit', function(e) {
            e.preventDefault(); // Prevent page reload

            let action = $('#state_id').val() ? 'update' : 'insert'; // Determine action
            let formData = {
                action: action,
                country_code: $('#country_code').val().toUpperCase(), // Ensure uppercase
                state_id: $('#state_id').val(), // Only for updates
                state_name: $('#state_name').val().trim(),
                status: $('input[name="status"]:checked').val() || 'inactive' // Default to inactive
            };
            console.log(formData);
            $.ajax({
                url: 'state_process.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    let res = JSON.parse(response);
                    console.log(res);
                    if (res.success) {
                        showMessage('success', res.message);

                        // Redirect after success (adjust URL as needed)
                        setTimeout(() => {
                            window.location.href = 'state.php';
                        }, 2000);
                    } else {
                        showMessage('danger', res.message);
                    }
                },
                error: function(xhr, status, error) {
                    showMessage('danger', 'An error occurred: ' + error);
                }
            });
        });

        // Function to show messages
        function showMessage(type, message) {
            let alertBox = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
            $('#message-container').html(alertBox);

            // Auto-hide alert after 5 seconds
            setTimeout(() => $('.alert').alert('close'), 5000);
        }
    });
</script>