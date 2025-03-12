<?php
include_once './includes/header.php';
include_once './class/fileUploader.php';
// Ensure database connection is included

error_reporting(2);
ini_set('display_errors', 1);

// Fetch Suppliers




// Handle form submission

if (isset($_POST['submit'])) {



    try {
        // Start transaction


        // Retrieve input values safely
        $d_id = isset($_POST['id']) ? intval($_POST['id']) : null; // Ensure ID exists for update
        $customer_id = trim($_POST['customer_id']);
        $contract_number = trim($_POST['contract_number']);
        $start_date = trim($_POST['start_date']);
        $end_date = trim($_POST['end_date']);
        $coverage = trim($_POST['coverage']);
        $cost = trim($_POST['cost']);
        $status = $_POST['status'] ?? 'Active'; // Default status

        // Input validation
        if (empty($customer_id) || empty($contract_number) || empty($start_date) || empty($end_date) || empty($coverage) || !is_numeric($cost)) {
            throw new Exception("Invalid input. Please fill all fields correctly.");
        }

        if (!empty($d_id) && $d_id > 0) {
            // Update existing record
            $sql = "UPDATE amc_contracts 
                    SET customer_id = ?, contract_number = ?, start_date = ?, end_date = ?, coverage = ?, cost = ?, status = ? 
                    WHERE id = ?";
            $stmt = $DatabaseCo->dbLink->prepare($sql);
            $stmt->execute([$customer_id, $contract_number, $start_date, $end_date, $coverage, $cost, $status, $d_id]);
        } else {
            // Insert new record
            $sql = "INSERT INTO amc_contracts (customer_id, contract_number, start_date, end_date, coverage, cost, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $DatabaseCo->dbLink->prepare($sql);
            $stmt->execute([$customer_id, $contract_number, $start_date, $end_date, $coverage, $cost, $status]);
        }

        // Commit transaction
        $DatabaseCo->dbLink->commit();

        // Redirect after successful operation
        header("Location: list_amc.php");
        exit();
    } catch (PDOException $e) {
        // Rollback in case of a database error
        $DatabaseCo->dbLink->rollBack();
        die("Database Error: " . $e->getMessage());
    } catch (Exception $e) {
        // Rollback for other errors
        $DatabaseCo->dbLink->rollBack();
        die("Error: " . $e->getMessage());
    }
}


// Fetch product details if editing
$titl = "Add New ";
if (isset($_REQUEST['id']) && $_REQUEST['id'] > 0) {
    $d_id = $_REQUEST['id'];
    $titl = "Update ";
    $selectQuery = "SELECT * FROM `amc_contracts` WHERE `id` = ?";

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
            <h3 class="page-title">AMC Contracts </h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="list_amc .php">AMC Contracts Listing</a></li>
                <li class="breadcrumb-item active">AMC Contracts </li>
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

                <form method="post" action="create_amc.php" enctype="multipart/form-data" class="needs-validation">
                    <!-- Hidden ID Field for Updating -->
                    <input type="hidden" name="id" value="<?= isset($Row->id) ? intval($Row->id) : '' ?>">

                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Customer ID</label>
                        <input type="number" name="customer_id" id="customer_id"
                            value="<?= isset($Row->customer_id) ? htmlspecialchars($Row->customer_id) : '' ?>"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="contract_number" class="form-label">Contract Number</label>
                        <input type="number" name="contract_number" id="contract_number"
                            value="<?= isset($Row->contract_number) ? htmlspecialchars($Row->contract_number) : '' ?>"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date"
                            value="<?= isset($Row->start_date) ? htmlspecialchars($Row->start_date) : '' ?>"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date"
                            value="<?= isset($Row->end_date) ? htmlspecialchars($Row->end_date) : '' ?>"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="cost" class="form-label">Cost</label>
                        <input type="number" step="0.01" name="cost" id="cost"
                            value="<?= isset($Row->cost) ? htmlspecialchars($Row->cost) : '' ?>"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Coverage</label>
                        <textarea class="form-control" name="coverage" placeholder="Enter coverage"><?= isset($Row->coverage) ? htmlspecialchars($Row->coverage) : '' ?></textarea>
                    </div>

                    <?php if (isset($Row->id) && intval($Row->id) > 0) { ?>
                        <div class="mb-3">
                            <label for="status">Status:</label>
                            <select name="status" class="form-control" id="status">
                                <option value="Active" <?= isset($Row->status) && $Row->status == 'Active' ? 'selected' : '' ?>>Active</option>
                                <option value="Expired" <?= isset($Row->status) && $Row->status == 'Expired' ? 'selected' : '' ?>>Expired</option>
                                <option value="Renewed" <?= isset($Row->status) && $Row->status == 'Renewed' ? 'selected' : '' ?>>Renewed</option>
                                <option value="Terminated" <?= isset($Row->status) && $Row->status == 'Terminated' ? 'selected' : '' ?>>Terminated</option>
                            </select>
                        </div>
                    <?php } ?>

                    <button type="submit" name="submit" class="btn btn-primary">
                        <?= isset($Row->id) ? 'Update AMC' : 'Create AMC' ?>
                    </button>
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