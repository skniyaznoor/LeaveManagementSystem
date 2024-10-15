<?php
// session_start();
include '../auth.php';

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username']; 
    $role_id = $_SESSION['role']; 
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Employee Management System</title>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="../img/hyscaler-logo.svg" alt="logo" />
            </div>
            <ul class="nav-links">
                <?php if ($_SESSION['role'] == 'employee'): ?>
                    <li class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
                        <a href="../user/dashboard.php">Dashboard</a>
                    </li>
                    <li class="<?= $current_page == 'leave_form.php' ? 'active' : '' ?>">
                        <a href="../templates/leave_form.php">Request</a>
                    </li>
                    <li class="<?= $current_page == 'leave_history.php' ? 'active' : '' ?>">
                        <a href="../user/leave_history.php">History</a>
                    </li>
                <?php elseif ($_SESSION['role'] == 'manager'): ?>
                    <li class="<?= $current_page == 'manager_dashboard.php' ? 'active' : '' ?>">
                        <a href="../templates/manager_dashboard.php">Manager Dashboard</a>
                    </li>
                    <li class="<?= $current_page == 'view_requests.php' ? 'active' : '' ?>">
                        <a href="../admin/view_requests.php">View Requests</a>
                    </li>
                    <li class="<?= $current_page == 'leave_calender.php' ? 'active' : '' ?>">
                        <a href="../calender/leave_calender.php">Calendar</a>
                    </li>
                    <li class="<?= $current_page == 'update_leave_balance.php' ? 'active' : '' ?>">
                        <a href="../update/update_leave_balance.php">Updates</a>
                    </li>
                <?php endif; ?>
                <li>
                    <form action="../logout.php" method="POST" style="display: inline">
                        <button type="submit" style="background: none; border: none">
                            <img src="../img/logout.png" alt="Logout" style="width: 25px; height: 25px; cursor: pointer" />
                        </button>
                    </form>
                </li>
            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <script>
        const hamburger = document.querySelector(".hamburger");
        const navLinks = document.querySelector(".nav-links");

        hamburger.addEventListener("click", () => {
            navLinks.classList.toggle("active");
        });
    </script>
</body>
</html>
