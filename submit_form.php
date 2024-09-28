<?php
include 'connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . htmlspecialchars($conn->connect_error)]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $contact_number = trim($_POST['contact_number']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash the password

    $parent_sql = "INSERT INTO parent_acc (fullname, contact_number, email, address, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($parent_sql);

    if ($stmt === false) {
        die(json_encode(['success' => false, 'message' => 'Database prepare failed: ' . htmlspecialchars($conn->error)]));
    }

    $stmt->bind_param("sssss", $fullname, $contact_number, $email, $address, $password);

    if ($stmt->execute()) {
        $parent_id = $stmt->insert_id;
        echo json_encode(['success' => true, 'parent_id' => $parent_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error inserting parent information: ' . htmlspecialchars($stmt->error)]);
    }

    $stmt->close();
}

$conn->close();
?>
