<?php ob_start();
include_once './includes/header.php';
if ($_REQUEST['state_id']) {
    $state = $_REQUEST['state_id'];
} else {
    $state = "38";
}
error_reporting(1);
if (isset($_POST['delete_now']) && !empty($_POST['del_c'])) {
    $del_id = intval($_POST['del_c']); // Sanitize input to avoid SQL injection

    $query = "DELETE FROM `cities` WHERE `id` = ?";
    $stmt = $DatabaseCo->dbLink->prepare($query);
    $stmt->bind_param("i", $del_id);

    if ($stmt->execute()) {
        echo "<script>
              
                window.location.href = 'city.php';
              </script>";
    } else {
        echo "<script>alert('Error deleting record: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $DatabaseCo->dbLink->close();
}
// Check if delete ID is available in the request

?>



<style>
    .form-select {
        width: 60% !important;
    }
</style>



<div class="page-header">
    <div class="row">
        <div class="col-sm-8">
            <h3 class="page-title">All Country </h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Listing of All State </li>
            </ul>
        </div>
        <div class="col-md-4" align="right">
            <a class="btn btn-primary btn-rounded" href="city_add.php">
                <i class="mdi mdi-settings-outline mr-1"></i> Add New City
            </a>
        </div>
        <div class="dropdown">

            <div class="form-group">
                <label for="countryDropdown" class="fw-semi-bold">Select State</label>
                <select class="form-control" id="countryDropdown">
                    <option value="">Select state</option>
                    <?php
                    $select = "SELECT * FROM `states` ORDER BY `id` ASC";
                    $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);
                    if (mysqli_num_rows($SQL_STATEMENT) != 0) {
                        while ($Row = mysqli_fetch_object($SQL_STATEMENT)) { ?>
                            <option value="<?php echo htmlspecialchars($Row->id); ?>">
                                <?php echo $Row->name; ?>
                            </option>
                    <?php }
                    } ?>
                </select>
            </div>
        </div>
    </div>
