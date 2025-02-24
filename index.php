<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title> Aeroflame Customer Tickets </title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <!--favicon-->
  <link rel="icon" href="./images/logo.png" type="image/x-icon">
  <!-- Toastr CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Toastr JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  
  <style>
    /* General Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Background with Gradient */
    body {
      background: linear-gradient(135deg, #667eea, #764ba2);
      height: 100vh;
    }

    /* Form Container */
    .form-container {
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
      max-width: 80%;
      margin: 50px auto;
    }

    .form-container h2 {
      margin-bottom: 20px;
      font-size: 24px;
    }

    /* Button */
    .form-container button {
      width: 100%;
      padding: 10px;
      background: #ff8c00;
      border: none;
      border-radius: 5px;
      color: white;
      font-size: 18px;
      cursor: pointer;
      transition: 0.3s;
    }

    .form-container button:hover {
      background: #e07b00;
    }

    /* Responsive */
    @media (max-width: 400px) {
      .form-container {
        width: 90%;
      }
    }

    .navbar,
    .footer {
      background-color: white;
      color: black;
    }

    .navbar a {
      color: black;
    }

    .footer {
      text-align: center;
      padding: 15px 0;
      font-size: 14px;
    }

    .form-container {
      background-color: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
      margin-top: 50px;
      margin-bottom: 50px;
    }

    h3,
    h5 {
      color: #4a90e2;
      margin-bottom: 20px;
      font-weight: 600;
    }

    .form-label {
      font-weight: 500;
      font-size: 14px;
    }

    .form-control,
    .form-select {
      border-radius: 8px;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #d1d9e6;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: #4a90e2;
      box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
    }

    .btn-primary {
      background-color: #4a90e2;
      border-color: #4a90e2;
      padding: 12px 25px;
      font-size: 16px;
      border-radius: 8px;
    }

    .btn-primary:hover {
      background-color: #357abd;
      border-color: #357abd;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-container .row {
      display: flex;
      justify-content: center;
    }

    .form-container .col-md-6 {
      max-width: 100%;
    }

    /* Header styling */
    .navbar {
      background-color: white;
      text-align: center;
      padding: 15px 0;
      z-index: 1000;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
      font-size: 24px;
      font-weight: bold;
      text-transform: uppercase;
      color: #4a90e2;
      letter-spacing: 2px;
      display: flex;
      align-items: center;
    }

    .logo {
      max-height: 50px;
      width: auto;
    }

    @media (max-width: 768px) {
      .navbar-brand {
        font-size: 20px;
        letter-spacing: 1px;
      }

      .logo {
        max-height: 40px;
      }
    }

    @media (max-width: 768px) {
      .form-container {
        padding: 20px;
      }

      .footer p {
        font-size: 12px;
      }
    }

    .social-icon a {
      font-size: 20px;
      color: #333;
      transition: color 0.3s ease-in-out;
    }

    .social-icon a:hover {
      color: #007bff;
    }
    .toast-success {
      background-color: #28a745; /* Green for success */
    }

    .toast-error {
      background-color: #dc3545; /* Red for error */
    }

  </style>
</head>

<body>
  <!-- Header -->
  <nav class="navbar navbar-expand-lg navbar-light">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="./images/logo.png" alt="Logo" class="logo img-fluid">
      <span class="mx-3">Customer Tickets Form</span>
    </a>
    <div class="social-icon d-flex align-items-center">
      <a href="#" class="me-3"><i class="fab fa-facebook-f"></i></a>
      <a href="#" class="me-3"><i class="fab fa-twitter"></i></a>
      
    </div>
  </div>
</nav>



  <!-- Main Form Container -->
  <div class="container form-container">
    <h3 class="text-center">Ticket Request </h3>
    <form  id="feedbackForm" class="">
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
             />
        </div>
        <div class="col-md-6">
          <label for="feedbackId" class="form-label">Feedback ID</label>
          <input
            type="text"
            class="form-control"
            id="feedbackId"
            name="feedback_id" />
        </div>
        <div class="col-md-6">
          <label for="consumerDisabledName" class="form-label">Consumer/Customer Disabled Name</label>
          <input
            type="text"
            class="form-control"
            id="consumerDisabledName"
            name="consumer_customer" />
        </div>
        <div class="col-md-6">
          <label for="consumerNumber" class="form-label">Consumer Number</label>
          <input
            type="text"
            class="form-control"
            id="consumerNumber"
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
            name="customer_name" />
        </div>
        <div class="col-md-6">
          <label for="mobileNumber" class="form-label">Mobile Number</label>
          <input
            type="text"
            class="form-control"
            id="mobileNumber"
            name="mobile_number" />
        </div>
        <div class="col-md-6">
          <label for="googleUrl" class="form-label">Google URL</label>
          <input
            type="url"
            class="form-control"
            id="googleUrl"
            name="google_url" />
        </div>
        <div class="col-md-6">
          <label for="emailAddress" class="form-label">Email Address</label>
          <input
            type="email"
            class="form-control"
            id="emailAddress"
            name="email" />
        </div>
        <div class="col-md-6">
          <label for="socialMediaAgency" class="form-label">Social Media Agency</label>
          <input
            type="text"
            class="form-control"
            id="socialMediaAgency"
            name="social_media_agency" />
        </div>
        <div class="col-md-6">
          <label for="address" class="form-label">Address</label>
          <input
            type="text"
            class="form-control"
            id="address"
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
            id="srNumber"
            name="sr_number" />
        </div>
        <div class="col-md-6">
          <label for="priority" class="form-label">Priority</label>
          <select class="form-select" id="priority" name="priority" >
            <option selected>Choose...</option>
            <option value="high">High</option>
            <option value="medium">Medium</option>
            <option value="low">Low</option>
          </select>
        </div>
        <div class="col-md-6">
          <label for="channel" class="form-label">Channel</label>
          <select class="form-select" id="channel" name="channel" >
            <option selected>Choose...</option>
            <option value="Portal">Portal</option>
            <option value="Email">Email</option>
            <option value="Phone">Phone</option>
          </select>
        </div>
        <div class="col-md-6">
          <label for="dateOpened" class="form-label">Date Opened</label>
          <input
            type="date"
            class="form-control"
            id="dateOpened"
            name="date_opened"  />
        </div>
        <div class="col-md-6">
          <label for="srState" class="form-label">SR State</label>
          <select class="form-select" id="srState" name="sr_state" >
            <option selected>Choose...</option>
            <option value="Tamil Nadu">Tamil Nadu</option>
            <option value="Kerala">Kerala</option>
            <option value="Karnataka">Karnataka</option>
          </select>
        </div>

        <div class="col-md-6">
          <label for="ldr" class="form-label">LDR (if applicable)</label>
          <select class="form-select" id="ldr" name="ldr" >
            <option selected>Choose...</option>
            <option value="LPG">LPG</option>
            <option value="CNG">CNG</option>
          </select>
        </div>

        <div class="col-md-6">
          <label for="type" class="form-label">Type</label>
          <select class="form-select" id="type" name="type" >
            <option selected>Choose...</option>
            <option value="incident">Incident</option>
            <option value="request">Request</option>
          </select>
        </div>

        <div class="col-md-6">
          <label for="escalationLevel" class="form-label">Escalation Level</label>
          <select
            class="form-select"
            id="escalationLevel"
            name="escalation_level" >
            <option selected>Choose...</option>
            <option value="level1">Level 1</option>
            <option value="level2">Level 2</option>
            <option value="level3">Level 3</option>
          </select>
        </div>

        <div class="col-md-6">
          <label for="escalationDate" class="form-label">Escalation Date</label>
          <input
            type="date"
            class="form-control"
            id="escalationDate"
            name="escalation_date" />
        </div>

        <div class="col-md-6">
          <label for="srDistrict" class="form-label">SR District</label>
          <select class="form-select" id="srDistrict" name="sr_district" >
            <option selected>Choose...</option>
            <option value="chennai">Chennai</option>
            <option value="madurai">Madurai</option>
          </select>
        </div>

        <div class="col-md-6">
          <label for="resolutionCategory" class="form-label">Resolution Category</label>
          <select
            class="form-select"
            id="resolutionCategory"
            name="resolution_category" >
            <option selected>Choose...</option>
            <option value="resolved">Resolved</option>
            <option value="pending">Pending</option>
          </select>
        </div>

        <div class="col-md-6">
          <label for="status" class="form-label">Status</label>
          <select class="form-select" id="status" name="status" >
            <option selected>Choose...</option>
            <option value="received">Received</option>
            <option value="in-progress">In Progress</option>
          </select>
        </div>

        <div class="col-md-6">
          <label for="channelReferenceId" class="form-label">Channel Reference ID</label>
          <input
            type="text"
            class="form-control"
            id="channelReferenceId"
            name="channel_reference_id"  />
        </div>

        <div class="col-md-6">
          <label for="responseDate" class="form-label">Response Date</label>
          <input
            type="date"
            class="form-control"
            id="responseDate"
            name="response_date" />
        </div>

        <div class="col-md-6">
          <label for="partnerName" class="form-label">Partner Name</label>
          <input
            type="text"
            class="form-control"
            id="partnerName"
            name="partner_name" />
        </div>

        <div class="col-md-6">
          <label for="subCategory" class="form-label">Sub-Category</label>
          <select class="form-select" id="subCategory" name="sub_category" required>
            <option selected>Choose...</option>
            <option value="technical">Technical</option>
            <option value="customer-Tickets ">Customer Tickets </option>
          </select>
        </div>

        <div class="col-md-6">
          <label for="receiverSocialId" class="form-label">Receiver Social ID</label>
          <input
            type="text"
            class="form-control"
            id="receiverSocialId"
            name="receiver_social_id" />
        </div>

        <div class="col-md-6">
          <label for="assignedTo" class="form-label">Assigned To</label>
          <input
            type="text"
            class="form-control"
            id="assignedTo"
            name="assigned_to"  />
        </div>

        <div class="col-md-6">
          <label for="resolvedDate" class="form-label">Resolved Date</label>
          <input
            type="date"
            class="form-control"
            id="resolvedDate"
            name="resolved_date" />
        </div>

        <div class="col-md-6">
          <label for="externalParty" class="form-label">External Party</label>
          <select class="form-select" id="externalParty" name="external_party" >
            <option selected>Choose...</option>
            <option value="none">None</option>
            <option value="vendor">Vendor</option>
          </select>
        </div>

        <div class="col-md-6">
          <label for="oate" class="form-label">Tickets </label>
          <input
            type="text"
            class="form-control"
            id="oate"
            name="oate" />
        </div>

        <div class="col-md-6">

        </div>
        <div class="col-md-3">
          <label for="complaintEstablished" class="form-label">Complaint Established</label>
          <input
            type="checkbox"
            class="form-check-input p-2"
            id="complaintEstablished"
            name="complaint" />
        </div>
        <div class="col-md-3">
          <label for="redirectedGrievance" class="form-label">Redirected Grievance</label>
          <input
            type="checkbox"
            class="form-check-input p-2"
            id="redirectedGrievance"
            name="redirected_grievance_center" />
        </div>

        <div class="col-md-3">
          <label for="vigilanceFlag" class="form-label">Vigilance Flag</label>
          <input
            type="checkbox"
            class="form-check-input p-2"
            id="vigilanceFlag"
            name="vigilance_flag	" />
        </div>
        <div class="col-md-3">
          <label for="alternativeUnderMDG" class="form-label">Is Alternative Under MDG</label>
          <input
            type="checkbox"
            class="form-check-input p-2"
            id="alternativeUnderMDG"
            name="mdg" />
        </div>
        <div class="col-md-12">
          <label for="description" class="form-label">Description</label>
          <textarea
            class="form-control"
            id="description"
            name="reverse_description"></textarea>
        </div>
        <div class="col-md-12">
          <label for="resolutionRemarks" class="form-label">Resolution Remarks</label>
          <textarea
            class="form-control"
            id="resolutionRemarks"
            name="resolution_remarks"></textarea>
        </div>
      </div>
      <!-- Submit Button -->
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>&copy; 2025 Aeroflame . All rights reserved.</p>
  </div>
  <script>
 $(document).ready(function() {
    $("#feedbackForm").submit(function(e) {
        e.preventDefault();

        // Check if any input fields are empty
        var isValid = true;
        $(".form-control").each(function() {
            if ($(this).val().trim() === "") {
                isValid = false;
                $(this).css("border-color", "#ff0000"); // Highlight empty fields in red
            } else {
                $(this).css("border-color", "#d1d9e6"); // Reset to original color
            }
        });

        // If any field is empty, show toastr error message
        if (!isValid) {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                showMethod: "fadeIn",
                hideMethod: "fadeOut",
                timeOut: 3000, // 3 seconds
                positionClass: "toast-top-right"
            };
            toastr.error("Please fill all the required fields.");
            return; // Stop form submission if validation fails
        }

        var formData = $(this).serialize();

        $.ajax({
            type: "POST",
            url: "process.php",
            data: formData,
            dataType: "json",
            success: function(response) {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: "fadeIn",
                    hideMethod: "fadeOut",
                    timeOut: 3000, // 3 seconds
                    positionClass: "toast-top-right"
                };

                if (response.status === "success") {
                    toastr.success("Tickets Successfully Submitted!");
                    $("#feedbackForm")[0].reset();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: "fadeIn",
                    hideMethod: "fadeOut",
                    timeOut: 3000,
                    positionClass: "toast-top-right"
                };
                toastr.error("An error occurred while processing your request.");
            }
        });
    });
});


    </script>


  
 <!-- jQuery (Required for Toastr) -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>