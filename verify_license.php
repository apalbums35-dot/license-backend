<?php
header("Content-Type: application/json");

// ✅ Database config (Render पर env variables से भी set कर सकते हैं)
$host = "sql213.infinityfree.com";
$user = "if0_39337642";
$pass = "eVvKnsr7Kt2Lkf";
$db   = "if0_39337642_ap_reg";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["valid" => false, "message" => "DB connection failed"]);
    exit;
}

$key = $_GET['key'] ?? '';
$hid = $_GET['hid'] ?? '';

if (empty($key) || empty($hid)) {
    echo json_encode(["valid" => false, "message" => "Missing key or hardware ID"]);
    exit;
}

$stmt = $conn->prepare("SELECT Name, Mobile, Studio, ExpiryDate, IsActive 
                        FROM licenses 
                        WHERE LicenseKey=? AND HardwareId=? LIMIT 1");
$stmt->bind_param("ss", $key, $hid);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($row['IsActive'] == 1) {
        echo json_encode([
            "valid" => true,
            "user" => $row['Name'],
            "mobile" => $row['Mobile'],
            "studio" => $row['Studio'],
            "expiry" => $row['ExpiryDate']
        ]);
    } else {
        echo json_encode(["valid" => false, "message" => "License inactive"]);
    }
} else {
    echo json_encode(["valid" => false, "message" => "License not found"]);
}

$conn->close();
