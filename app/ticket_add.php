<?php include_once './includes/header.php';
include_once './class/fileUploader.php';
error_reporting(1);
include_once './class/databaseConn.php';
include_once './lib/requestHandler.php';
$DatabaseCo = new DatabaseConn();

include_once './class/XssClean.php';
$xssClean = new xssClean();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Database connection (Ensure $DatabaseCo->dbLink is properly initialized)

  // Sanitize form inputs
  $consumer_id = $DatabaseCo->dbLink->real_escape_string($_POST['consumer_id']);
  $feedback_id = $DatabaseCo->dbLink->real_escape_string($_POST['feedback_id']);
  $consumer_customer = $DatabaseCo->dbLink->real_escape_string($_POST['consumer_customer']);
  $consumer_number = $DatabaseCo->dbLink->real_escape_string($_POST['consumer_number']);
  $customer_name = $DatabaseCo->dbLink->real_escape_string($_POST['customer_name']);
  $mobile_number = $DatabaseCo->dbLink->real_escape_string($_POST['mobile_number']);
  $google_url = $DatabaseCo->dbLink->real_escape_string($_POST['google_url']);
  $email = $DatabaseCo->dbLink->real_escape_string($_POST['email']);
  $social_media_agency = $DatabaseCo->dbLink->real_escape_string($_POST['social_media_agency']);
  $address = $DatabaseCo->dbLink->real_escape_string($_POST['address']);
  $sr_number = $DatabaseCo->dbLink->real_escape_string($_POST['sr_number']);
  $priority = $DatabaseCo->dbLink->real_escape_string($_POST['priority']);
  $channel = $DatabaseCo->dbLink->real_escape_string($_POST['channel']);
  $date_opened = $DatabaseCo->dbLink->real_escape_string($_POST['date_opened']);
  $sr_state = $DatabaseCo->dbLink->real_escape_string($_POST['sr_state']);
  $ldr = $DatabaseCo->dbLink->real_escape_string($_POST['ldr']);
  $type = $DatabaseCo->dbLink->real_escape_string($_POST['type']);
  $escalation_level = $DatabaseCo->dbLink->real_escape_string($_POST['escalation_level']);
  $escalation_date = $DatabaseCo->dbLink->real_escape_string($_POST['escalation_date']);
  $sr_district = $DatabaseCo->dbLink->real_escape_string($_POST['sr_district']);
  $resolution_category = $DatabaseCo->dbLink->real_escape_string($_POST['resolution_category']);
  $status = $DatabaseCo->dbLink->real_escape_string($_POST['status']);
  $channel_reference_id = $DatabaseCo->dbLink->real_escape_string($_POST['channel_reference_id']);
  $response_date = $DatabaseCo->dbLink->real_escape_string($_POST['response_date']);
  $partner_name = $DatabaseCo->dbLink->real_escape_string($_POST['partner_name']);
  $sub_category = $DatabaseCo->dbLink->real_escape_string($_POST['sub_category']);
  $receiver_social_id = $DatabaseCo->dbLink->real_escape_string($_POST['receiver_social_id']);
  $assigned_to = $DatabaseCo->dbLink->real_escape_string($_POST['assigned_to']);
  $resolved_date = $DatabaseCo->dbLink->real_escape_string($_POST['resolved_date']);
  $external_party = $DatabaseCo->dbLink->real_escape_string($_POST['external_party']);
  $oate = $DatabaseCo->dbLink->real_escape_string($_POST['oate']);
  $reverse_description = $DatabaseCo->dbLink->real_escape_string($_POST['reverse_description']);
  $resolution_remarks = $DatabaseCo->dbLink->real_escape_string($_POST['resolution_remarks']);
  $country = $DatabaseCo->dbLink->real_escape_string($_POST['country']);
  $state = $DatabaseCo->dbLink->real_escape_string($_POST['state']);
  $cities = isset($_REQUEST['city']) ? $_REQUEST['city'] : [];
  $city = implode(',', array_map([$xssClean, 'clean_input'], $cities));
  $assigned_areas = $DatabaseCo->dbLink->real_escape_string($_POST['assigned_areas']);

  // Checkbox values (set to 0 if unchecked)
  $complaint = isset($_POST['complaint']) ? 1 : 0;
  $redirected_grievance_center = isset($_POST['redirected_grievance_center']) ? 1 : 0;
  $vigilance_flag = isset($_POST['vigilance_flag']) ? 1 : 0;
  $mdg = isset($_POST['mdg']) ? 1 : 0;

  // Get ID for update
  $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

  if ($id > 0) {
    // Update existing record
    $sql = "UPDATE resolve SET 
          consumer_id='$consumer_id', feedback_id='$feedback_id', consumer_customer='$consumer_customer', 
          consumer_number='$consumer_number', customer_name='$customer_name', mobile_number='$mobile_number', 
          google_url='$google_url', email='$email', social_media_agency='$social_media_agency', 
          address='$address', sr_number='$sr_number', priority='$priority', channel='$channel', 
          date_opened='$date_opened', sr_state='$sr_state', ldr='$ldr', type='$type', 
          escalation_level='$escalation_level', escalation_date='$escalation_date', sr_district='$sr_district', 
          resolution_category='$resolution_category', status='$status', channel_reference_id='$channel_reference_id', 
          response_date='$response_date', partner_name='$partner_name', sub_category='$sub_category', 
          receiver_social_id='$receiver_social_id', assigned_to='$assigned_to', resolved_date='$resolved_date', 
          external_party='$external_party', oate='$oate', reverse_description='$reverse_description', 
          resolution_remarks='$resolution_remarks', complaint='$complaint', 
          redirected_grievance_center='$redirected_grievance_center', vigilance_flag='$vigilance_flag', 
          mdg='$mdg',country='$country',state='$state',city='$city',assigned_areas='$assigned_areas'
          WHERE index_id='$id'";

    if ($DatabaseCo->dbLink->query($sql) === TRUE) {
      // echo "Record updated successfully.";
    } else {
      echo "Error: " . $sql . "<br>" . $DatabaseCo->dbLink->error;
    }
  } else {
    // Insert new record with default 'pending' status
    $sql = "INSERT INTO resolve (
          consumer_id, feedback_id, consumer_customer, consumer_number, customer_name, mobile_number, google_url, email,
          social_media_agency, address, sr_number, priority, channel, date_opened, sr_state, ldr, type, escalation_level,
          escalation_date, sr_district, resolution_category, status, channel_reference_id, response_date, partner_name,
          sub_category, receiver_social_id, assigned_to, resolved_date, external_party, oate, reverse_description, 
          resolution_remarks, complaint, redirected_grievance_center, vigilance_flag, mdg, status_process,assigned_areas,country,state,city
      ) VALUES (
          '$consumer_id', '$feedback_id', '$consumer_customer', '$consumer_number', '$customer_name', '$mobile_number', 
          '$google_url', '$email', '$social_media_agency', '$address', '$sr_number', '$priority', '$channel', '$date_opened', 
          '$sr_state', '$ldr', '$type', '$escalation_level', '$escalation_date', '$sr_district', '$resolution_category', 
          '$status', '$channel_reference_id', '$response_date', '$partner_name', '$sub_category', '$receiver_social_id', 
          '$assigned_to', '$resolved_date', '$external_party', '$oate', '$reverse_description', '$resolution_remarks', 
          '$complaint', '$redirected_grievance_center', '$vigilance_flag', '$mdg', 'pending','$assigned_areas','$country','$state','$city'
      )";

    if ($DatabaseCo->dbLink->query($sql) === TRUE) {
      // echo "Record inserted successfully.";
    } else {
      echo "Error: " . $sql . "<br>" . $DatabaseCo->dbLink->error;
    }
  }
  header("Location: Tickets.php");
  exit;
  // Close the connection

}

