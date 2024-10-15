<?php
session_start();
include_once "../includes/dp.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $leave_request_id = $_POST['id'];
    $action = $_POST['action']; 
    $comment_text = $_POST['comment'];

    $stmt = $conn->prepare("SELECT user_id, leave_type, start_date, end_date FROM leave_requests WHERE id = ?");
    $stmt->bind_param("i", $leave_request_id);
    $stmt->execute();
    $stmt->bind_result($user_id, $leave_type, $start_date, $end_date);
    $stmt->fetch();
    $stmt->close();

    $start_date_obj = new DateTime($start_date);
    $end_date_obj = new DateTime($end_date);
    $leave_duration = $end_date_obj->diff($start_date_obj)->days + 1; 

    if ($action == 'approve') {
        $stmt = $conn->prepare("UPDATE leave_requests SET status = 'approved' WHERE id = ?");
        $stmt->bind_param("i", $leave_request_id);
        $stmt->execute();
        $stmt->close();

        switch ($leave_type) {
            case 'vacation':
                $stmt = $conn->prepare("UPDATE leave_balances SET vacation_balance = vacation_balance - ? WHERE user_id = ?");
                $stmt->bind_param("ii", $leave_duration, $user_id);
                break;
            case 'sick':
                $stmt = $conn->prepare("UPDATE leave_balances SET sick_balance = sick_balance - ? WHERE user_id = ?");
                $stmt->bind_param("ii", $leave_duration, $user_id);
                break;
            case 'personal':
                $stmt = $conn->prepare("UPDATE leave_balances SET personal_balance = personal_balance - ? WHERE user_id = ?");
                $stmt->bind_param("ii", $leave_duration, $user_id);
                break;
        }
        $stmt->execute();
        $stmt->close();

    } elseif ($action == 'reject') {
        $stmt = $conn->prepare("UPDATE leave_requests SET status = 'rejected' WHERE id = ?");
        $stmt->bind_param("i", $leave_request_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: view_requests.php");
    exit;
}
?>
