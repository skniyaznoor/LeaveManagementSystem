<?php
session_start();
include '../auth.php';
include_once "includes/dp.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT users.id, users.username, users.password, roles.role_name 
         FROM users 
         JOIN roles ON users.role_id = roles.id 
         WHERE users.username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role_name'];

        if ($user['role_name'] === 'employee') {
            header('Location: user/dashboard.php');
        } elseif ($user['role_name'] === 'manager') {
            header('Location: templates/manager_dashboard.php');
        }
        exit();
    } else {
        $error = 'Invalid username or password';
        header('Location: index.php?error=' . urlencode($error));
        exit();
    }
}
?>
