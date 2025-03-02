<?php
include_once './includes/header.php';
include_once './class/fileUploader.php';
error_reporting(1);

if (isset($_REQUEST['submit'])) {
    $technician_name = $xssClean->clean_input($_REQUEST['technician_name']);
    $email = $xssClean->clean_input($_REQUEST['email']);
    $skills = $xssClean->clean_input($_REQUEST['skills']);
    $contact = $xssClean->clean_input($_REQUEST['contact']);
    $availability_status = $xssClean->clean_input($_REQUEST['availability_status']);
    $work_schedule = $xssClean->clean_input($_REQUEST['work_schedule']);
    $performance_metrics = $xssClean->clean_input($_REQUEST['performance_metrics']);
    $dob = $xssClean->clean_input($_REQUEST['dob']);
    $address = $xssClean->clean_input($_REQUEST['address']);
    $experience = $xssClean->clean_input($_REQUEST['experience']);
    $log_date = date("Y-m-d H:i:s");

    // Handle assigned areas as an array
    $assigned_areas = isset($_REQUEST['assigned_areas']) ? $_REQUEST['assigned_areas'] : [];
    $assigned_areas_str = implode(',', array_map([$xssClean, 'clean_input'], $assigned_areas));

    // Check if it's an update operation
    if (!empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
        $d_id = intval($_REQUEST['id']);

        // Fetch the existing employee_id
        $stmt = $DatabaseCo->dbLink->prepare("SELECT employee_id FROM technician WHERE id=?");
        $stmt->bind_param("i", $d_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $employee_id = $row['employee_id']; // Keep the existing employee ID

        // Update existing technician
        $stmt = $DatabaseCo->dbLink->prepare("UPDATE technician SET technician_name=?,email=?,dob=?, skills=?, contact=?, assigned_areas=?, availability_status=?, work_schedule=?, performance_metrics=?,address=?,experience=? WHERE id=?");
        $stmt->bind_param("sssssssssssi", $technician_name, $email, $dob, $skills, $contact, $assigned_areas_str, $availability_status, $work_schedule, $performance_metrics, $address, $experience, $d_id);
        $stmt->execute();
    } else {
        // Generate a new unique Employee ID
        $stmt = $DatabaseCo->dbLink->prepare("SELECT MAX(id) AS max_id FROM technician");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $next_id = $row['max_id'] + 1;
        $employee_id = "TECH" . str_pad($next_id, 3, '0', STR_PAD_LEFT); // Format: TECH001, TECH002, etc.

        // Insert new technician
        $stmt = $DatabaseCo->dbLink->prepare("INSERT INTO technician (employee_id, technician_name, email,dob, skills, contact, assigned_areas, availability_status, work_schedule, performance_metrics, log_date,address,experience) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssss", $employee_id, $technician_name, $email, $dob, $skills, $contact, $assigned_areas_str, $availability_status, $work_schedule, $performance_metrics, $log_date, $address, $experience);
        $stmt->execute();
        $d_id = $stmt->insert_id;
    }

    // Handle Image Upload
    $uploadimage = new ImageUploader($DatabaseCo);

    if (isset($_FILES['technician_image']) && is_uploaded_file($_FILES['technician_image']['tmp_name'])) {
        $photos = $uploadimage->upload($_FILES['technician_image'], "technician/technician_image");
        if ($photos) {
            $stmt = $DatabaseCo->dbLink->prepare("UPDATE technician SET technician_image=? WHERE id=?");
            $stmt->bind_param("si", $photos, $d_id);
            $stmt->execute();
        }
    }

    if (isset($_FILES['certifications']) && is_uploaded_file($_FILES['certifications']['tmp_name'])) {
        $certifications = $uploadimage->upload($_FILES['certifications'], "technician/certifications");
        if ($certifications) {
            $stmt = $DatabaseCo->dbLink->prepare("UPDATE technician SET certifications=? WHERE id=?");
            $stmt->bind_param("si", $certifications, $d_id);
            $stmt->execute();
        }
    }

    header("Location: technician.php");
    exit();
}


if (!empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
    $titl = "Update";
    $stmt = $DatabaseCo->dbLink->prepare("SELECT * FROM technician WHERE id=?");
    $stmt->bind_param("i", $_REQUEST['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $Row = $result->fetch_object();
} else {
    $titl = "Add New";
}
?>


<!-- Page Header -->
<div class="page-header">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title"><?php echo $titl; ?> Add Technician </h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="technician_add.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="technician .php"> Technician Listing</a></li>
                <li class="breadcrumb-item active"><?php echo $titl; ?> Add Technician </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Page Header -->

<div class="row">
    <div class="col-12">
        <div class="card">
        <div class=" mb-4">
                      <div class="card-header position-relative">
                        <h6 class="fs-17 fw-semi-bold mb-0">Location</h6>
                      </div>
                      <div class="card-body">
                        <div class="row g-4">
                          <div class="col-sm-6">
                            <!-- start form group -->
                            <div class="">
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
                            <!-- end /. form group -->
                          </div>
                          <div class="col-sm-6">
                            <!-- start form group -->
                            <div class="">
                              <label class="required fw-medium mb-2">State</label>
                              <?php
                              $options = ''; // Initialize an empty string for options

                              if (!empty($country_code)) {
                                $stateQuery = "SELECT * FROM states WHERE country_id = '$country_code' ORDER BY name";
                                $stateResult = mysqli_query($DatabaseCo->dbLink, $stateQuery);

                                if (!$stateResult) {
                                  die("Database query failed: " . mysqli_error($DatabaseCo->dbLink));
                                }

                                // Fetch all rows and build the options string
                                foreach (mysqli_fetch_all($stateResult, MYSQLI_ASSOC) as $stateRow) {
                                  $selected = ($stateRow['id'] == $Row->state) ? 'selected' : '';
                                  $options .= "<option value='{$stateRow['id']}' $selected>{$stateRow['name']}</option>";
                                }
                              }
                              ?>

                              <select class="form-control mb-3" name="state" id="state">
                                <option selected disabled>Select State</option>
                                <?php echo $options; // Output all options 
                                ?>
                              </select>





                            </div>
                            <!-- end /. form group -->
                          </div>
                          <div class="col-sm-6">
                            <!-- start form group -->
                            <div class="">
                              <label class="required fw-medium mb-2">City</label>
                              <?php
                              $options = ''; // Initialize an empty string for options

                              // Check if city and state codes are available
                              $currentCity = isset($Row->city) ? $Row->city : ''; // Current city ID
                              $stateCode = isset($Row->state_code) ? $Row->state_code : ''; // Current state code

                              // Only fetch cities if a state code is provided
                              if (!empty($currentCity)) {
                                $cityQuery = "SELECT * FROM cities ORDER BY name";
                                $cityResult = mysqli_query($DatabaseCo->dbLink, $cityQuery);

                                if (!$cityResult) {
                                  die("Database query failed: " . mysqli_error($DatabaseCo->dbLink));
                                }

                                // Fetch all rows and build the options string
                                foreach (mysqli_fetch_all($cityResult, MYSQLI_ASSOC) as $cityRow) {
                                  $selected = ($cityRow['city_id'] == $currentCity) ? 'selected' : '';
                                  $options .= "<option value='{$cityRow['city_id']}' $selected>{$cityRow['city_name']}</option>";
                                }
                              }
                              ?>

                              <select class="form-control mb-3" name="city" id="city">
                                <option selected disabled>Select City</option>
                                <?php echo $options; // Output all options 
                                ?>
                              </select>


                            </div>
                            <!-- end /. form group -->
                          </div>
                          <div class="col-sm-6">
                            <!-- start form group -->
                            <div class="">
                              <label class="required fw-medium mb-2">Address</label>
                              <input type="text" class="form-control" name="address" placeholder="Enter Address" required="" value="<?php echo $Row->address; ?>">
                            </div>
                            <!-- end /. form group -->
                          </div>
                        </div>

                      </div>
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
<script>
    $(document).ready(function() {
        $('#Assigned_Areas').select2({
            placeholder: "Select Assigned Areas",
            allowClear: true
        });
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

<script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBG-RZCzEuy7JMyMu4ykftt5ooRcCeqhKY&loading=async&libraries=places&callback=initMap&callback=initAutocomplete"></script>
<script type="text/javascript">
    const selectedCities = [];

    function initAutocomplete() {
        const map = new google.maps.Map(document.getElementById("map"), {
            center: {
                lat: -33.8688,
                lng: 151.2195
            },
            zoom: 13,
            mapTypeId: "roadmap",
        });

        const input3 = document.getElementById("pickup-input");
        const searchBox3 = new google.maps.places.SearchBox(input3);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input3);

        const input = document.getElementById("city-input");
        const searchResults = document.getElementById("search-results");
        const searchBox = new google.maps.places.SearchBox(input);

        searchBox.addListener('places_changed', function() {
            const places = searchBox.getPlaces();
            if (places.length === 0) return;

            places.forEach(place => {
                if (place.name && !selectedCities.includes(place.name)) {
                    addTag(place.name); // Add place to the tag list
                }
            });

            input.value = ''; // Clear the input field
        });

        // Hide the search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchResults.contains(e.target) && e.target !== input) {
                searchResults.style.display = 'none';
            }
        });
    }

    window.initAutocomplete = initAutocomplete;
</script>

    <script>
  // Load states when a country is selected
  $('#country').change(function() {
    let countryCode = $(this).val();
    console.log(countryCode);
    $.ajax({
      url: 'get_states.php',
      type: 'POST',
      data: {
        country_code: countryCode
      },
      success: function(response) {
        console.log(response);
        $('#state').html(response);
        $('#city').html('<option selected disabled>Select City</option>'); // Reset city dropdown
      }
    });
  });

  // Load cities when a state is selected
  $('#state').change(function() {
    let stateCode = $(this).val();
    $.ajax({
      url: 'get_cities.php',
      type: 'POST',
      data: {
        state_code: stateCode
      },
      success: function(response) {
        $('#city').html(response);
      }
    });
  });
</script>
