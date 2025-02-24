<?php
include_once './app/class/databaseConn.php';
include_once './app/lib/requestHandler.php';
$DatabaseCo = new DatabaseConn();

error_reporting(E_ALL);
header('Content-Type: application/json'); // Ensure JSON response

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Required fields validation
  $requiredFields = [
    "consumer_id", "feedback_id", "consumer_customer", "consumer_number",
    "customer_name", "mobile_number", "google_url", "email", "social_media_agency",
    "address", "sr_number", "priority", "channel", "date_opened", "sr_state",
    "ldr", "type", "escalation_level", "escalation_date", "sr_district",
    "resolution_category", "status", "channel_reference_id", "response_date",
    "partner_name", "sub_category", "receiver_social_id", "assigned_to",
    "resolved_date", "external_party", "oate", "reverse_description", "resolution_remarks"
  ];

  foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
      echo json_encode(["status" => "error", "message" => "Error: $field is required."]);
      exit();
    }
  }

  // Collect input data
  $consumer_id = $_POST['consumer_id'];
  $feedback_id = $_POST['feedback_id'];
  $consumer_customer = $_POST['consumer_customer'];
  $consumer_number = $_POST['consumer_number'];
  $customer_name = $_POST['customer_name'];
  $mobile_number = $_POST['mobile_number'];
  $google_url = $_POST['google_url'];
  $email = $_POST['email'];
  $social_media_agency = $_POST['social_media_agency'];
  $address = $_POST['address'];
  $sr_number = $_POST['sr_number'];
  $priority = $_POST['priority'];
  $channel = $_POST['channel'];
  $date_opened = $_POST['date_opened'];
  $sr_state = $_POST['sr_state'];
  $ldr = $_POST['ldr'];
  $type = $_POST['type'];
  $escalation_level = $_POST['escalation_level'];
  $escalation_date = $_POST['escalation_date'];
  $sr_district = $_POST['sr_district'];
  $resolution_category = $_POST['resolution_category'];
  $status = $_POST['status'];
  $channel_reference_id = $_POST['channel_reference_id'];
  $response_date = $_POST['response_date'];
  $partner_name = $_POST['partner_name'];
  $sub_category = $_POST['sub_category'];
  $receiver_social_id = $_POST['receiver_social_id'];
  $assigned_to = $_POST['assigned_to'];
  $resolved_date = $_POST['resolved_date'];
  $external_party = $_POST['external_party'];
  $oate = $_POST['oate'];
  $complaint = isset($_POST['complaint']) ? 1 : 0;
  $redirected_grievance_center = isset($_POST['redirected_grievance_center']) ? 1 : 0;
  $vigilance_flag = isset($_POST['vigilance_flag']) ? 1 : 0;
  $mdg = isset($_POST['mdg']) ? 1 : 0;
  $reverse_description = $_POST['reverse_description'];
  $resolution_remarks = $_POST['resolution_remarks'];
  $status_process = 'pending';

  // Insert Query
  $query = "INSERT INTO resolve (
        consumer_id, feedback_id, consumer_customer, consumer_number, customer_name,
        mobile_number, google_url, email, social_media_agency, address, sr_number,
        priority, channel, date_opened, sr_state, ldr, type, escalation_level,
        escalation_date, sr_district, resolution_category, status, channel_reference_id,
        response_date, partner_name, sub_category, receiver_social_id, assigned_to,
        resolved_date, external_party, status_process, oate, complaint,
        redirected_grievance_center, vigilance_flag, mdg, reverse_description, resolution_remarks
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  $stmt = $DatabaseCo->dbLink->prepare($query);

  if ($stmt === false) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $DatabaseCo->dbLink->error]);
    exit();
  }

  $stmt->bind_param(
    "ssssssssssssssssssssssssssssssssssssss",
    $consumer_id, $feedback_id, $consumer_customer, $consumer_number, $customer_name,
    $mobile_number, $google_url, $email, $social_media_agency, $address, $sr_number,
    $priority, $channel, $date_opened, $sr_state, $ldr, $type, $escalation_level,
    $escalation_date, $sr_district, $resolution_category, $status, $channel_reference_id,
    $response_date, $partner_name, $sub_category, $receiver_social_id, $assigned_to,
    $resolved_date, $external_party, $status_process, $oate, $complaint,
    $redirected_grievance_center, $vigilance_flag, $mdg, $reverse_description, $resolution_remarks
  );

  if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Form submitted successfully!"]);
  } else {
    echo json_encode(["status" => "error", "message" => "Error inserting data: " . $stmt->error]);
  }

  $stmt->close();
}
?>
