<?php
session_start();
include_once "../includes/dp.php";

// if ($_SESSION['role_id'] !== 'HR') {
if ($_SESSION['role'] !== 'manager') {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['vacation_balance'] as $user_id => $vacation_balance) {
        $sick_balance = $_POST['sick_balance'][$user_id];
        $personal_balance = $_POST['personal_balance'][$user_id];

        $stmt = $conn->prepare("
            UPDATE leave_balances 
            SET vacation_balance = ?, sick_balance = ?, personal_balance = ? 
            WHERE user_id = ?
        ");
        $stmt->bind_param("iiii", $vacation_balance, $sick_balance, $personal_balance, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: update_leave_balance.php");
    exit;
}
?>
