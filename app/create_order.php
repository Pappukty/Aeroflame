<?php 
include_once './includes/header.php';
include_once './class/fileUploader.php';
// Ensure database connection is included

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch Suppliers
try {
    // Fetch Suppliers
    $stmt = $DatabaseCo->dbLink->prepare("SELECT id, product_name FROM products");
    $stmt->execute();
    $result = $stmt->get_result();
    $suppliers = $result->fetch_all(MYSQLI_ASSOC); // Fetch all suppliers
    $stmt->close();
} catch (Exception $e) {
    die("Error fetching suppliers: " . $e->getMessage());
}


// Handle form submission
if (isset($_POST['submit'])) {
    $d_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : null; // Ensure ID exists
    try {
        $order_number = "PO-" . time(); // Unique Order Number
        $supplier_id = $_POST['supplier_id'] ?? null;
        $total_cost = $_POST['total_cost'] ?? null;
        $status = $_POST['status'] ?? 'Pending'; // Default to 'Pending' if not provided

        // Input validation
        if (!$supplier_id || !$total_cost || !is_numeric($total_cost)) {
            throw new Exception("Invalid input. Please fill all fields correctly.");
        }

        // Start transaction


        if ($d_id) {
            // If $d_id exists, update record
            $sql = "UPDATE purchase_order SET status = ? WHERE id = ?";
            $stmt = $DatabaseCo->dbLink->prepare($sql);
            $stmt->execute([$status, $d_id]);
        } else {
            // If no $d_id, insert new record
            $sql = "INSERT INTO purchase_order (order_number, supplier_id, total_cost, status) VALUES (?, ?, ?, ?)";
            $stmt = $DatabaseCo->dbLink->prepare($sql);
            $stmt->execute([$order_number, $supplier_id, $total_cost, $status]);
        }

        // Commit transaction
        $DatabaseCo->dbLink->commit();

        // Redirect after successful operation
        header("Location: view_orders.php");
        exit();
    } catch (Exception $e) {
        $DatabaseCo->dbLink->rollBack(); // Rollback if error occurs
        die("Error: " . $e->getMessage());
    }
}

// Fetch product details if editing
$titl = "Add New ";
if (isset($_REQUEST['id']) && $_REQUEST['id'] > 0) {
    $d_id = $_REQUEST['id'];
    $titl = "Update ";
    $selectQuery = "SELECT * FROM `purchase_order` WHERE `id` = ?";
    
    $stmt = $DatabaseCo->dbLink->prepare($selectQuery);
    $stmt->bind_param("i", $d_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $Row = $result->fetch_object();
    $stmt->close();
}
?>

<!-- Page Header -->
<div class="page-header">
  <div class="row">
    <div class="col-sm-12">
      <h3 class="page-title">Inventory </h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="Tickets .php">Inventory Listing</a></li>
        <li class="breadcrumb-item active">Inventory </li>
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
        <form method="post" action="create_order.php"  enctype="multipart/form-data" class="needs-validation">>
    <div class="mb-3">
        <label for="supplier_id" class="form-label">Supplier:</label>
        <select name="supplier_id" id="supplier_id" class="form-control" required>
            <option value="">Select Supplier</option>
            <?php foreach ($suppliers as $supplier) { ?>
                <option value="<?= htmlspecialchars($supplier['id']) ?>" 
                    <?= isset($Row->supplier_id) && $Row->supplier_id == $supplier['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($supplier['product_name']) ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="total_cost" class="form-label">Total Cost:</label>
        <input type="number" step="0.01" name="total_cost" id="total_cost" 
               value="<?= isset($Row->total_cost) ? htmlspecialchars($Row->total_cost) : '' ?>" 
               class="form-control" required>
    </div>
    <?php
    if(isset($_REQUEST['id']) && $_REQUEST['id'] > 0){

        ?>

    <div class="mb-3">
    <select name="status" class="form-control">
            <option value="Pending" <?= $Row->status == 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Completed" <?= $Row->status == 'Completed' ? 'selected' : '' ?>>Completed</option>
            <option value="Cancelled" <?= $Row->status == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>
    </div>
<?php }
    ?>
    <button type="submit" name="submit" class="btn btn-primary">Create Order</button>
</form>


      </div>
    </div>


  </div>

</div>
<!-- end row -->

<?php
include_once './includes/footer.php';
?>
<script src="https://cdn.ckeditor.com/4.16.2/standard-all/ckeditor.js"></script>
<script type="text/javascript">
  CKEDITOR.replace('editor1', {
    extraPlugins: 'editorplaceholder',
    removeButtons: 'PasteFromWord'
  });
</script>


<style>
  .is-invalid {
    border-color: #dc3545;
  }

  .invalid-feedback {
    color: #dc3545;
    font-size: 0.875em;
    margin-top: 0.25rem;
  }
</style>
 

