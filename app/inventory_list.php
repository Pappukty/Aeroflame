<?php
include_once './includes/header.php';

?>

<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-8">
            <h3 class="page-title">All Inventory </h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Listing of All Inventory </li>
            </ul>
        </div>
        <div class="col-md-4" align="right">
            <a class="btn btn-primary btn-rounded" href="Inventory_add.php">
                <i class="mdi mdi-settings-outline mr-1"></i> Add New Inventory
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
                            <th class="w-15">Product Image</th>
                            <th class="w-25">Product Name</th>
                            <th class="w-20">Category</th>
                            <th class="w-25">Sub-category</th>
                            <th class="w-15">Stock Quantity</th>
                            <th class="w-15">Purchase Price</th>
                            <th class="w-25">Supplier Name</th>
                            <!-- <?php if ($_SESSION["user_id"] == 1) { ?> -->
                            <th class="w-25">Action</th>
                            <!-- <?php } ?> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php $select = "SELECT * FROM `products` WHERE id!='0' ORDER BY id DESC";
                        $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);
                        $num_rows = mysqli_num_rows($SQL_STATEMENT);
                        if ($num_rows != 0) {
                            $i = 1;
                            while ($Row = mysqli_fetch_object($SQL_STATEMENT)) {
                        ?>
                                <tr>
                                    <td><?php echo $i;
                                        $i++; ?></td>
                                    <td>
                                        <?php if ($Row->product_image != '') { ?>
                                            <a href="../uploads/product/<?php echo $Row->product_image; ?>" target="_blank"><img src="../uploads/product/<?php echo $Row->product_image; ?>" class="rounded-circle header-profile-user" width="60" height="60px" alt="" data-demo-src="../uploads/product/<?php echo $Row->product_image; ?>"></a>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $Row->product_name; ?></td>
                                    <td><?php echo $Row->category; ?></td>
                                    <td><?php echo $Row->subcategory; ?></td>
                                    <td><?php echo $Row->stock_quantity; ?></td>
                                    <td><?php echo $Row->purchase_price; ?></td>

                                    <td><?php echo ucfirst($Row->supplier_name); ?> </td>
                                    <!-- <?php if ($_SESSION["user_id"] == 1) { ?> -->
                                    <td>
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <div class="d-flex align-items-center">
                                                <!-- Edit button -->
                                                <a class="btn btn-sm p-2 btn-primary text-white  edit-board alert-box-trigger waves-effect waves-light kill-drop" href="Inventory_add.php?id=<?php echo $Row->id; ?>">
                                                    <i class="text-white fa fa-pencil" style="font-size: 15px;"></i>
                                                </a>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <!-- Delete button -->
                                                <a class="btn btn-sm p-2 btn-danger delete-board alert-box-trigger waves-effect waves-light kill-drop text-white" data-modal="delete-board-alert" data-toggle="modal" data-target="#delete-board-alert" href="#0" data-id="<?php echo $Row->id; ?>" id="delete-board<?php echo $Row->id; ?>">
                                                    <i class="fa fa-trash text-white" style="font-size: 15px;"></i>
                                                </a>
                                            </div>

                                            <!-- <div class="dropdown">
     
        <button class="btn btn-light btn-rounded dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mdi mdi-tune"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">

            <a class="mt-2 modal-trigger assign-trip dropdown-item" href="javascript:void(0);" onclick="markAsCompleted('<?php echo $Row->index_id; ?>')">
                Completed
            </a>
        </div>
    </div> -->
                                        </div>

                                    </td>

                                    <!-- <?php } ?> -->
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
                    <h5 class="header-title">Delete Inventory </h5>
                    <button type="btn" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" align="center">
                    <h5 class="text-center">Delete Inventory Details ?</h5>
                    <p>Are you sure you want to delete this Inventory details? All data and attached deals will be lost.</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="form_action" value="DeleteProduct" />
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