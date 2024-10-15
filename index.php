<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'employee') {
        header('Location: user/dashboard.php');
    } elseif ($_SESSION['role'] === 'manager') {
        header('Location: templates/manager_dashboard.php');
    }
    exit();
}

?>


<?php include('templates/login_form.php'); ?>

