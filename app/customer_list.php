<?php
include_once './includes/header.php';





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
            <h3 class="page-title">All Customer </h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Listing of All Customer </li>
            </ul>
        </div>
        <div class="col-md-4" align="right">
            <a class="btn btn-primary btn-rounded" href="customer_add.php">
                <i class="mdi mdi-settings-outline mr-1"></i> Add New Customer
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
                                <th class="w-15">Customer Type</th>
                                <th class="w-25">Company Name</th>
                                <th class="w-20">First Name</th>
                                <th class="w-25">Email</th>
                                <th class="w-15">Phone Number</th>
                                <!-- <th class="w-15">Address</th> -->
                                <th class="w-25">Status</th>
                                <th class="w-25">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $select = "SELECT * FROM `customer` WHERE id!='0' ORDER BY id DESC";
                            $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);
                            $num_rows = mysqli_num_rows($SQL_STATEMENT);
                            if ($num_rows != 0) {
                                $i = 1;
                                while ($Row = mysqli_fetch_object($SQL_STATEMENT)) {
                            ?>
                                    <tr>
                                        <td><?php echo $i;
                                            $i++; ?></td>

                                        <td><?php echo $Row->customer_type; ?></td>
                                        <td><?php echo $Row->company_name; ?></td>
                                        <td><?php echo $Row->first_name; ?></td>
                                        <td><?php echo $Row->email; ?></td>
                                        <td><?php echo $Row->phone_number; ?></td>
                                        <!-- <td><?php echo $Row->address; ?></td> -->

                                        <td><?php echo ucfirst($Row->amc_status); ?> </td>

                                        <td>
                                            <div class="d-flex align-items-center justify-content-between w-100">
                                                <div class="d-flex align-items-center">
                                                    <!-- Edit button -->
                                                    <a class="btn btn-sm p-2 btn-primary text-white  edit-board alert-box-trigger waves-effect waves-light kill-drop" href="Customer_add.php?id=<?php echo $Row->id; ?>">
                                                        <i class="text-white fa fa-pencil" style="font-size: 15px;"></i>
                                                    </a>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    <!-- Delete button -->
                                                    <a class="btn btn-sm p-2 btn-danger delete-board alert-box-trigger waves-effect waves-light kill-drop text-white" data-modal="delete-board-alert" data-toggle="modal" data-target="#delete-board-alert" href="#0" data-id="<?php echo $Row->id; ?>" id="delete-board<?php echo $Row->id; ?>">
                                                        <i class="fa fa-trash text-white" style="font-size: 15px;"></i>
                                                    </a>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-rounded dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="mdi mdi-tune"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                                                        <!-- Completed button -->

                                                        <!-- <a class="mt-2 modal-trigger assign-trip dropdown-item" href="javascript:void(0);" onclick="markAsCompleted('<?php echo $Row->index_id; ?>')">
                                                    Filled
                                                    </a> -->



                                                        <!-- Completed button -->
                                                        <a class="dropdown-item work-schedule-btn" href="javascript:void(0);"
                                                            data-id="<?php echo htmlspecialchars($Row->customer_type) . '&&' . htmlspecialchars($Row->company_name) . '&&' . htmlspecialchars($Row->first_name) . '&&' . htmlspecialchars($Row->email) . '&&' . htmlspecialchars($Row->phone_number) . '&&' . htmlspecialchars($Row->address) . '&&' . htmlspecialchars($Row->city); ?>"
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
                    <h5 class="header-title">Delete Customer </h5>
                    <button type="btn" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" align="center">
                    <h5 class="text-center">Delete Customer Details ?</h5>
                    <p>Are you sure you want to delete this Customer details? All data and attached deals will be lost.</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="form_action" value="DeleteCustomer" />
                    <input type="hidden" name="delid" id="delid" value="" />
                    <button class="btn raised bg-primary text-white ml-2 mt-2" data-dismiss="modal">Cancel</button>
                    <button name="delete_now" type="submit" class="btn mt-2 btn-dash btn-danger raised has-icon" id="modalDelete" value="Delete">Delete</button>
                </div>
            </div>
        </div>
    </form>
</div>
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
                    <h5 class="text-center">Customer QR Code</h5>
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
        console.log('ID:', id); // Log the ID being passed to ensure it's correct

        $.ajax({
            url: 'update.php', // PHP script to process the update
            type: 'POST',
            data: {
                id: id,
                status: 'completed'
            },
            success: function(response) {
                console.log('Response from server:', response); // Log the server response
                if (response == 'success') {
                    Lobibox.notify('success', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        icon: 'fa fa-check-circle',
                        position: 'right bottom',
                        showClass: 'lightSpeedIn',
                        hideClass: 'lightSpeedOut',
                        size: 'mini',
                        msg: 'Update successfully completed.'
                    });
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500); // Reload after notification
                } else {
                    window.location.reload();
                    console.error('Error response received:', response); // Log the error response

                }
            },
            error: function(xhr, status, error) {
                // Log detailed error information
                console.error('AJAX request failed:', status, error);
                Lobibox.notify('error', {
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    icon: 'fa fa-times-circle',
                    position: 'right bottom',
                    showClass: 'lightSpeedIn',
                    hideClass: 'lightSpeedOut',
                    size: 'mini',
                    msg: 'An error occurred during the request.'
                });
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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