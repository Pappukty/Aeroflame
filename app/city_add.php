<?php ob_start();
include_once './includes/header.php';
include_once './class/fileUploader.php';
error_reporting(1);



if ($_REQUEST['id'] > 0) {
    $titl = "Update ";
    $select = "SELECT * FROM `cities` WHERE id ='" . $_REQUEST['id'] . "'"; //echo $select;
    $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);
    $Row = mysqli_fetch_object($SQL_STATEMENT);
} else {
    $titl = "";
}
?>

<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title"><?php echo $titl; ?> Add City</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="city.php"> City Listing</a></li>
                <li class="breadcrumb-item active"><?php echo $titl; ?> Add City </li>
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
                    <h6 class="fs-17 fw-semi-bold mb-0">City</h6>
                </div>
                <div class="card-body">
                    <div class="row g-4">

                        <div class="col-sm-6">
                            <div class="">
                                <label class="required fw-medium mb-2">State</label>
                                <select class="form-control mb-3" name="state_id" id="state_id" required>
                                    <option value="">Select State</option>
                                    <?php
                                    // Fetch states from the database
                                    $Vselect = "SELECT * FROM `states` ORDER BY name";
                                    $VSQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $Vselect);

                                    while ($VRow = mysqli_fetch_object($VSQL_STATEMENT)) {
                                        $displayText = htmlspecialchars($VRow->name);
                                        $selected = (isset($Row->state_id) && $VRow->id == $Row->state_id) ? 'selected' : '';
                                        echo "<option value='{$VRow->id}' $selected>$displayText</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="city_name"><b>City Name</b></label>
                                <input type="text" name="name" class="form-control" id="city_name"
                                    placeholder="Enter City Name" value="<?php echo htmlspecialchars($Row->name ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label><b>Status</b></label>
                            <div class="radio">
                                <input id="active" class="status" type="radio" value="active" name="status"
                                    <?php echo ($Row->status === 'active') ? 'checked' : ''; ?> required>
                                <label for="active"><b>Active</b></label>

                                <input id="inactive" class="status" type="radio" value="inactive" name="status"
                                    <?php echo ($Row->status === 'inactive') ? 'checked' : ''; ?> required>
                                <label for="inactive"><b>Inactive</b></label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-sm-12 text-center">
                    <input type="hidden" name="city_id" id="city_id" value="<?php echo $Row->id ?? ''; ?>">
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

        let action = $('#city_id').val() ? 'update' : 'insert'; // Determine action

        let formData = {
            action: action,
            city_id: $('#city_id').val(),
            city_name: $('#city_name').val(),
            state_id: $('#state_id').val(),
            status: $('input[name="status"]:checked').val()
        };

        $.ajax({
            url: 'city_process.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showMessage('success', response.message);
                    setTimeout(() => { window.location.href = 'city.php'; }, 2000);
                } else {
                    showMessage('danger', response.message);
                }
            },
            error: function(xhr, status, error) {
                showMessage('danger', 'An error occurred: ' + error);
            }
        });
    });

    function showMessage(type, message) {
        let alertBox = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
        $('#message-container').html(alertBox);
        setTimeout(() => $('.alert').alert('close'), 5000);
    }
});
</script>