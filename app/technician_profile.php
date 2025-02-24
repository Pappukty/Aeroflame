<?php
include_once './includes/header.php';


// 1️⃣ Get Technician ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<div class='alert alert-danger text-center'>Technician ID is missing!</div>");
}

$technician_id = intval($_GET['id']); // Convert to integer for security

// 2️⃣ Fetch Technician Details from Database
$query = "SELECT * FROM technician WHERE id = ?";
$stmt = $DatabaseCo->dbLink->prepare($query);
$stmt->bind_param("i", $technician_id);
$stmt->execute();
$result = $stmt->get_result();

// 3️⃣ Check if the technician exists
if ($result->num_rows === 0) {
    die("<div class='alert alert-warning text-center'>Technician not found!</div>");
}

$row = $result->fetch_assoc();
?>

<style>
    body { background-color: #f4f4f4; }
    .profile-card {
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        transition: 0.3s;
    }
    .profile-header {
        position: relative;
        background: linear-gradient(to right, #0064ff, #254a62
);
        height: 220px;
        text-align: center;
        color: #fff;
        padding-top: 50px;
    }
    .profile-img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid #fff;
        position: absolute;
        bottom: -75px;
        left: 50%;
        transform: translateX(-50%);
    }
    .details-section { padding: 20px; margin-top: 70px; }
    .info-box {
        background: #fff8f0;
        border-left: 5px solid #ff7300;
        padding: 12px 15px;
        margin-bottom: 15px;
        border-radius: 5px;
        color: black;
    }
    .info-box strong{
        color: black;
    }
    .btn-custom {
        background-color: #ff7300;
        color: #fff;
        border-radius: 8px;
        padding: 10px 18px;
        font-size: 16px;
        transition: 0.3s;
    }
    .btn-custom:hover { background-color: #d95e00; }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="profile-card">
                <div class="profile-header">
                    <h2><?php echo htmlspecialchars($row['technician_name'] ); ?></h2>
                    <p><i class="fa fa-map-marker-alt"></i> <?php echo htmlspecialchars($row['address']); ?></p>
                    <img src="../uploads/technician/technician_image/<?php echo $row['technician_image'] ?>" alt="Technician Image" class="profile-img">


                </div>
                <div class="details-section">
                    <h5><span class="badge bg-<?php echo ($row['availability_status'] == 'Active') ? 'success' : 'danger'; ?>">
                        <?php echo htmlspecialchars($row['availability_status']); ?>
                    </span></h5>
                    <h5>🔧 Personal Details</h5>
                    <div class="info-box"><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></div>
                    <div class="info-box"><strong>Phone:</strong> <?php echo htmlspecialchars($row['contact']); ?></div>
                    <div class="info-box"><strong>Date of Birth:</strong> <?php echo htmlspecialchars($row['dob']); ?></div>
                    <div class="info-box"><strong>Experience:</strong> <?php echo htmlspecialchars($row['experience']); ?></div>

                    <h5>⚡ Professional Details</h5>
                    <div class="info-box"><strong>Skills:</strong> <?php echo htmlspecialchars($row['skills']); ?></div>
                    <div class="info-box"><strong>Service Areas:</strong> <?php echo htmlspecialchars($row['assigned_areas']); ?></div>
                    <div class="info-box"><strong>Certifications:</strong> <?php echo htmlspecialchars($row['certifications']); ?></div>
                    <div class="info-box"><strong>Emergency Services:</strong> 
                        <span class="badge bg-danger"><?php echo ($row['emergency_service'] == 1) ? "24/7 Available" : "Not Available"; ?></span>
                    </div>
                </div>

                <div class="text-center pb-3">
                    <?php if (!empty($row['resume'])) { ?>
                        <a href="uploads/<?php echo htmlspecialchars($row['resume']); ?>" class="btn btn-success" target="_blank">
                            <i class="fa fa-download"></i> Download Resume
                        </a>
                    <?php } ?>
                    <!-- <a href="edit_profile.php?id=<?php echo $technician_id; ?>" class="btn btn-custom">
                        <i class="fa fa-edit"></i> Edit Profile
                    </a> -->
                    <a href="technician.php" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once './includes/footer.php';
$stmt->close();
$conn->close();
?>
