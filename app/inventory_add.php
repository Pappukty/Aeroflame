<?php 
include_once './includes/header.php';
include_once './class/fileUploader.php';
error_reporting(4);
ini_set('display_errors', 1);

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve and sanitize form data
    $product_name = trim($_POST['product_name']);
    $product_code = trim($_POST['product_code']);
    $category = trim($_POST['category']);
    $subcategory = trim($_POST['subcategory']);
    $stock_quantity = intval($_POST['stock_quantity']);
    $stock_status = trim($_POST['stock_status']);
    $purchase_price = floatval($_POST['purchase_price']);
    $selling_price = floatval($_POST['selling_price']);
    $supplier_name = trim($_POST['supplier_name']);
    $supplier_id = intval($_POST['supplier_id']);
    $description = trim($_POST['description']);
    $supplier_contact = trim($_POST['supplier_contact']);
    $product_image = "";

    // Check if updating or inserting a new record
    if (!empty($_REQUEST['id']) && intval($_REQUEST['id']) > 0) {
        $d_id = intval($_REQUEST['id']);

        // Update existing product
        $updateQuery = "UPDATE `products` 
                        SET `product_name` = ?, `product_code` = ?, `category` = ?, `subcategory` = ?, 
                            `stock_quantity` = ?, `stock_status` = ?, `purchase_price` = ?, 
                            `selling_price` = ?, `supplier_name` = ?, `supplier_contact` = ?, `supplier_id` = ?, `description` = ?  
                        WHERE `id` = ?";

        $stmt = $DatabaseCo->dbLink->prepare($updateQuery);
        $stmt->bind_param(
            "ssssisssssssi",
            $product_name, $product_code, $category, $subcategory, 
            $stock_quantity, $stock_status, $purchase_price, 
            $selling_price, $supplier_name, $supplier_contact, $supplier_id, $description, $d_id
        );
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert new product
        $insertQuery = "INSERT INTO `products` (`product_name`, `product_code`, `category`, `subcategory`, 
                                                `stock_quantity`, `stock_status`, `purchase_price`, 
                                                `selling_price`, `supplier_name`, `supplier_contact`, `product_image`, `supplier_id`, `description`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $DatabaseCo->dbLink->prepare($insertQuery);
        $stmt->bind_param(
            "ssssissssssss",
            $product_name, $product_code, $category, $subcategory, 
            $stock_quantity, $stock_status, $purchase_price, 
            $selling_price, $supplier_name, $supplier_contact, $product_image, $supplier_id, $description
        );
        $stmt->execute();
        $d_id = $stmt->insert_id; // Get the inserted ID
        $stmt->close();
    }

    // Handle image upload if provided
    if (!empty($_FILES['product_image']['name'])) {
        $uploadImage = new ImageUploader($DatabaseCo);
        $product_image = $uploadImage->upload($_FILES['product_image'], "product");

        if ($product_image) {
            $updateImageQuery = "UPDATE `products` SET `product_image` = ? WHERE `id` = ?";
            $stmt = $DatabaseCo->dbLink->prepare($updateImageQuery);
            $stmt->bind_param("si", $product_image, $d_id);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Redirect to inventory list
    header("Location: Inventory_list.php");
    exit;
}

// Fetch product details if editing
$titl = "Add New ";
if (isset($_REQUEST['id']) && $_REQUEST['id'] > 0) {
    $d_id = $_REQUEST['id'];
    $titl = "Update ";
    $selectQuery = "SELECT * FROM `products` WHERE `id` = ?";
    
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
      <h3 class="page-title"><?php echo $titl; ?> Inventory </h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="Tickets .php">Inventory Listing</a></li>
        <li class="breadcrumb-item active"><?php echo $titl; ?> Inventory </li>
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
        <form  method="post" name="finish-form" enctype="multipart/form-data" class="needs-validation">
    <div class="row">
        <!-- Product Name -->
        <div class="col-md-6 col-12 mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" class="form-control" name="product_name" placeholder="Enter Product Name" value="<?php echo $Row->product_name; ?>" required>
        </div>
        <!-- Product Code / SKU -->
        <div class="col-md-6 col-12 mb-3">
            <label class="form-label">Product Code / SKU</label>
            <input type="text" class="form-control" name="product_code" placeholder="Enter SKU" value="<?php echo $Row->product_code; ?>" required>
        </div>
        <!-- Category -->
        <div class="col-md-6 col-12 mb-3">
    <label class="form-label">Category</label>
    <select class="form-control" name="category" id="category" required>
        <option value="">Select Category</option>
        <option value="LPG Cylinder" <?php if ($Row->category == "LPG Cylinder") echo "selected"; ?>>LPG Cylinder</option>
        <option value="Industrial Gas" <?php if ($Row->category == "Industrial Gas") echo "selected"; ?>>Industrial Gas</option>
        <option value="Medical Gas" <?php if ($Row->category == "Medical Gas") echo "selected"; ?>>Medical Gas</option>
    </select>
</div>

<div class="col-md-6 col-12 mb-3">
    <label class="form-label">Sub-category</label>
    <select class="form-control" name="subcategory" id="subcategory" required>
        <option value="">Select Sub-category</option>
        <option value="Domestic LPG" <?php if ($Row->subcategory == "Domestic LPG") echo "selected"; ?>>Domestic LPG</option>
        <option value="Commercial LPG" <?php if ($Row->subcategory == "Commercial LPG") echo "selected"; ?>>Commercial LPG</option>
        <option value="Household Gas" <?php if ($Row->subcategory == "Household Gas") echo "selected"; ?>>Household Gas</option>
    </select>
</div>


        <!-- Stock Quantity -->
        <div class="col-md-6 col-12 mb-3">
            <label class="form-label">Stock Quantity</label>
            <input type="number" class="form-control" name="stock_quantity" value="<?php echo $Row->stock_quantity; ?>" placeholder="Enter Stock Quantity" required>
        </div>
        <!-- Stock Status -->
        <div class="col-md-6 col-12 mb-3">
    <label class="form-label">Stock Status</label>
    <select class="form-control" name="stock_status" required>
        <option value="In Stock" <?php if ($Row->stock_status == "In Stock") echo "selected"; ?>>In Stock</option>
        <option value="Low Stock" <?php if ($Row->stock_status == "Low Stock") echo "selected"; ?>>Low Stock</option>
        <option value="Out of Stock" <?php if ($Row->stock_status == "Out of Stock") echo "selected"; ?>>Out of Stock</option>
    </select>
</div>

        <!-- Purchase Price -->
        <div class="col-md-6 col-12 mb-3">
            <label class="form-label">Purchase Price</label>
            <input type="number" class="form-control" name="purchase_price" value="<?php echo $Row->purchase_price; ?>" placeholder="Enter Purchase Price" required>
        </div>
        <div class="col-md-6 col-12 mb-3">
            <label class="form-label">Supplier Id</label>
            <input type="number" class="form-control" name="supplier_id" value="<?php echo $Row->supplier_id; ?>" placeholder="Enter  Supplier id" required>
        </div>
        
        <!-- Selling Price -->
        <div class="col-md-6 col-12 mb-3">
            <label class="form-label">Selling Price</label>
            <input type="number" class="form-control" name="selling_price " value="<?php echo $Row->selling_price; ?>" placeholder="Enter Selling Price" required>
        </div>
        <!-- Supplier Name -->
        <div class="col-md-6 col-12 mb-3">
            <label class="form-label">Supplier Name</label>
            <input type="text" class="form-control" name="supplier_name" value="<?php echo $Row->supplier_name; ?>" placeholder="Enter Supplier Name" required>
        </div>
        <!-- Supplier Contact -->
        <div class="col-md-6 col-12 mb-3">
            <label class="form-label">Supplier Contact</label>
            <input type="tel" class="form-control" name="supplier_contact" value="<?php echo $Row->supplier_contact; ?>" placeholder="Enter Contact Number" required>
        </div>
        <!-- Product Image Upload -->
        <div class="col-md-6 col-12 mb-3">
            <label class="form-label">Product Image</label>
            <input type="file" class="form-control" name="product_image" value="<?php echo $Row->product_image; ?>">
        </div>
        <div class="col-md-6 col-12 mb-3">
            <label class="form-label">Description</label>
            <input type="text" class="form-control" name="description" value="<?php echo $Row->description; ?>" placeholder="Enter  Description" required>
        </div>
        <!-- Submit Button -->
        <div class="col-12 text-center mt-3">
            <button name="submit" type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
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
 

