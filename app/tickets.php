<?php
include_once './includes/header.php';
include "phpqrcode/qrlib.php";
if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Debug: Check if data is received correctly
    error_log("ID: " . $id . " Status: " . $status);

    // SQL query to update the status
    $query = "UPDATE resolve SET status_process = ? WHERE index_id = ?";

    // Prepare and execute the query
    if ($stmt = $DatabaseCo->dbLink->prepare($query)) {
        $stmt->bind_param("si", $status, $id);  // "si" means string for status, integer for id

        // Debug: Check if the statement executed successfully
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Ticket marked as completed!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update ticket."]);
        }
        $stmt->close();
    } else {
        error_log("Statement Preparation Failed: " . $DatabaseCo->dbLink->error); // Log any issues with preparing the statement
        echo 'error';  // Respond with error if the query preparation fails
    }
} else {
    error_log("Missing POST data: id or status not set.");
    echo 'error';  // Respond with error if ID or status is not set
}


// qr code
$PNG_TEMP_DIR = 'temp/';
if (!file_exists($PNG_TEMP_DIR)) {
    mkdir($PNG_TEMP_DIR, 0777, true);
}

$filename = $PNG_TEMP_DIR . 'test.png';

// Ensure the required data is set
if (isset($_POST["btnsubmit"])) {
    // Retrieve form values properly
    $city = isset($_POST['city']) ? $_POST['city'] : '';
    $index_id = isset($_POST['index_id']) ? $_POST['index_id'] : '';
    $consumer_number = isset($_POST['consumer_number']) ? $_POST['consumer_number'] : '';
    $customer_name = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';
    $sr_number = isset($_POST['sr_number']) ? $_POST['sr_number'] : '';

    // Create QR code content
    $codeString = "City: $city\n";
    $codeString .= "Index ID: $index_id\n";
    $codeString .= "Consumer Number: $consumer_number\n";
    $codeString .= "Customer Name: $customer_name\n";
    $codeString .= "SR Number: $sr_number";

    // Unique filename based on content
    $filename = $PNG_TEMP_DIR . 'qr_' . md5($codeString) . '.png';

    // Generate QR code
    QRcode::png($codeString, $filename);

    // Display QR code
    echo '<img src="' . $filename . '" alt="User QR Code"/><hr/>';
}
?>



<style>
    #delete_form .modal-dialog .modal-content .modal-body p {
        color: white !important;
    }
</style>
<style>
    .modal-body {
    display: flex;
    flex-direction: column;
    align-items: center;
}
#qrCodeContainer {
    background: white;
    border: 2px solid #ddd;
    padding: 15px;
    text-align: center;
}
#qrCodeImage {
    width: 200px;
    height: 200px;
}