if ($_REQUEST['id'] > 0) {
  $titl = "Update ";
  $select = "SELECT * FROM resolve WHERE index_id='" . $_REQUEST['id'] . "'"; //echo $select;
  $SQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $select);
  $Row = mysqli_fetch_object($SQL_STATEMENT);
} else {
  $titl = "Add New ";
}



?>
<style>
    /* Custom Styling */
    .select2-container .select2-selection--multiple {
        border: 2px solid #007bff;
        border-radius: 8px;
        padding: 5px;
        color: black;
        border: 0px solid #e5eaef;
        background-color: rgba(255, 255, 255, 0.2);
        /* color: #fff !important; */
    }

    .multiple option {
        color: black !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        color: black;
        border-radius: 5px;
        padding: 3px 8px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: black !important;
    }

    .select2-selection .select2-selection--multiple {
        color: black !important;
    }
</style>
<!-- Page Header -->
<div class="page-header">
  <div class="row">
    <div class="col-sm-12">
      <h3 class="page-title"><?php echo $titl; ?> Tickets </h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="Tickets .php">Tickets Listing</a></li>
        <li class="breadcrumb-item active"><?php echo $titl; ?> Tickets </li>
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
        <form action="" method="post" name="finish-form" enctype="multipart/form-data" class="needs-validation">
          <!-- Consumer Details -->
          <h5>Consumer Details</h5>
          <div class="row">
            <div class="col-md-6">
              <label for="consumerId" class="form-label">Consumer ID</label>
              <input
                type="text"
                class="form-control"
                id="consumerId"
                name="consumer_id"
                value="<?php echo $Row->consumer_id; ?>" />
            </div>
            <div class="col-md-6">
              <label for="feedbackId" class="form-label">Feedback ID</label>
              <input
                type="text"
                class="form-control"
                id="feedbackId"
                value="<?php echo $Row->feedback_id; ?>"
                name="feedback_id" />
            </div>
            <div class="col-md-6">
              <label for="consumerDisabledName" class="form-label">Consumer/Customer Disabled Name</label>
              <input
                type="text"
                class="form-control"
                id="consumerDisabledName"
                value="<?php echo $Row->consumer_customer; ?>"
                name="consumer_customer" />
            </div>
            <div class="col-md-6">
              <label for="consumerNumber" class="form-label">Consumer Number</label>
              <input
                type="text"
                class="form-control"
                id="consumerNumber"
                value="<?php echo $Row->consumer_number; ?>"
                name="consumer_number" />
            </div>
          </div>

          <!-- Customer Details -->
          <h5>Customer Details</h5>
          <div class="row">
            <div class="col-md-6">
              <label for="customerName" class="form-label">Customer Name</label>
              <input
                type="text"
                class="form-control"
                id="customerName"
                value="<?php echo $Row->customer_name; ?>"
                name="customer_name" />
            </div>
            <div class="col-md-6">
              <label for="mobileNumber" class="form-label">Mobile Number</label>
              <input
                type="text"
                class="form-control"
                id="mobileNumber"
                value="<?php echo $Row->mobile_number; ?>"
                name="mobile_number" />
            </div>
            <div class="col-md-6">
              <label for="googleUrl" class="form-label">Google URL</label>
              <input
                type="url"
                class="form-control"
                value="<?php echo $Row->google_url; ?>"
                id="googleUrl"
                name="google_url" />
            </div>
            <div class="col-md-6">
              <label for="emailAddress" class="form-label">Email Address</label>
              <input
                type="email"
                class="form-control"
                value="<?php echo $Row->email; ?>"
                id="emailAddress"
                name="email" />
            </div>
            <div class="col-md-6">
              <label for="socialMediaAgency" class="form-label">Social Media Agency</label>
              <input
                type="text"
                class="form-control"
                id="socialMediaAgency"
                value="<?php echo $Row->social_media_agency; ?>"
                name="social_media_agency" />
            </div>
            <div class="col-md-6">
              <label class="required fw-medium mb-2">Country</label>
              <?php
              $options = ''; // Initialize an empty string for options
              $Vselect = "SELECT * FROM countries ORDER BY name";

              // Execute the SQL query and handle errors
              $VSQL_STATEMENT = mysqli_query($DatabaseCo->dbLink, $Vselect);
              if (!$VSQL_STATEMENT) {
                die("Database query failed: " . mysqli_error($DatabaseCo->dbLink));
              }

              // Fetch each row and append to the $options string
              foreach (mysqli_fetch_all($VSQL_STATEMENT, MYSQLI_ASSOC) as $VRow) {
                // Check if the current row should be marked as selected
                $isSelected = ($VRow['id'] === $Row->country) ? 'selected' : '';
                $options .= "<option value='{$VRow['id']}' $isSelected>{$VRow['name']}</option>";
              }
              ?>

              <select class="form-control mb-3" name="country" id="country">
                <option selected disabled>Select Country</option>
                <?php echo $options; // Output all options 
                ?>
              </select>

            </div>
            <div class="col-md-6">
              <label for="Assigned_Areas" class="form-label fw-bold">State</label>



              <select class="form-control mb-3" name="state" id="state">
                <option selected disabled>Select State</option>
                <?php
                // Load states based on the selected country
                $state = isset($Row->state) ? $Row->state : ''; // Current state code

                $stateQuery = "SELECT * FROM states WHERE country_id = '$Row->country' ORDER BY name";
                $stateResult = mysqli_query($DatabaseCo->dbLink, $stateQuery);
                while ($stateRow = mysqli_fetch_object($stateResult)) {
                  $selected = ($stateRow->id == $state) ? 'selected' : '';
                  echo "<option value='{$stateRow->id}' $selected>{$stateRow->name}</option>";
                }

                ?>
              </select>


            </div>
            <div class="col-md-6">
              <label class="required fw-medium mb-2">City</label>
              <?php
              $options = '';

              // Fetch existing selected city IDs (comma-separated)
              $currentCities = isset($Row->city) ? explode(',', $Row->city) : [];

              // Fetch cities based on the state selection
              $cityQuery = "SELECT * FROM `cities` 
                            WHERE id != '0' AND state_id = '$state' 
                               ORDER BY id DESC";
              $cityResult = mysqli_query($DatabaseCo->dbLink, $cityQuery);

              if (!$cityResult) {
                die("Database query failed: " . mysqli_error($DatabaseCo->dbLink));
              }

              // Generate city dropdown options
              foreach (mysqli_fetch_all($cityResult, MYSQLI_ASSOC) as $cityRow) {
                $selected = in_array($cityRow['id'], $currentCities) ? 'selected' : '';
                $options .= "<option value='{$cityRow['id']}' $selected>{$cityRow['name']}</option>";
              }

              ?>

              <select class="form-control mb-3" name="city[]" id="city" multiple>
                <?php echo $options; ?>
              </select>

            </div>
            <div class="col-md-6 mb-3">
              <label for="address" class="form-label">Area</label>
              <input
                type="text"
                class="form-control"
                id="address"
                value="<?php echo $Row->area; ?>"
                name="assigned_areas" />
            </div>
            <div class="col-md-6 mb-3">
              <label for="address" class="form-label">Address</label>
              <input
                type="text"
                class="form-control"
                id="address"
                value="<?php echo $Row->address; ?>"
                name="address" />
            </div>
          </div>

          <!-- Tickets  Request Details -->
          <h5>Tickets Request (SR) Details</h5>
          <div class="row">
            <div class="col-md-6">
              <label for="srNumber" class="form-label">SR Number</label>
              <input
                type="text"
                class="form-control"
                value="<?php echo $Row->sr_number; ?>"
                id="srNumber"
                name="sr_number" />
            </div>
            <div class="col-md-6">
              <label for="priority" class="form-label">Priority</label>
              <select class="form-control" id="priority" name="priority">
                <option value="" disabled>Choose...</option>
                <option value="high" <?php echo ($Row->priority == 'high') ? 'selected' : ''; ?>>High</option>
                <option value="medium" <?php echo ($Row->priority == 'medium') ? 'selected' : ''; ?>>Medium</option>
                <option value="low" <?php echo ($Row->priority == 'low') ? 'selected' : ''; ?>>Low</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="channel" class="form-label">Channel</label>
              <select class="form-control" id="channel" name="channel">
                <option value="" disabled>Choose...</option>
                <option value="Portal" <?php echo ($Row->channel == 'Portal') ? 'selected' : ''; ?>>Portal</option>
                <option value="Email" <?php echo ($Row->channel == 'Email') ? 'selected' : ''; ?>>Email</option>
                <option value="Phone" <?php echo ($Row->channel == 'Phone') ? 'selected' : ''; ?>>Phone</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="dateOpened" class="form-label">Date Opened</label>
              <input
                type="date"
                class="form-control"
                id="dateOpened"
                value="<?php echo $Row->date_opened; ?>"
                name="date_opened" />
            </div>
            <div class="col-md-6">
              <label for="srState" class="form-label">SR State</label>
              <select class="form-control" id="srState" name="sr_state">
                <option value="" disabled>Choose...</option>
                <option value="Tamil Nadu" <?php echo ($Row->sr_state == 'Tamil Nadu') ? 'selected' : ''; ?>>Tamil Nadu</option>
                <option value="Kerala" <?php echo ($Row->sr_state == 'Kerala') ? 'selected' : ''; ?>>Kerala</option>
                <option value="Karnataka" <?php echo ($Row->sr_state == 'Karnataka') ? 'selected' : ''; ?>>Karnataka</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="ldr" class="form-label">LDR (if applicable)</label>
              <select class="form-control" id="ldr" name="ldr">
                <option value="" disabled>Choose...</option>
                <option value="LPG" <?php echo ($Row->ldr == 'LPG') ? 'selected' : ''; ?>>LPG</option>
                <option value="CNG" <?php echo ($Row->ldr == 'CNG') ? 'selected' : ''; ?>>CNG</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="type" class="form-label">Type</label>
              <select class="form-control" id="type" name="type">
                <option value="" disabled>Choose...</option>
                <option value="incident" <?php echo ($Row->type == 'incident') ? 'selected' : ''; ?>>Incident</option>
                <option value="request" <?php echo ($Row->type == 'request') ? 'selected' : ''; ?>>Request</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="escalationLevel" class="form-label">Escalation Level</label>
              <select class="form-control" id="escalationLevel" name="escalation_level">
                <option value="" disabled>Choose...</option>
                <option value="level1" <?php echo ($Row->escalation_level == 'level1') ? 'selected' : ''; ?>>Level 1</option>
                <option value="level2" <?php echo ($Row->escalation_level == 'level2') ? 'selected' : ''; ?>>Level 2</option>
                <option value="level3" <?php echo ($Row->escalation_level == 'level3') ? 'selected' : ''; ?>>Level 3</option>
              </select>
            </div>


            <div class="col-md-6">
              <label for="escalationDate" class="form-label">Escalation Date</label>
              <input
                type="date"
                class="form-control"
                id="escalationDate"
                value="<?php echo $Row->escalation_date; ?>"
                name="escalation_date" />
            </div>

            <div class="col-md-6">
              <label for="srDistrict" class="form-label">SR District</label>
              <select class="form-control" id="srDistrict" name="sr_district">
                <option value="" disabled>Choose...</option>
                <option value="chennai" <?php echo ($Row->sr_district == 'chennai') ? 'selected' : ''; ?>>Chennai</option>
                <option value="madurai" <?php echo ($Row->sr_district == 'madurai') ? 'selected' : ''; ?>>Madurai</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="resolutionCategory" class="form-label">Resolution Category</label>
              <select class="form-control" id="resolutionCategory" name="resolution_category">
                <option value="" disabled>Choose...</option>
                <option value="resolved" <?php echo ($Row->resolution_category == 'resolved') ? 'selected' : ''; ?>>Resolved</option>
                <option value="pending" <?php echo ($Row->resolution_category == 'pending') ? 'selected' : ''; ?>>Pending</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="status" class="form-label">Status</label>
              <select class="form-control" id="status" name="status">
                <option value="" disabled>Choose...</option>
                <option value="received" <?php echo ($Row->status == 'received') ? 'selected' : ''; ?>>Received</option>
                <option value="in-progress" <?php echo ($Row->status == 'in-progress') ? 'selected' : ''; ?>>In Progress</option>
              </select>
            </div>


            <div class="col-md-6">
              <label for="channelReferenceId" class="form-label">Channel Reference ID</label>
              <input
                type="text"
                class="form-control"
                id="channelReferenceId"
                value="<?php echo $Row->channel_reference_id; ?>"
                name="channel_reference_id" />
            </div>

            <div class="col-md-6">
              <label for="responseDate" class="form-label">Response Date</label>
              <input
                type="date"
                class="form-control"
                id="responseDate"
                value="<?php echo $Row->response_date; ?>"
                name="response_date" />
            </div>

            <div class="col-md-6">
              <label for="partnerName" class="form-label">Partner Name</label>
              <input
                type="text"
                class="form-control"
                id="partnerName"
                value="<?php echo $Row->partner_name; ?>"
                name="partner_name" />
            </div>

            <div class="col-md-6">
              <label for="subCategory" class="form-label">Sub-Category</label>
              <select class="form-control" id="subCategory" name="sub_category" required>
                <option value="" disabled>Choose...</option>
                <option value="technical" <?php echo ($Row->sub_category == 'technical') ? 'selected' : ''; ?>>Technical</option>
                <option value="customer-Tickets" <?php echo ($Row->sub_category == 'customer-Tickets') ? 'selected' : ''; ?>>Customer Tickets</option>
              </select>
            </div>


            <div class="col-md-6">
              <label for="receiverSocialId" class="form-label">Receiver Social ID</label>
              <input
                type="text"
                class="form-control"
                id="receiverSocialId"
                value="<?php echo $Row->receiver_social_id; ?>"
                name="receiver_social_id" />
            </div>

            <div class="col-md-6">
              <label for="assignedTo" class="form-label">Assigned To</label>
              <input
                type="text"
                class="form-control"
                value="<?php echo $Row->assigned_to; ?>"
                id="assignedTo"
                name="assigned_to" />
            </div>

            <div class="col-md-6">
              <label for="resolvedDate" class="form-label">Resolved Date</label>
              <input
                type="date"
                class="form-control"
                value="<?php echo $Row->resolved_date; ?>"
                id="resolvedDate"
                name="resolved_date" />
            </div>

            <div class="col-md-6">
              <label for="externalParty" class="form-label">External Party</label>
              <select class="form-control" id="externalParty" name="external_party">
                <option value="" disabled>Choose...</option>
                <option value="none" <?php echo ($Row->external_party == 'none') ? 'selected' : ''; ?>>None</option>
                <option value="vendor" <?php echo ($Row->external_party == 'vendor') ? 'selected' : ''; ?>>Vendor</option>
              </select>
            </div>


            <div class="col-md-6">
              <label for="oate" class="form-label">Tickets </label>
              <input
                type="text"
                class="form-control"
                id="oate"
                value="<?php echo $Row->oate; ?>"
                name="oate" />
            </div>

            <div class="col-md-6">

            </div>



            <div class="col-md-3 mt-2">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="customCheck1" name="complaint" value="1"
                  <?php echo ($Row->complaint == '1') ? 'checked' : ''; ?> />
                <label for="customCheck1" class="custom-control-label">Complaint Established</label>
              </div>
            </div>

            <div class="col-md-3 mt-2">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="redirectedGrievance" name="redirected_grievance_center" value="1"
                  <?php echo ($Row->redirected_grievance_center == '1') ? 'checked' : ''; ?> />
                <label class="custom-control-label" for="redirectedGrievance">Redirected Grievance</label>
              </div>
            </div>

            <div class="col-md-3 mt-2">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="vigilanceFlag" name="vigilance_flag" value="1"
                  <?php echo ($Row->vigilance_flag == '1') ? 'checked' : ''; ?> />
                <label class="custom-control-label" for="vigilanceFlag">Vigilance Flag</label>
              </div>
            </div>

            <div class="col-md-3 mt-2">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="alternativeUnderMDG" name="mdg" value="1"
                  <?php echo ($Row->mdg == '1') ? 'checked' : ''; ?> />
                <label class="custom-control-label" for="alternativeUnderMDG">Is Alternative Under MDG</label>
              </div>
            </div>



            <div class="col-md-12">
              <label for="description" class="form-label">Description</label>
              <textarea
                class="form-control"
                id="description"

                name="reverse_description"><?php echo $Row->reverse_description; ?></textarea>
            </div>
            <div class="col-md-12">
              <label for="resolutionRemarks" class="form-label">Resolution Remarks</label>
              <textarea
                class="form-control"
                id="resolutionRemarks"
                name="resolution_remarks"><?php echo $Row->resolution_remarks; ?></textarea>
            </div>
          </div>
          <div class="mt-4">
            <input name="submit" type="submit" class="btn btn-primary" value="<?php echo $titl; ?>" />
          </div>
          <!-- Submit Button -->

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
<script>
    $(document).ready(function() {
        // Load states when a country is selected
        $('#country').change(function() {
            let countryCode = $(this).val();
            console.log("Selected Country:", countryCode);

            if (countryCode) {
                $.ajax({
                    url: 'get_states.php',
                    type: 'POST',
                    data: {
                        country_code: countryCode
                    },
                    success: function(response) {
                        console.log("States Loaded:", response);
                        $('#state').html(response);
                        $('#city').html('').trigger('change'); // Reset city dropdown
                    },
                    error: function() {
                        console.error("Error loading states.");
                    }
                });
            } else {
                $('#state, #city').html('').trigger('change'); // Reset both if no country is selected
            }
        });

        // Load cities when a state is selected
        $('#city').select2({
        placeholder: "Select City",
        allowClear: true,
        width: '100%'
    });

    // Handle state change and load corresponding cities
    $('#state').change(function() {
        let stateCode = $(this).val();
        console.log("Selected State:", stateCode);

        if (stateCode) {
            $.ajax({
                url: 'get_cities.php',
                type: 'POST',
                data: { state_code: stateCode },
                success: function(response) {
                    console.log("Cities Loaded:", response);
                    let selectedValues = $('#city').val() || []; // Keep previous selections
                    $('#city').html(response).val(selectedValues).trigger('change');
                },
                error: function() {
                    console.error("Error loading cities.");
                }
            });
        } else {
            $('#city').html('').trigger('change'); // Reset cities dropdown
        }
    });
    });
</script>