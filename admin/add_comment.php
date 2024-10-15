<?php
session_start();
include_once "../includes/dp.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leave_request_id = $_POST['leave_request_id'];
    $comment_text = $_POST['comment'];

    $stmt = $conn->prepare("SELECT id FROM comments WHERE leave_request_id = ?");
    $stmt->bind_param("i", $leave_request_id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->close();
        $stmt = $conn->prepare("UPDATE comments SET comment_text = ? WHERE leave_request_id = ?");
        $stmt->bind_param("si", $comment_text, $leave_request_id);
    } else {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO comments (leave_request_id, comment_text) VALUES (?, ?)");
        $stmt->bind_param("is", $leave_request_id, $comment_text);
    }

    if ($stmt->execute()) {
        echo "Comment added/updated successfully";
    } else {
        echo "Error: " . $stmt->error; 
    }

    $stmt->close();
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
