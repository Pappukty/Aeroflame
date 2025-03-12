<?php
include_once './includes/header.php';
if ($_REQUEST['country_id']) {
    $country = $_REQUEST['country_id'];
} else {
    $country = "101";
}
if (isset($_POST['delete_now']) && !empty($_POST['del_c'])) {
    $del_id = intval($_POST['del_c']); // Sanitize input to avoid SQL injection

    $query = "DELETE FROM `countries` WHERE `id` = ?";
    $stmt = $DatabaseCo->dbLink->prepare($query);
    $stmt->bind_param("i", $del_id);

    if ($stmt->execute()) {
        echo "<script>
              
                window.location.href = 'country.php';
              </script>";
    } else {
        echo "<script>alert('Error deleting record: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $DatabaseCo->dbLink->close();
}
?>
<style>
    #delete_form .modal-dialog .modal-content .modal-body p {
        color: white !important;
    }
</style>
<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-8">
            <h3 class="page-title">All State </h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Listing of All State </li>
            </ul>
        </div>
        <div class="col-md-4" align="right">
            <a class="btn btn-primary btn-rounded" href="state_add.php">
                <i class="mdi mdi-settings-outline mr-1"></i> Add New State
            </a>
        </div>
        <div class="dropdown">

            <div class="form-group">
                <label for="countryDropdown" class="fw-semi-bold">Select Country</label>
                <select class="form-control" id="countryDropdown">
                    <option value="">Select Country</option>
                    <?php
                    $select = "SELECT * FROM `countries` ORDER BY `id` ASC";
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
                                <th class="w-15">Name</th>
                                <th class="w-15">Country Code</th>
                                <th class="w-25">status</th>


                                <th class="w-25">Action</th>
                                <!-- <th class="w-25">Status</th> -->
                                <!-- <?php if ($_SESSION["user_id"] == 1) { ?> -->
                               
                                <!-- <?php } ?> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php $select = "SELECT * FROM `states` WHERE id !='0' AND country_id='$country' ORDER BY id  DESC";
                            $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);
                            $num_rows = mysqli_num_rows($SQL_STATEMENT);

                            if ($num_rows != 0) {
                                $i = 1;
                                while ($Row = mysqli_fetch_object($SQL_STATEMENT)) {
                            ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>

                                        <!-- Staff Image -->
                                        <td>
                                            <?php echo htmlspecialchars($Row->name); ?>
                                        </td>

                                        <!-- Employee ID -->
                                        <td><?php echo htmlspecialchars($Row->country_id); ?></td>

                                        <!-- Staff Name -->
                                        <td><?php echo htmlspecialchars($Row->status); ?></td>

                                        <!-- Contact -->


                                        <!-- Availability Status Button -->


                                        <!-- Action Buttons -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <!-- Edit Button -->
                                                <div class="mr-3">
                                                    <a class="btn btn-sm p-2 btn-primary text-white edit-board"
                                                        href="state_add.php?id=<?php echo $Row->id; ?>">
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



                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else { ?>
                                <tr>
                                    <td colspan="9" class="text-center"><strong>No Records Found!</strong></td>
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
                    <h5 class="header-title">Delete State </h5>
                    <button type="btn" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" align="center">
                    <h5 class="text-center">Delete State Details ?</h5>
                    <p>Are you sure you want to delete this State details? All data and attached deals will be lost.</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="form_action" value="DeleteState" />
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
            window.location.href = `state.php?country_id=${selectedValue}`;
        }
    });
</script>