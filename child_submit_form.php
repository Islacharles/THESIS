<?php
include 'connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . htmlspecialchars($conn->connect_error)]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $parent_id = trim($_POST['parent_id']);
    $child_name = trim($_POST['child_name']);
    $child_id = trim($_POST['child_id']);
    $child_teacher = trim($_POST['child_teacher']); // This will now hold the teacher's ID
    $child_age = trim($_POST['child_age']);
    $child_grade = trim($_POST['child_grade']);
    $child_section = trim($_POST['child_section']);
    $child_address = trim($_POST['child_address']);

    // Check if parent_id is set and valid
    if (empty($parent_id) || !is_numeric($parent_id)) {
        die(json_encode(['success' => false, 'message' => 'Invalid parent ID']));
    }

    $child_sql = "INSERT INTO child_acc (parent_id, child_name, child_id, child_teacher, child_age, child_grade, child_section, child_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($child_sql);

    if ($stmt === false) {
        die(json_encode(['success' => false, 'message' => "Database prepare failed: " . htmlspecialchars($conn->error)]));
    }

    // Adjust bind_param types
    $stmt->bind_param("ississss", $parent_id, $child_name, $child_id, $child_teacher, $child_age, $child_grade, $child_section, $child_address);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error inserting child information: ' . htmlspecialchars($stmt->error)]);
    }

    $stmt->close();
}

$conn->close();
?>