</style>
<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-8">
            <h3 class="page-title">All Tickets </h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Listing of All Tickets </li>
            </ul>
        </div>
        <div class="col-md-4" align="right">
            <a class="btn btn-primary btn-rounded" href="ticket_add.php">
                <i class="mdi mdi-settings-outline mr-1"></i> Add New Tickets
            </a>
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
                                <th class="w-15">Customer</th>
                                <th class="w-25">Name</th>
                                <th class="w-20">Number</th>
                                <th class="w-25">Email</th>
                                <th class="w-15">SR Number</th>
                                <!-- <th class="w-15">Type</th> -->
                                <th class="w-25">Status</th>
                                <th class="w-25">Action</th>
                                <!-- <?php if ($_SESSION["user_id"] == 1) { ?> -->

                                <!-- <?php } ?> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php $select = "SELECT * FROM `resolve` WHERE index_id!='0' ORDER BY index_id DESC";
                            $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);
                            $num_rows = mysqli_num_rows($SQL_STATEMENT);
                            if ($num_rows != 0) {
                                $i = 1;
                                while ($Row = mysqli_fetch_object($SQL_STATEMENT)) {
                            ?>
                                    <tr>
                                        <td><?php echo $i;
                                            $i++; ?></td>

                                        <td><?php echo $Row->consumer_customer; ?></td>
                                        <td><?php echo $Row->customer_name; ?></td>
                                        <td><?php echo $Row->consumer_number; ?></td>
                                        <td><?php echo $Row->email; ?></td>
                                        <td><?php echo $Row->sr_number; ?></td>
                                        <!-- <td><?php echo $Row->type; ?></td> -->
                                        <td>


                                            <button class="btn 
        <?php
                                    if ($Row->status_process == 'Completed') {
                                        echo 'btn btn-success'; // Green button for completed
                                    } elseif ($Row->status_process == 'pending') {
                                        echo 'btn btn-warning text-dark'; // Yellow button for pending
                                    } elseif ($Row->status_process == 'In Progress') {
                                        echo 'btn btn-info'; // Blue button for in-progress
                                    } elseif ($Row->status_process == 'Open') {
                                        echo 'btn btn-primary'; // Blue button for in-progress
                                    } elseif ($Row->status_process == 'Filled') {
                                        echo 'btn btn-secondary'; // Light blue for filled
                                    } else {
                                        echo 'btn btn-dark'; // Default gray button
                                    }
        ?>
    ">
                                                <?= ucfirst($Row->status_process); ?>
                                            </button>
                                        </td>



                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">

                                                    <a class="btn btn-sm p-2 btn-primary text-white  edit-board alert-box-trigger waves-effect waves-light kill-drop" href="ticket_add.php?id=<?php echo $Row->index_id; ?>">
                                                        <i class="text-white fa fa-pencil" style="font-size: 15px;"></i>
                                                    </a>
                                                </div>

                                                <div class="mr-3">
                                                    <!-- Delete button -->
                                                    <a class="btn btn-sm p-2 btn-danger delete-board alert-box-trigger waves-effect waves-light kill-drop text-white" data-modal="delete-board-alert" data-toggle="modal" data-target="#delete-board-alert" href="#0" data-id="<?php echo $Row->index_id; ?>" id="delete-board<?php echo $Row->index_id; ?>">
                                                        <i class="fa fa-trash text-white" style="font-size: 15px;"></i>
                                                    </a>
                                                </div>


                                                <!-- Dropdown menu -->
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-rounded dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="mdi mdi-tune"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                                                        <!-- Completed button -->
                                                        <a class="mt-2 modal-trigger assign-trip dropdown-item" href="javascript:void(0);" onclick="markAsCompleted('<?php echo $Row->index_id; ?>')">
                                                            Completed
                                                        </a>
                                                        <!-- <a class="mt-2 modal-trigger assign-trip dropdown-item" href="javascript:void(0);" onclick="markAsCompleted('<?php echo $Row->index_id; ?>')">
                                                    Filled
                                                    </a> -->


                                                    <div class="dropdown-divider"></div>
                                                        <!-- Completed button -->
                                                        <a class="dropdown-item work-schedule-btn" href="javascript:void(0);"
                                                            data-id="<?php echo htmlspecialchars($Row->city) . '&&' . htmlspecialchars($Row->index_id) . '&&' . htmlspecialchars($Row->consumer_number) . '&&' . htmlspecialchars($Row->customer_name) . '&&' . htmlspecialchars($Row->sr_number) . '&&' . htmlspecialchars($Row->email); ?>"
                                                            data-bs-toggle="modal" data-bs-target="#workScheduleModal">
                                                            QR Code
                                                        </a>


                                                    </div>
                                                </div>

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
                    <h5 class="header-title">Delete Tickets </h5>
                    <button type="btn" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" align="center">
                    <h5 class="text-center">Delete Tickets Details ?</h5>
                    <p>Are you sure you want to delete this Tickets details? All data and attached deals will be lost.</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="form_action" value="DeleteTickets" />
                    <input type="hidden" name="delid" id="delid" value="" />
                    <button class="btn raised bg-primary text-white ml-2 mt-2" data-dismiss="modal">Cancel</button>
                    <button name="delete_now" type="submit" class="btn mt-2 btn-dash btn-danger raised has-icon" id="modalDelete" value="Delete">Delete</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- Bootstrap Modal -->
<div class="modal fade" id="workScheduleModal" tabindex="-1" aria-labelledby="workScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="workScheduleModalLabel">QR Code</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
            </div>
            <div class="modal-body text-center">
                <div id="qrCodeContainer" class="p-3 border rounded bg-light">
                    <h5 class="text-center">Ticket QR Code</h5>
                    <img id="qrCodeImage" src="" alt="" class="img-fluid border p-2" />
                </div>
                <button class="btn btn-primary mt-3" onclick="printQRCode()">Print QR Code</button>
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
    function markAsCompleted(id) {
        console.log('Submitting Ticket ID:', id); // Debugging

        $.ajax({
            url: 'update.php', // Ensure this points to the correct backend script
            type: 'POST',
            data: {
                id: id,
                status: 'Completed'
            },
            dataType: 'json', // Expecting JSON response
            success: function(response) {
                console.log('Server Response:', response); // Debugging

                // Toastr Configuration
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: "fadeIn",
                    hideMethod: "fadeOut",
                    timeOut: 3000, // 3 seconds
                    positionClass: "toast-top-right"
                };

                if (response.status === 'success') {
                    toastr.success(response.message || "Ticket successfully marked as completed!");
                    setTimeout(function() {
                        window.location.reload(); // Refresh the page after success
                    }, 1500);
                } else {
                    toastr.error(response.message || "Something went wrong, please try again!");
                    console.error('Error Response:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Request Failed:', xhr.responseText, status, error);

                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: "fadeIn",
                    hideMethod: "fadeOut",
                    timeOut: 3000,
                    positionClass: "toast-top-right"
                };

                toastr.error("Failed to process the request! Please try again.");
            }
        });
    }
</script>

<script>
    $(document).ready(function() {
        $(".work-schedule-btn").click(function() {
            var data = $(this).data("id"); // Get full data
            console.log("Raw Data Received: " + data); // Debugging log

            // Replace '&&' with a safe separator like '|'
            var safeData = data.replace(/&&/g, '|'); 
            var values = safeData.split('|'); // Use '|' as the separator
            console.log("Processed Data: ", values); // Debugging log

            $.ajax({
                url: "generate_qr.php", // PHP script to generate QR code
                type: "POST",
                data: { qr_data: safeData }, // Send cleaned-up data
                success: function(response) {
                    $("#qrCodeImage").attr("src", response); // Update modal image with QR code
                },
                error: function() {
                    alert("Error generating QR code.");
                }
            });
        });
    });
</script>

<script>
function printQRCode() {
    var qrContainer = document.getElementById("qrCodeContainer").innerHTML;

    if (qrContainer) {
        var newWindow = window.open("", "_blank");
        newWindow.document.write(`
            <html>
            <head>
                <title>Print QR Code</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
                    .qr-box { border: 2px solid #000; padding: 10px; display: inline-block; }
                    h3 { margin-bottom: 15px; }
                    img { width: 200px; height: 200px; }
                    @media print {
                        body { visibility: hidden; }
                        .print-container { visibility: visible; position: absolute; top: 0; left: 0; width: 100%; }
                    }
                </style>
            </head>
            <body>
                <div class="print-container">
                    <h3> QR Code</h3>
                    <div class="qr-box">` + qrContainer + `</div>
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        window.close();
                    }
                <\/script>
            </body>
            </html>
        `);
        newWindow.document.close();
    } else {
        alert("No QR code available to print.");
    }
}
</script>



<!-- Include jQuery (make sure to include this in your HTML head or before your script) -->


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