<?php ob_start();
include_once './includes/header.php';
include_once './class/fileUploader.php';
error_reporting(1);



if ($_REQUEST['id'] > 0) {
    $titl = "Update ";
    $select = "SELECT * FROM countries WHERE id ='" . $_REQUEST['id'] . "'"; //echo $select;
    $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);
    $Row = mysqli_fetch_object($SQL_STATEMENT);
} else {
    $titl = "";
}
?>


<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title"><?php echo $titl; ?> Add Country</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="country.php"> Country Listing</a></li>
                <li class="breadcrumb-item active"><?php echo $titl; ?> Add Country </li>
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
                <form method="post" id="country-form" class="needs-validation">
                                <div id="basic-layout" class="pt-30 pl-30 pr-30 pb-30">
                                    <div id="message-container" class="mb-3"></div> <!-- Alert messages -->

                                 
                                        <div class="mb-4">
                                            <div class="card-header position-relative">
                                                <h6 class="fs-17 fw-semi-bold mb-0">Country</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-4">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="country_name"><b>Country Name</b></label>
                                                            <input type="text" name="name" class="form-control" id="country_name"
                                                                placeholder="Enter country name" value="<?php echo $Row->name; ?>" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
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

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="country_code"><b>Country Code</b></label>
                                                            <input type="text" name="iso2" class="form-control" id="iso2"
                                                                placeholder="Enter country code" value="<?php echo $Row->iso2; ?>" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 text-center">
                                                <input type="hidden" name="country_id" id="country_id" value="<?php echo $Row->id  ?? ''; ?>">
                                                <button type="submit" class="btn btn-primary" id="save">Submit</button>
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
        $('#country-form').on('submit', function(e) {
            e.preventDefault(); // Prevent page reload on form submission

            let action = $('#country_id').val() ? 'update' : 'insert'; // Determine action based on country_id

            $.ajax({
                url: 'country_process.php',
                type: 'POST',
                data: {
                    action: action,
                    country_id: $('#country_id').val(), // Optional for 'update' only
                    country_name: $('#country_name').val(),
                    iso2: $('#iso2').val(),
                    status: $('input[name="status"]:checked').val()
                },
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.success) {
                        showMessage('success', res.message); // Show success message

                        // Redirect to another page after 2 seconds (adjust URL as needed)
                        setTimeout(() => {
                            window.location.href = 'country.php'; // Change to your target page
                        }, 2000);
                    } else {
                        showMessage('danger', res.message); // Show error message
                    }
                },
                error: function(xhr, status, error) {
                    showMessage('danger', 'An error occurred: ' + error);
                }
            });
        });

        // Function to show messages (success or error)
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