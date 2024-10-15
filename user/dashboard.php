<?php
session_start();
include '../auth.php';
include_once "../includes/dp.php";

$stmt = $conn->prepare("SELECT vacation_balance, sick_balance, personal_balance FROM leave_balances WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($vacation_balance, $sick_balance, $personal_balance);
$stmt->fetch();
$stmt->close();

$total_leave_balance = $vacation_balance + $sick_balance + $personal_balance;

$stmt = $conn->prepare("
    SELECT lr.id, lr.leave_type, lr.start_date, lr.end_date, lr.reason, lr.status, c.comment_text 
    FROM leave_requests lr 
    LEFT JOIN comments c ON lr.id = c.leave_request_id
    WHERE lr.user_id = ? 
    ORDER BY lr.id DESC 
    LIMIT 5
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$leave_requests = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="../css/style_dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <?php include "../includes/header.php"; ?>

    <div class="dashboard-control-manager">
        <div class="" style="width:50%; align-content: center;text-align: center;">
            <div class="dashboard-manager">
                <div class="welcome-manager">
                <img src="../img/confirm1.png" alt="" srcset="">
                    <h2>Welcome, <br><?= htmlspecialchars($_SESSION['username']) ?></h2>
                </div>
                <div class="view-request">
                    <a href="#targetDiv" id="scrollButton">View Details</a>
                </div>
            </div>
        </div>
        <div class="" style="width:50%; align-content: center;">
            <div class="image-manager-header">
                <img src="../img/about-hero.webp" alt="Hero Image" style="width:550px;">
            </div>
        </div>
    </div> 

    <div class="cards-title-heading" id="targetDiv">
        <h3>Employee Dashboard<img id="request-icon" src="../img/employe.png" alt="" srcset="" style="width:20px;height:20px;margin-left:8px"></h3>
    </div>

    <div class="pie-chart-leave">
        <div>
            <div class="balance-chart">
                <h2>Your Leave Balances</h2>
                <ul>
                    <li>Vacation Balance: <?= htmlspecialchars($vacation_balance) ?> days</li>
                    <li>Sick Leave Balance: <?= htmlspecialchars($sick_balance) ?> days</li>
                    <li>Personal Leave Balance: <?= htmlspecialchars($personal_balance) ?> days</li>
                </ul>
            </div>
            <div class="request-link">
                <h3>Submit a New Leave Request</h3>
                <a id="leaveRequestLink" class="link-niyaz" href="../templates/leave_form.php">Submit Leave Request</a>
            </div>

            <div id="alertMessage" class="alert-popup" style="display:none;">
            <span class="close-btn">&times;</span>
                You cannot submit a leave request as your total leave balance is 0 or negative.
            </div>
        </div>

        <div class="balance-chart">
            <div class="canvas-control">
                <canvas id="leaveBalanceChart" width="400" height="400"></canvas>
            </div>
        </div>
    </div>
    

    <div class="table-history">
        <h3>Past Leave Requests<img id="request1-icon" src="../img/history.png" alt="" srcset="" style="width:20px;height:20px;margin-left:8px"></h3>
        <table>
            <thead>
                <tr>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Manager's Comment</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leave_requests as $request): ?>
                <tr>
                    <td><?= htmlspecialchars($request['leave_type']) ?></td>
                    <td><?= htmlspecialchars($request['start_date']) ?></td>
                    <td><?= htmlspecialchars($request['end_date']) ?></td>
                    <td><?= htmlspecialchars($request['reason']) ?></td>
                    <td class="<?php 
                        echo ($request['status'] === 'rejected') ? 'status-rejected' : 
                            (($request['status'] === 'approved') ? 'status-approved' : 'status-pending'); ?>">
                        <?= ucfirst($request['status']) ?>
                    </td>
                    <td><?= htmlspecialchars($request['comment_text']) ?: 'No comment' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="load-history-page">
        <a href="leave_history.php">Load More ...</a>
    </div>
    <?php include_once "../includes/footer.php"; ?> 
    <script>
        const scrollLink = document.querySelector('a[href="#targetDiv"]');
        scrollLink.addEventListener('click', function(event) {
        event.preventDefault(); 
        
        const targetDiv = document.getElementById('targetDiv');
        const targetDivPosition = targetDiv.getBoundingClientRect().top + window.scrollY; 
        
        window.scrollTo({
            top: targetDivPosition,
            behavior: 'smooth'
        });
        });

        const img = document.getElementById('request-icon');

        document.querySelector('.cards-title-heading h3').addEventListener('mouseenter', () => {
        img.src = '../img/employee1.png';
        });

        document.querySelector('.cards-title-heading h3').addEventListener('mouseleave', () => {
        img.src = '../img/employe.png';
        });

        const ctx = document.getElementById('leaveBalanceChart').getContext('2d');
            const leaveBalanceChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Vacation', 'Sick Leave', 'Personal Leave'],
                    datasets: [{
                        label: 'Leave Balances',
                        data: [<?= $vacation_balance ?>, <?= $sick_balance ?>, <?= $personal_balance ?>],
                        backgroundColor: ['#000080', '#D2B48C', ' #FF7F50'],
                        hoverBackgroundColor: ['#000080', '#D2B48C', ' #FF7F50']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            enabled: true,
                        },
                    },
                }
        });

        document.getElementById('leaveRequestLink').addEventListener('click', function(event) {
            const totalLeaveBalance = <?= $total_leave_balance ?>;
            const vacation_balance = <?= $vacation_balance ?>;
            const sick_balance = <?= $sick_balance ?>;
            const personal_balance = <?= $personal_balance ?>;

            if (totalLeaveBalance <= 0) {
                event.preventDefault();
                document.getElementById('alertMessage').style.display = 'block';
            }
            else if(vacation_balance <= 0 || personal_balance <= 0 || sick_balance <= 0){
                event.preventDefault();
                document.getElementById('alertMessage').style.display = 'block';
            }
            
        });

        document.querySelector('.alert-popup .close-btn').addEventListener('click', function() {
            document.getElementById('alertMessage').style.display = 'none';
        });

    </script>
</body>
</html>
