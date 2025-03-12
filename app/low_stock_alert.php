<?php
include_once './includes/header.php';
try {
    // Prepare the SQL statement
    $stmt = $DatabaseCo->dbLink->prepare("SELECT product_code, stock_quantity FROM products WHERE stock_quantity < reorder_threshold");
    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();
    // Fetch all results as an associative array
 
    $low_stock_parts = $result->fetch_all(MYSQLI_ASSOC); // Fetch all suppliers
    $stmt->close();
} catch (Exception $e) {
    die("Error fetching suppliers: " . $e->getMessage());
}
?>
<style>
    #delete_form .modal-dialog .modal-content .modal-body p {
        color: white !important;
    }
</style>
<!-- Font Awesome CDN -->


<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-8">
            <h3 class="page-title">All Low Stock Alerts </h3>
         
        </div>
      
    </div>
</div>
<!-- /Page Header -->

<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12">
        <div class="card border-0 shadow-lg rounded-3">
            <div class="card-header bg-danger text-white d-flex align-items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <h4 class="mb-0">Low Stock Alerts</h4>
            </div>
            <div class="card-body">
                <?php if (count($low_stock_parts) > 0) { ?>
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Urgent:</strong> Some parts are running low. Please reorder!
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($low_stock_parts as $part) { ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong><?= htmlspecialchars($part['part_number']) ?></strong>
                                <span class="badge bg-danger rounded-pill p-2">
                                    Stock: <?= htmlspecialchars($part['stock_quantity']) ?> <i class="fas fa-box-open"></i>
                                </span>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } else { ?>
                    <div class="alert alert-success text-center d-flex align-items-center justify-content-center" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>All parts are in sufficient stock.</strong>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- end row -->

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