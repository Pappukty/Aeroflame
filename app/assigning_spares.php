<?php
include_once './includes/header.php';
include_once './class/fileUploader.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch Spare Parts (Suppliers)
try {
    $stmt = $DatabaseCo->dbLink->prepare("SELECT * FROM products");

    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $DatabaseCo->dbLink->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $suppliers = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (Exception $e) {
    die("Error fetching products: " . $e->getMessage());
}

// Fetch existing issued spare details if editing
$title = "Add New ";
$row = null;

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $d_id = intval($_GET['id']);
    $title = "Update ";

    $stmt = $DatabaseCo->dbLink->prepare("SELECT * FROM issued_spares WHERE id = ?");
    $stmt->bind_param("i", $d_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_object();
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d_id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $spare_part_id = $_POST['spare_part_id'] ?? null;
    $issued_to = $_POST['issued_to'] ?? null;
    $issued_by = $_SESSION['user_id'] ?? null; // Ensure user authentication
    $issue_date = date('Y-m-d H:i:s');
    $approval_status = 'Pending';

    // Input validation
    if (!$spare_part_id || !$issued_to || !$issued_by) {
        die("Invalid input. Please fill all fields correctly.");
    }

    try {
        $DatabaseCo->dbLink->begin_transaction();

        if ($d_id) {
            // Update existing record
            $stmt = $DatabaseCo->dbLink->prepare("UPDATE issued_spares SET spare_part_id = ?, issued_to = ?, issued_by = ?, issue_date = ?, approval_status = ? WHERE id = ?");
            $stmt->bind_param("iisssi", $spare_part_id, $issued_to, $issued_by, $issue_date, $approval_status, $d_id);
        } else {
            // Insert new record
            $stmt = $DatabaseCo->dbLink->prepare("INSERT INTO issued_spares (spare_part_id, issued_to, issued_by, issue_date, approval_status) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisss", $spare_part_id, $issued_to, $issued_by, $issue_date, $approval_status);
        }

        $stmt->execute();
        $DatabaseCo->dbLink->commit();

        header("Location: issued_spares_list.php");
        exit();
    } catch (Exception $e) {
        $DatabaseCo->dbLink->rollback();
        die("Error: " . $e->getMessage());
    }
}

// Handle approval action separately
if (isset($_GET['approve_id'])) {
    updateIssuedSpare(intval($_GET['approve_id']), 'Approved');
    header("Location: issued_spares_list.php");
    exit();
}

if (isset($_GET['reject_id'])) {
    updateIssuedSpare(intval($_GET['reject_id']), 'Rejected');
    header("Location: issued_spares_list.php");
    exit();
}

// Function to update approval status
function updateIssuedSpare($id, $approval_status) {
    global $DatabaseCo;

    $stmt = $DatabaseCo->dbLink->prepare("UPDATE issued_spares SET approval_status = ? WHERE id = ?");
    $stmt->bind_param("si", $approval_status, $id);

    if ($stmt->execute()) {
        return "Issued spare updated successfully.";
    } else {
        return "Error updating issued spare: " . $DatabaseCo->dbLink->error;
    }
}
?>


<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title">Assigning Spare Parts </h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="Tickets .php">Assigning Spare Parts Listing</a></li>
                <li class="breadcrumb-item active">Assigning Spare Parts </li>
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
                <form method="post" action="">
    <input type="hidden" name="id" value="<?= $row->id ?? '' ?>">

    <label for="spare_part_id">Spare Part:</label>
    <select name="spare_part_id" id="spare_part_id" required class="form-control">
        <?php foreach ($suppliers as $supplier): ?>
            <option value="<?= htmlspecialchars($supplier['id']) ?>" 
                <?= isset($row->spare_part_id) && $row->spare_part_id == $supplier['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($supplier['product_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="issued_to">Issued To:</label>
    <input type="text" name="issued_to" id="issued_to" class="form-control" 
           value="<?= htmlspecialchars($row->issued_to ?? '') ?>" required>

    <button type="submit"  class="btn btn-primary mt-3" id="save" name="submit" ><?= $title ?> Spare</button>
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