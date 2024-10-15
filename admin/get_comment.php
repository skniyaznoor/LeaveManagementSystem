<?php
session_start();
include_once "../includes/dp.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $leave_request_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT comment_text FROM comments WHERE leave_request_id = ?");
    $stmt->bind_param("i", $leave_request_id);
    $stmt->execute();
    $stmt->bind_result($comment_text);
    
    if ($stmt->fetch()) {
        echo json_encode(['comment' => $comment_text]);
    } else {
        echo json_encode(['comment' => '']);
    }

    $stmt->close();
} else {
    echo json_encode(['comment' => '']);
}

$conn->close();
?>
