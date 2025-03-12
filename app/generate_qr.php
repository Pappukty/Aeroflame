<?php
include "phpqrcode/qrlib.php";

$PNG_TEMP_DIR = 'temp/';
if (!file_exists($PNG_TEMP_DIR)) {
    mkdir($PNG_TEMP_DIR, 0777, true);
}

if (isset($_POST['qr_data'])) {
    $data = $_POST['qr_data'];

    // Create a unique filename
    $filename = $PNG_TEMP_DIR . 'qr_' . md5($data) . '.png';

    // Generate QR code
    QRcode::png($data, $filename, QR_ECLEVEL_L, 5);

    // Return the generated QR code path
    echo $filename;
}


if (isset($_POST['qr_Barcode'])) {
    $data = $_POST['qr_Barcode'];

    // Create a unique filename
    $filename = $PNG_TEMP_DIR . 'qr_' . md5($data) . '.png';

    // Generate QR code
    QRcode::png($data, $filename, QR_ECLEVEL_L, 5);

    // Return the generated QR code path
    echo $filename;
}
?>
