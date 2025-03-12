
<?php 
include_once './includes/header.php';
include_once './class/fileUploader.php';
// Ensure database connection is included

error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once './StockAudit.php';

$stockAudit = new StockAudit($DatabaseCo);
$row = null;

if (isset($_GET['id'])) {
    $row = $stockAudit->getAuditById($_GET['id']);
}
// Fetch Suppliers

?>

<!-- Page Header -->
<div class="page-header">
  <div class="row">
    <div class="col-sm-12">
      <h3 class="page-title">StockAudit </h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="Tickets .php">StockAudit Listing</a></li>
        <h4 class="ml-4"><?= isset($row) ? "Update" : "Add New" ?> Stock Audit</h4>
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
        <form method="post" action="manage_audit.php">
    <input type="hidden" name="id" value="<?= $row['id'] ?? '' ?>">

    <label>Spare Part ID:</label>
    <input type="number" name="spare_part_id" class="form-control" value="<?= $row['spare_part_id'] ?? '' ?>" required>

    <label>Actual Stock:</label>
    <input type="number" name="actual_stock" class="form-control"  value="<?= $row['actual_stock'] ?? '' ?>" required>

    <label>Recorded Stock:</label>
    <input type="number" name="recorded_stock" class="form-control" value="<?= $row['recorded_stock'] ?? '' ?>" required>

    <button type="submit" class="btn btn-primary mt-2" id="save">Submit</button>
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
 

