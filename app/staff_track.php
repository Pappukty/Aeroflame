<?php
session_start(); // Start the session

include_once './includes/header.php';
error_reporting(1);
// Check if the user is logged in

?>


<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-8">
            <h3 class="page-title">All KPIs to Track </h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Listing of All KPIs to Track </li>
            </ul>
        </div>
        <!-- <div class="col-md-4" align="right">
            <a class="btn btn-primary btn-rounded" href="technician_add.php">
                <i class="mdi mdi-settings-outline mr-1"></i> Add New Technician
            </a>
        </div> -->
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
                                <th>ID</th>
                                <th>Staff ID</th>
                                <th>Name</th>
                                <th>Assigned Technicians</th>
                                <th>Active Tickets</th>
                                <th>Resolved Tickets</th>
                                <th>Avg. Resolution Time</th>
                                <!-- <th></th> -->
                                <th>Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $select = "SELECT * FROM `technician_assignment` WHERE id != '0' ORDER BY id DESC";
                            $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);
                            $num_rows = mysqli_num_rows($SQL_STATEMENT);

                            if ($num_rows != 0) {
                                $i = 1;
                                while ($Row = mysqli_fetch_object($SQL_STATEMENT)) {


                                    $selectedCities = isset($Row->city) ? explode(',', $Row->city) : []; // Convert CSV to array

                                    // Ensure we handle multiple city selections correctly
                                    $cityIds = implode("','", $selectedCities); // Convert array to a string for SQL query
                                    $sql3 = mysqli_query($DatabaseCo->dbLink, "SELECT * FROM cities WHERE id IN ('$cityIds')");
                                    // Query to count the number of matching technicians
                                    $sql4 = mysqli_query($DatabaseCo->dbLink, "SELECT COUNT(*) as total FROM `technician` WHERE employee_id='" . $Row->technican_id . "'");
                                    $res4 = mysqli_fetch_object($sql4);

                                    // Get the count result
                                    $totalTechnicians = $res4->total;
                                    $sql = mysqli_query($DatabaseCo->dbLink, "SELECT COUNT(*) as total FROM `technician_assignment` WHERE status_process = 'In Progress' AND staff_id = '$Row->staff_id'");
                                    $res = mysqli_fetch_object($sql);

                                    $cityNames = [];

                                    while ($res3 = mysqli_fetch_object($sql3)) {
                                        $cityNames[] = $res3->name; // Collect city names
                                    }

                                    // Convert city names array into a comma-separated string
                                    $mergedCityNames = implode(', ', $cityNames);
                            ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($Row->staff_id); ?>
                                        </td>
                                        <!-- Technician Image -->
                                        <td>
                                            <?php echo htmlspecialchars($res5->username); ?>
                                        </td>

                                        <!-- Employee ID -->
                                        <td><?php echo htmlspecialchars($totalTechnicians); ?></td>

                                        <!-- Technician Name -->
                                        <td><?php echo htmlspecialchars($res->total); ?></td>
                                        <td><?php echo htmlspecialchars($Row->customer_address); ?></td>

                                        <!-- Contact -->


                                        <!-- Assigned Areas -->
                                        <!-- <td>
                                            <?php
                                            $assigned_areas = json_decode($mergedCityNames, true);
                                            echo is_array($assigned_areas) ? implode(', ', $assigned_areas) : htmlspecialchars($mergedCityNames);
                                            ?>
                                        </td> -->
                                        <td>2.5 hrs</td>
                                        <!-- <td>4.5/5 ⭐</td> -->

                                        <!-- Availability Status Button -->


                                        <!-- Action Buttons -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <!-- Edit Button -->
                                                <!-- <div class="mr-3">
                                                    <a class="btn btn-sm p-2 btn-primary text-white edit-board"
                                                        href="technician_add.php?id=<?php echo $Row->index_id; ?>">
                                                        <i class="fa fa-pencil" style="font-size: 15px;"></i>
                                                    </a>
                                                </div> -->

                                                <!-- Delete Button -->
                                                <!-- <div class="mr-3">
                                                    <a class="btn btn-sm p-2 btn-danger delete-board"
                                                        data-modal="delete-board-alert"
                                                        data-toggle="modal"
                                                        data-target="#delete-board-alert"
                                                        href="#0"
                                                        data-id="<?php echo $Row->index_id; ?>">
                                                        <i class="fa fa-trash" style="font-size: 15px;"></i>
                                                    </a>
                                                </div> -->

                                                <!-- Status Toggle Dropdown -->
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-rounded dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="mdi mdi-tune"></i>
                                                    </button>

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
                    <h5 class="header-title">Delete Technician </h5>
                    <button type="btn" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" align="center">
                    <h5 class="text-center">Delete Technician Details ?</h5>
                    <p>Are you sure you want to delete this Technician details? All data and attached deals will be lost.</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="form_action" value="DeleteTechnician" />
                    <input type="hidden" name="delid" id="delid" value="" />
                    <button class="btn raised bg-primary text-white ml-2 mt-2" data-dismiss="modal">Cancel</button>
                    <button name="delete_now" type="submit" class="btn mt-2 btn-dash btn-danger raised has-icon" id="modalDelete" value="Delete">Delete</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal fade" id="workScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="workScheduleForm" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Technician's Work Schedule</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="customer_name">Assigned Customers:</label>
                    <select class="form-control" name="technician_id" id="customer_name" required>
                        <option value="" disabled selected>Select Technician</option>
                    </select>
                    <input type="hidden" name="customer_id" id="customer_id">
                    <input type="hidden" name="status_process" value="In-Progress">
                    <button type="submit" class="btn btn-primary mt-3">Assign</button>
                </div>
            </form>

        </div>
    </div>
</div>
</div>


<?php
include_once './includes/footer.php';
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $(".work-schedule-btn").click(function() {
            var dataId = $(this).data("id"); // Example format: "3659,3683 && 5678"
            var values = dataId.split("&&");
            var area_id = values[0].trim(); // "3659,3683"
            var customer_id = values[1].trim();
            console.log(area_id);
            $("#customer_id").val(customer_id);
            $("#customer_name").find("option:not(:first)").remove();

            $.ajax({
                url: "fetch_technicians.php",
                type: "POST",
                data: {
                    area_id: area_id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        let mergedKey = Object.keys(response.data)[0]; // Get first key (e.g., "3659")
                        let technicians = response.data[mergedKey] || [];

                        if (technicians.length > 0) {
                            $.each(technicians, function(index, technician) {
                                $("#customer_name").append(
                                    `<option value="${technician.employee_id}">${technician.name}</option>`
                                );
                            });
                        } else {
                            toastr.warning("No technicians found for the selected area.");
                        }
                    } else {
                        toastr.error(response.message || "Failed to fetch technicians.");
                    }
                },
                error: function(xhr) {
                    console.error("XHR Error:", xhr.responseText);
                    toastr.error("An error occurred while fetching technicians. Please try again.");
                }
            });
        });



        // Handle form submission
        $("#workScheduleForm").submit(function(e) {
            e.preventDefault();

            var technician_id = $("#customer_name").val();
            var customer_id = $("#customer_id").val();

            if (!technician_id) {
                toastr.warning("Please select a technician.");
                return;
            }

            $.ajax({
                url: "assign_update.php",
                type: "POST",
                data: {
                    customer_id: customer_id,
                    technician_id: technician_id,
                    status_process: "In-Progress"
                },
                dataType: "json",
                success: function(response) {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut",
                        timeOut: 3000,
                        positionClass: "toast-top-right"
                    };

                    if (response.status === "success") {
                        toastr.success(response.message || "Work schedule updated successfully!");
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                        $("#workScheduleModal").modal("hide"); // Close modal
                    } else {
                        toastr.error(response.message || "Something went wrong, please try again.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", xhr.responseText, status, error);
                    toastr.error("An error occurred while assigning the technician. Please try again.");
                }
            });
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
                    setTimeout(() => location.reload(), 1500); // Refresh after success
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
    function showWorkSchedule(technicianId) {
        console.log("Technician ID:", technicianId); // Debugging

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