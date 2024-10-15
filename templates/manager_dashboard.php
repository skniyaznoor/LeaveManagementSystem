<?php
session_start();
include '../auth.php';
include_once "../includes/dp.php";

$stmtt = $conn->prepare(
    "SELECT lr.*, u.username 
     FROM leave_requests lr 
     JOIN users u ON lr.user_id = u.id 
     WHERE lr.status = 'approved' 
     ORDER BY lr.start_date DESC"
);
$stmtt->execute();
$resultt = $stmtt->get_result();
$approved_leaves = $resultt->fetch_all(MYSQLI_ASSOC);

$grouped_leaves = [];
foreach ($approved_leaves as $leave) {
    $grouped_leaves[$leave['username']][] = $leave;
}

$employees_to_display = array_slice($grouped_leaves, 0, 5, true);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include_once "../includes/header.php"; ?>  

    <div class="dashboard-control-manager">
        <div class="" style="width:50%; align-content: center;">
            <div class="dashboard-manager">
                <div class="welcome-manager">
                    <h2>Welcome, <br><?= htmlspecialchars($_SESSION['username']) ?></h2>
                </div>
                <div class="view-request">
                    <a href="../admin/view_requests.php">View Requests</a>
                </div>
            </div>
        </div>
        <div class="" style="width:50%; align-content: center;">
            <div class="image-manager-header">
                <img src="../img/about-hero.webp" alt="Hero Image" style="width:550px;">
            </div>
        </div>
    </div> 

    <div class="calendar-body-record">
        <div class="calendar-table">
            <h1>Approved Leave Calendar</h1>
            <h3>The table below displays the employees whose leaves have been approved,
                 along with detailed information on the leave type and duration. 
                 Click on an employee's name to view multiple leave records if applicable.
            </h3>

            <div class="table-container">
                <table style="border-radius:7px;">
                    <thead>
                        <tr>
                            <th>
                                <img src="../img/employee1.webp" alt="" style="width:30px; margin-right:7px">
                                Employee
                            </th>
                            <th>
                                <img src="../img/suitcase.png" alt="" style="width:30px; margin-right:7px">
                                Leave Details
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees_to_display as $username => $leaves): ?>
                            <tr class="employee-row" onclick="toggleDetails('<?= $username ?>')">
                                <td><h4><?= htmlspecialchars($username) ?></h4></td>
                                <td><button class="toggle-button">View Leaves</button></td>
                            </tr>
                            <tr id="details-<?= $username ?>" class="leave-details hidden">
                                <td colspan="2">
                                    <table class="inner-table">
                                        <thead>
                                            <tr>
                                                <th>Leave Type</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $latest_leaves = array_slice($leaves, 0, 3);
                                            foreach ($latest_leaves as $leave): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars(ucfirst($leave['leave_type'])) ?></td>
                                                    <td><?= htmlspecialchars(date('d M Y', strtotime($leave['start_date']))) ?></td>
                                                    <td><?= htmlspecialchars(date('d M Y', strtotime($leave['end_date']))) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>


        </div>
        <div class="load-more">
            <a href="../calender/leave_calender.php">Load More</a>
        </div>
    </div>

    <?php include_once "../includes/footer.php"; ?> 

    <script>
        // function toggleDetails(username) {
        //     const detailsRow = document.getElementById(`details-${username}`);
        //     detailsRow.classList.toggle('hidden');
        // }

        function toggleDetails(username) {
            const detailsRow = document.getElementById(`details-${username}`);
            const clickedEmployeeRow = detailsRow.previousElementSibling;
            const allEmployeeRows = document.querySelectorAll('.employee-row');
            const allLeaveDetails = document.querySelectorAll('.leave-details');

            const isAlreadyVisible = detailsRow.classList.contains('visible');

            allLeaveDetails.forEach(row => row.classList.add('hidden'));
            allEmployeeRows.forEach(row => row.classList.remove('faded', 'active'));

            if (!isAlreadyVisible) {
                detailsRow.classList.remove('hidden');
                detailsRow.classList.add('visible');
                clickedEmployeeRow.classList.add('active');

                allEmployeeRows.forEach(row => {
                    if (row !== clickedEmployeeRow) row.classList.add('faded');
                });
            } else {
                detailsRow.classList.remove('visible');
                clickedEmployeeRow.classList.remove('active');
            }
        }

    </script>
</body>
</html>

