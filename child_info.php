<?php
include 'connection.php';

$parent_id = isset($_GET['parent_id']) ? intval($_GET['parent_id']) : 0;

if ($parent_id > 0) {
    $sql = "SELECT * FROM child_acc WHERE parent_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $parent_id);
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $children = [];
    while ($row = $result->fetch_assoc()) {
        $children[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($children);
} else {
    echo json_encode(["error" => "Invalid parent ID."]);
}
?>