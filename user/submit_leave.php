<?php
session_start();
include_once "../includes/dp.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("SELECT vacation_balance, sick_balance, personal_balance FROM leave_balances WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($vacation_balance, $sick_balance, $personal_balance);
    $stmt->fetch();
    $stmt->close();

    $start_date_obj = new DateTime($start_date);
    $end_date_obj = new DateTime($end_date);
    $leave_duration = $end_date_obj->diff($start_date_obj)->days + 1;

    if ($leave_type == 'vacation' && $vacation_balance < $leave_duration) {
        echo "<script>
                alert('You do not have enough vacation leave balance.');
                window.location.href = '../templates/leave_form.php';
              </script>";
        exit;
    } elseif ($leave_type == 'sick' && $sick_balance < $leave_duration) {
        if ($personal_balance >= $leave_duration) {
            echo "<script>
                    alert('You do not have enough sick leave balance. Consider using personal leave.');
                    window.location.href = '../templates/leave_form.php';
                  </script>";
        } else {
            echo "<script>
                    alert('You do not have enough sick or personal leave balance.');
                    window.location.href = '../templates/leave_form.php';
                  </script>";
        }
        exit;
    } elseif ($leave_type == 'personal' && $personal_balance < $leave_duration) {
        echo "<script>
                alert('You do not have enough personal leave balance.');
                window.location.href = '../templates/leave_form.php';
              </script>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO leave_requests (user_id, leave_type, start_date, end_date, reason, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("issss", $user_id, $leave_type, $start_date, $end_date, $reason);

    if ($stmt->execute()) {
        header('Location: dashboard.php');
    } else {
        echo "<script>
                alert('Error: " . $stmt->error . "');
                window.location.href = 'submit_leave.php';
              </script>";
    }

    $stmt->close();
}
?>
