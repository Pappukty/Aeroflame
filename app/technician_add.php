<?php
include_once './includes/header.php';
include_once './class/fileUploader.php';
if ($_REQUEST['state_id']) {
    $state = $_REQUEST['state_id'];
} else {
    $state = "38";
}
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
    $country = $xssClean->clean_input($_REQUEST['country']);
    $state = $xssClean->clean_input($_REQUEST['state']);
    $area = $xssClean->clean_input($_REQUEST['assigned_areas']);

    $experience = $xssClean->clean_input($_REQUEST['experience']);
    $log_date = date("Y-m-d H:i:s");

    // Handle assigned areas as an array
    $assigned_areas = isset($_REQUEST['city']) ? $_REQUEST['city'] : [];
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
        $stmt = $DatabaseCo->dbLink->prepare("UPDATE technician SET technician_name=?,email=?,dob=?, skills=?, contact=?, city=?, availability_status=?, work_schedule=?, performance_metrics=?,address=?,country=?,state=?,experience=?,assigned_areas=? WHERE id=?");
        $stmt->bind_param("ssssssssssssssi", $technician_name, $email, $dob, $skills, $contact, $assigned_areas_str, $availability_status, $work_schedule, $performance_metrics, $address, $country, $state, $experience, $area, $d_id);
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
        $stmt = $DatabaseCo->dbLink->prepare("INSERT INTO technician (employee_id, technician_name, email,dob, skills, contact, city, availability_status, work_schedule, performance_metrics, log_date,address,country,state,experience,assigned_areas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssssssss", $employee_id, $technician_name, $email, $dob, $skills, $contact, $assigned_areas_str, $availability_status, $work_schedule, $performance_metrics, $log_date, $address, $country, $state, $experience, $area);
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
            <h3 class="page-title"><?php echo $titl; ?> Add Technician </h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="technician.php"> Technician Listing</a></li>
                <li class="breadcrumb-item active"><?php echo $titl; ?> Add Technician </li>
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
                <form action="" method="POST" enctype="multipart/form-data" class="needs-validation">

                    <!-- Row 1: Name & Employee ID -->
                    <div class="row mb-3">
                        <div class="col-md-6 col-12 mb-3">
                            <label class="form-label">Technician Image</label>
                            <input type="file" class="form-control" name="technician_image" value="<?php echo $Row->technician_image; ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="technician_name" class="form-label">Technician Name</label>
                            <input type="text" class="form-control" id="technician_name" name="technician_name" value="<?php echo $Row->technician_name; ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $Row->dob; ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $Row->email; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?php echo $Row->address; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="experience" class="form-label">Experience</label>
                            <input type="text" class="form-control" id="experience" name="experience" value="<?php echo $Row->experience; ?>" required>
                        </div>
                        <!-- Row 2: Certifications & Skills -->
                        <div class="col-md-6 col-12 mb-3">
                            <label class="form-label">Certifications</label>
                            <input type="file" class="form-control" name="certifications" value="<?php echo $Row->certifications; ?>">
                        </div>

                        <div class="col-md-6">
                            <label for="skills" class="form-label">Skills</label>
                            <input type="text" class="form-control" id="skills" name="skills" value="<?php echo $Row->skills; ?>">
                        </div>


                        <!-- Row 3: Contact & Assigned Areas -->

                        <div class="col-md-6">
                            <label for="contact" class="form-label">Contact</label>
                            <input type="tel" class="form-control" maxlength="10" id="contact" name="contact" value="<?php echo $Row->contact; ?>" required>
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
                        <div class="col-md-6">
                            <label for="assigned_areas" class="form-label">Area</label>
                            <input type="text" class="form-control" id="assigned_areas" name="assigned_areas" value="<?php echo $Row->assigned_areas; ?>">
                        </div>



                        <!-- Row 4: Availability Status & Work Schedule -->

                        <div class="col-md-6">
                            <label for="availability_status" class="form-label">Availability Status</label>
                            <select class="form-control" id="availability_status" name="availability_status">
                                <option value="Active" <?php echo (isset($Row->availability_status) && $Row->availability_status == "Active") ? "selected" : ""; ?>>Active</option>
                                <option value="Inactive" <?php echo (isset($Row->availability_status) && $Row->availability_status == "Inactive") ? "selected" : ""; ?>>Inactive</option>
                            </select>
                        </div>

                        <!-- <div class="col-md-6">
                            <label for="work_schedule" class="form-label">Work Schedule</label>
                            <input type="text" class="form-control" id="work_schedule" name="work_schedule" value="<?php echo $Row->work_schedule; ?>">
                        </div> -->


                        <!-- Row 5: Performance Metrics -->
                        <div class="mb-3 col-md-12">
                            <label for="performance_metrics" class="form-label">Performance Metrics</label>
                            <textarea
                                class="form-control"
                                id="performance_metrics"
                                name="performance_metrics"><?php echo $Row->performance_metrics; ?></textarea>

                        </div>

                        <!-- Submit & Cancel Buttons -->
                        <div class="text-center">
                            <input name="submit" type="submit" class="btn btn-primary" value="<?php echo $titl; ?>" />
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
<script>

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