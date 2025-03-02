<?php
session_start(); // Start the session

include_once './includes/header.php';
error_reporting(1);
// Check if the user is logged in
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $customer_id = $_POST['customer_id'];
    $technician_id = $_POST['technician_id'];
    $status = $_POST['status_process'];

    // Ensure valid input
    if (!empty($customer_id) && !empty($technician_id)) {
        // Update work schedule
        $sql = "UPDATE resolve SET technician_id = ?, status_process = ? WHERE customer_id = ?";
        $stmt = $DatabaseCo->dbLink->prepare($sql);
        $stmt->bind_param("isi", $technician_id, $status, $customer_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Work schedule updated successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error updating work schedule."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Please select a technician."]);
    }
}


?>


<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-8">
            <h3 class="page-title">All Technician Assignment </h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Listing of All Technician Assignment </li>
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
                                <th class="w-5">Sno</th>
                                <th class="w-15">consumer_id</th>
                                <th class="w-15">customer_name</th>
                                <th class="w-25">mobile_number</th>

                                <!-- <th class="w-25">Email</th> -->
                                <th class="w-15">Assigned Areas</th>
                                <th class="w-15">employee_id</th>
                                <th class="w-15">technician_name</th>

                                <!-- <th class="w-15">Type</th> -->
                                <th class="w-25">Status</th>
                                <!-- <?php if ($_SESSION["user_id"] == 1) { ?> -->
                                <th class="w-25">Action</th>
                                <!-- <?php } ?> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $select = "SELECT * FROM `resolve` WHERE index_id != '0' ORDER BY index_id DESC";
                            $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);
                            $num_rows = mysqli_num_rows($SQL_STATEMENT);

                            if ($num_rows != 0) {
                                $i = 1;
                                while ($Row = mysqli_fetch_object($SQL_STATEMENT)) {


                                    $selectedCities = isset($Row->city) ? explode(',', $Row->city) : []; // Convert CSV to array

                                    // Ensure we handle multiple city selections correctly
                                    $cityIds = implode("','", $selectedCities); // Convert array to a string for SQL query
                                    $sql3 = mysqli_query($DatabaseCo->dbLink, "SELECT * FROM cities WHERE id IN ('$cityIds')");
                                    
                                    $cityNames = [];

                                    while ($res3 = mysqli_fetch_object($sql3)) {
                                        $cityNames[] = $res3->name; // Collect city names
                                    }

                                    // Convert city names array into a comma-separated string
                                    $mergedCityNames = implode(', ', $cityNames);
                            ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>

                                        <!-- Technician Image -->
                                        <td>
                                            <?php echo htmlspecialchars($Row->consumer_id); ?>
                                        </td>

                                        <!-- Employee ID -->
                                        <td><?php echo htmlspecialchars($Row->customer_name); ?></td>

                                        <!-- Technician Name -->
                                        <td><?php echo htmlspecialchars($Row->mobile_number); ?></td>

                                        <!-- Contact -->


                                        <!-- Assigned Areas -->
                                        <td>
                                            <?php
                                            $assigned_areas = json_decode($mergedCityNames, true);
                                            echo is_array($assigned_areas) ? implode(', ', $assigned_areas) : htmlspecialchars($mergedCityNames);
                                            ?>
                                        </td>
                                        <td>____</td>
                                        <td>____</td>
                                        <!-- Availability Status Button -->
                                        <td>
                                            <button class="btn 
                    <?php

                                    if ($Row->status_process == 'completed') {
                                        echo 'btn-success'; // Green button for completed
                                    } elseif ($Row->status_process == 'pending') {
                                        echo 'btn-warning text-dark'; // Yellow button for pending
                                    } elseif ($Row->status_process == 'Filled') {
                                        echo 'btn-primary'; // Blue button for filled
                                    } else {
                                        echo 'btn-secondary'; // Default gray button
                                    }
                    ?>">
                                                <?php echo ucfirst(htmlspecialchars($Row->status_process)); ?>
                                            </button>
                                        </td>

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
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                                                        <?php if ($Row->availability_status == 'Active') { ?>
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="updateStatus('<?php echo $Row->id; ?>', 'Inactive')">
                                                                Set as Inactive
                                                            </a>
                                                        <?php } elseif ($Row->availability_status == 'Inactive') { ?>
                                                            <a class="dropdown-item" href="javascript:void(0);" onclick="updateStatus('<?php echo $Row->id; ?>', 'Active')">
                                                                Set as Active
                                                            </a>
                                                        <?php } ?>

                                                        <div class="dropdown-divider"></div> <!-- Adds a separator between status change and profile view -->
                                                        <!-- 
                                                        <a class="dropdown-item" href="technician_profile.php?id=<?php echo $Row->id; ?>">
                                                            View Profile
                                                        </a> -->
                                                        <div class="dropdown-divider"></div>
                                                        <!-- Work Schedule Button -->
                                                        <a class="dropdown-item work-schedule-btn" href="javascript:void(0);"
                                                            data-id="<?php echo htmlspecialchars($Row->city . '&&' . $Row->consumer_id); ?>"
                                                            data-bs-toggle="modal" data-bs-target="#workScheduleModal">
                                                            Work Schedule
                                                        </a>



                                                    </div>
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
            var dataId = $(this).data("id"); // Example format: "1845 && 5678"
            var values = dataId.split("&&");
            var area_id = values[0].trim();
            var customer_id = values[1].trim();

            $("#customer_id").val(customer_id); // Assign customer ID to hidden input
            $("#customer_name").find("option:not(:first)").remove(); // Clear previous options

            $.ajax({
                url: "fetch_technicians.php",
                type: "POST",
                data: {
                    area_id: area_id
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        $.each(response.data, function(index, technician) {
                            $("#customer_name").append(
                                `<option value="${technician.id}">${technician.name}</option>`
                            );
                        });
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function(xhr) {
                    console.log("XHR Error:", xhr.responseText);
                    alert("Error fetching technicians.");
                }
            });
        });

        // Handle form submission
        $("#workScheduleForm").submit(function(e) {
            e.preventDefault();

            var technician_id = $("#customer_name").val();
            var customer_id = $("#customer_id").val();

            if (!technician_id) {
                alert("Please select a technician.");
                return;
            }

            $.ajax({
                url: "technician_assign.php",
                type: "POST",
                data: {
                    customer_id: customer_id,
                    technician_id: technician_id,
                    status_process: "In-Progress"
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        alert(response.message);
                        $("#workScheduleModal").modal("hide"); // Close modal
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function(xhr) {
                    console.log("XHR Error:", xhr.responseText);
                    alert("Error assigning technician.");
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