</div>
<!-- /Page Header -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th class="w-5">Sno</th>
                                <!-- <th class="w-5">City Id</th> -->

                                <th class="w-15">State Code</th>

                                <th class="w-25">City Name</th>
                                <th class="w-20">Status</th>
                                <th class="w-5">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  // Example: State ID to filter cities, change as needed

                            $select = "SELECT * FROM `cities` 
                           WHERE id != '0' AND state_id = '$state' 
                              ORDER BY id DESC";

                            $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);
                            $num_rows = mysqli_num_rows($SQL_STATEMENT);
                            if ($num_rows != 0) {
                                $i = 1;
                                while ($Row = mysqli_fetch_object($SQL_STATEMENT)) {
                                    $sql3 = mysqli_query($DatabaseCo->dbLink, "SELECT name FROM `states` WHERE id='" . $Row->state_id . "'");
                                    $res3 = mysqli_fetch_object($sql3);
                            ?>
                                    <tr>
                                        <td><?php echo $i;
                                            $i++; ?></td>

                                        <!-- <td><?php echo $Row->city_id; ?></td> -->

                                        <td><?php echo $Row->state_id; ?></td>
                                        <td><?php echo $Row->name; ?></td>
                                        <td><?php echo $Row->status; ?></td>

                                        <td>
                                        <div class="d-flex align-items-center">
                                                <!-- Edit Button -->
                                                <div class="mr-3">
                                                    <a class="btn btn-sm p-2 btn-primary text-white edit-board"
                                                        href="city_add.php?id=<?php echo $Row->id; ?>">
                                                        <i class="fa fa-pencil" style="font-size: 15px;"></i>
                                                    </a>
                                                </div>

                                                <!-- Delete Button -->
                                                <div class="mr-3">
                                                    <a class="btn btn-sm p-2 btn-danger delete-board"
                                                        data-modal="delete-board-alert"
                                                        data-toggle="modal"
                                                        data-target="#delete-board-alert"
                                                        href="#0"
                                                        data-id="<?php echo $Row->id; ?>">
                                                        <i class="fa fa-trash" style="font-size: 15px;"></i>
                                                    </a>
                                                </div>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="9">
                                        <div align="center"><strong>No Records!</strong></div>
                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>


    </div>

</div>
<!-- end row -->
<div id="delete-board-alert" class="modal fade alert-box">
    <form action="process.php" method="post" name="delete_form" id="delete_form">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="header-title">Delete City </h5>
                    <button type="btn" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" align="center">
                    <h5 class="text-center">Delete City Details ?</h5>
                    <p>Are you sure you want to delete this City details? All data and attached deals will be lost.</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="form_action" value="DeleteCity" />
                    <input type="hidden" name="delid" id="delid" value="" />
                    <button class="btn raised bg-primary text-white ml-2 mt-2" data-dismiss="modal">Cancel</button>
                    <button name="delete_now" type="submit" class="btn mt-2 btn-dash btn-danger raised has-icon" id="modalDelete" value="Delete">Delete</button>
                </div>
            </div>
        </div>
    </form>
</div>


<?php
include_once './includes/footer.php';
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript">
    // $("#add_form").submit(function(event) {
    //     event.preventDefault();
    //     var post_url = $(this).attr("action");
    //     var request_method = $(this).attr("method");
    //     var form_data = $("#add_form").serialize();
    //     //alert(form_data);
    //     $.ajax({
    //         url: post_url,
    //         type: request_method,
    //         dataType: "text",
    //         data: form_data
    //     }).done(function(response) {
    //         console.log(response);
    //         //window.location.reload();
    //     });
    // });
    $("#add_form").submit(function(event) {
        event.preventDefault();
        var post_url = $(this).attr("action");
        var request_method = $(this).attr("method");
        var form = $('#add_form')[0];
        var data = new FormData(form);
        //alert(data);
        $.ajax({
            type: "POST",
            url: "packages-process.php",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function(data) {
                var newPatient = $.trim(data);
                //console.log(newPatient);
                // $("#new_patient_id").val(newPatient);
                // $("#hideDate").hide();             
                // $("#hideTrouble").hide();              
                // $('#patient_details').modal('hide');
                // $('#appointment_add').modal('show');
                //   window.location.href="billing.php?type=1";
                window.location.reload();
            },
            error: function(event) {
                console.log("ERROR : ", event);
                window.location.reload();
            }
        });
    })
    $('.drop-edit-board').click(function() {
        var id = $(this).data('id');
        $("#pget_id").val(id);
        $("#vcategory").hide();
        var dataString = 'TourAddedit=' + id;
        $("#hidden_id").val(id);
        $.ajax({
            url: "packages-process.php",
            type: "POST",
            dataType: "text",
            data: dataString
        }).done(function(html) { //alert(html);
            var arr = html.split("|");
            $("#package_name").val(arr[0]);
            $("#package_price").val(arr[1]);
            $("#number_of_nights").val(arr[2]);
            $("#others_details").val(arr[3]);
            $("#number_of_days").val(arr[4]);

        });

    });
</script>
<script type="text/javascript">
    $('.delete-board').click(function() {
        var id = $(this).data('id');
        $("#delid").val(id);
    });
    $("#delete_form").submit(function(event) {
        event.preventDefault();
        var post_url = $(this).attr("action");
        var request_method = $(this).attr("method");
        var form_data = $("#delete_form").serialize();
        //alert(form_data);
        $.ajax({
            url: post_url,
            type: request_method,
            dataType: "text",
            data: form_data
        }).done(function(response) {
            Lobibox.notify('error', {
                pauseDelayOnHover: true,
                continueDelayOnInactiveTab: false,
                icon: 'fa fa-times-circle',
                position: 'right bottom',
                showClass: 'lightSpeedIn',
                hideClass: 'lightSpeedOut',
                size: 'mini',
                msg: 'Details deleted successfully.'
            });
            window.location.reload();
        });
    });
</script>
<script>
    function updateStatus(id, newStatus) {
        console.log("Updating ID:", id, "New Status:", newStatus);

        $.ajax({
            url: 'update.php',
            type: 'POST',
            data: {
                status_id: id,
                availability_status: newStatus
            },
            dataType: 'json',
            success: function(response) {
                console.log("Server Response:", response);

                // Toastr options
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: "fadeIn",
                    hideMethod: "fadeOut",
                    timeOut: 3000,
                    positionClass: "toast-top-right"
                };

                if (response.status === 'success') {
                    toastr.success(response.message || "Status updated successfully!");
                    setTimeout(() => location.reload(), 2500); // Refresh after success
                } else {
                    toastr.error(response.message || "Something went wrong, please try again!");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText, status, error);

                toastr.error("Request failed! Check the console for details.");
            }
        });
    }
</script>





<script>
    $(document).ready(function() {
        $('#print_button').click(function(event) {
            event.preventDefault();

            let selectedProductIds = [];
            $('.product-checkbox:checked').each(function() {
                selectedProductIds.push($(this).val());
            });

            let productIdsString = selectedProductIds.join(',');

            $.ajax({
                url: 'print_process.php',
                type: 'POST',
                data: {
                    product_ids: productIdsString
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Redirect to the new page with the product IDs as a query parameter
                        window.location.href = 'print_layout.php?product_ids=' + encodeURIComponent(productIdsString);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while processing your request.');
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#example').DataTable()
    });
</script>
<script>
    function showWorkSchedule(StaffId) {
        console.log("Staff ID:", StaffId); // Debugging

        // Dummy Tamil Nadu customer list
        var dummyCustomers = [{
                id: 1,
                name: "Arun Kumar"
            },
            {
                id: 2,
                name: "Lakshmi Narayanan"
            },
            {
                id: 3,
                name: "Meena Ramesh"
            },
            {
                id: 4,
                name: "Suresh Babu"
            },
            {
                id: 5,
                name: "Priya Chandran"
            },
            {
                id: 6,
                name: "Venkatesh Iyer"
            }
        ];

        // Generate options for the select dropdown
        var options = '<option value="">Select a Customer</option>';
        dummyCustomers.forEach(function(customer) {
            options += `<option value="${customer.id}">${customer.name}</option>`;
        });

        // Populate the dropdown and show modal
        $("#customerList").html(options);
        $("#workScheduleModal").modal("show");
    }
</script>

<script>
    $(document).ready(function() {
        $('#print_button').click(function(event) {
            event.preventDefault();

            let selectedProductIds = [];
            $('.product-checkbox:checked').each(function() {
                selectedProductIds.push($(this).val());
            });

            let productIdsString = selectedProductIds.join(',');

            $.ajax({
                url: 'print_process.php',
                type: 'POST',
                data: {
                    product_ids: productIdsString
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Redirect to the new page with the product IDs as a query parameter
                        window.location.href = 'print_layout.php?product_ids=' + encodeURIComponent(productIdsString);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while processing your request.');
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#example').DataTable()
    });
</script>
<script>
    $(document).ready(function() {
        var table = $('#example2').DataTable({
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf', 'print']
        });

        table.buttons().container()
            .appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>
<script>
    $("#countryDropdown").on('change', function() {
        const selectedValue = this.value;
        console.log(selectedValue);
        if (selectedValue) {
            // Use correct string interpolation for the URL
            window.location.href = `city.php?state_id=${selectedValue}`;
        }
    });
</script>
<script>
    $(document).on('click', '.delete-board', function() {
        var countryId = $(this).data('id'); // Get the data-id from the button
        $('#delid').val(countryId); // Assign it to the hidden input field
    });
</script>