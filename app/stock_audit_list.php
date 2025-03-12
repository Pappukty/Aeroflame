<?php
include_once './includes/header.php';
include_once './class/databaseConn.php';
include_once './lib/requestHandler.php';

require_once './StockAudit.php';

// Initialize database connection
$DatabaseCo = new DatabaseConn();
$stockAudit = new StockAudit($DatabaseCo);

// Fetch all audits
$audits = $stockAudit->getAllAudits();

// Print results

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
            <h3 class="page-title">All Stock Audit List </h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Listing of All Stock Audit List </li>
            </ul>
        </div>
        <div class="col-md-4" align="right">
            <a class="btn btn-primary btn-rounded" href="add_audit.php">
                <i class="mdi mdi-settings-outline mr-1"></i> Add New Stock Audit
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
                                <th>ID</th>
                                <th>Spare Part</th>
                                <th>Actual Stock</th>
                                <th>Recorded Stock</th>
                                <th>Variance</th>
                                <th>Audit Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($audits as $audit): ?>
        <tr>
            <td><?= $audit['id'] ?></td>
            <td><?= htmlspecialchars($audit['spare_name']) ?></td>
            <td><?= $audit['actual_stock'] ?></td>
            <td><?= $audit['recorded_stock'] ?></td>
            <td><?= $audit['variance'] ?></td>
            <td><?= $audit['audit_date'] ?></td>
            <td>
            <a class="btn btn-sm p-2 btn-primary text-white  edit-board alert-box-trigger waves-effect waves-light kill-drop" href="add_audit.php?id=<?= $audit['id'] ?>">
                                                        <i class="text-white fa fa-pencil" style="font-size: 15px;"></i>
                                                    </a>
               
                <a class="btn btn-sm p-2 btn-danger delete-board alert-box-trigger waves-effect waves-light kill-drop text-white" data-modal="delete-board-alert" data-toggle="modal" data-target="#delete-board-alert" href="#0" data-id="<?= $audit['id'] ?>" id="delete-board<?= $audit['id'] ?>">
                                                        <i class="fa fa-trash text-white" style="font-size: 15px;"></i>
                                                    </a>
            </td>
        </tr>
    <?php endforeach; ?>

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
                    <h5 class="header-title">Delete Stock Audit </h5>
                    <button type="btn" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" align="center">
                    <h5 class="text-center">Delete Stock Audit Details ?</h5>
                    <p>Are you sure you want to delete this Stock Audit details? All data and attached deals will be lost.</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="form_action" value="DeleteStock_Audit" />
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
                    <h5 class="text-center">Barcode QR Code</h5>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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