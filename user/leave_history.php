<?php
session_start();
include '../auth.php';
include_once "../includes/dp.php";

$rows_per_page = 9;

$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $rows_per_page; 

$stmt = $conn->prepare("
    SELECT lr.leave_type, lr.start_date, lr.end_date, lr.reason, lr.status, c.comment_text 
    FROM leave_requests lr 
    LEFT JOIN comments c ON lr.id = c.leave_request_id
    WHERE lr.user_id = ? 
    ORDER BY lr.start_date DESC 
    LIMIT ? OFFSET ?
");
$stmt->bind_param("iii", $_SESSION['user_id'], $rows_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
$leave_history = $result->fetch_all(MYSQLI_ASSOC);

$count_stmt = $conn->prepare("SELECT COUNT(*) FROM leave_requests WHERE user_id = ?");
$count_stmt->bind_param("i", $_SESSION['user_id']);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_rows = $count_result->fetch_array()[0];
$total_pages = ceil($total_rows / $rows_per_page);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave History</title>
    <link rel="stylesheet" href="../css/style_history.css">
</head>
<body>
<?php include "../includes/header.php"; ?>
    <div class="dashboard-control-manager">
        <div class="" style="width:50%; align-content: center;">
            <div class="dashbboard-manager">
                <div class="welcome-managers">
                    <img src="../img/history1.png" alt="" srcset="">
                    <h2>View all your submitted leave requests</h2>
                </div>
                <div class="view-request">
                    <a href="#targetDiv" id="scrollButton">Requests History</a>
                </div>
            </div>
        </div>
        <div class="" style="width:50%; align-content: center;">
            <div class="leave-request-image">
                <img src="../img/about-hero.webp" alt="Hero Image" style="width:550px;">
            </div>
        </div>
    </div>

    <div class="cards-title" id="targetDiv">
        <h3>Your Leave History<img id="request-icon" src="../img/history1.png" alt="" srcset="" style="width:35px;height:20px;margin-left:8px"></h3>
    </div>

    <div class="history-table">
        <table>
            <thead>
                <tr>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Reason</th>
                    <th>Manager's Comment</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($leave_history as $leave): ?>
                    <tr class="<?php 
                        echo ($leave['status'] === 'rejected') ? 'row-rejected' : 
                            (($leave['status'] === 'approved') ? 'row-approved' : 'row-pending'); ?>">
                        <td><?= $leave['leave_type'] ?></td>
                        <td><?= $leave['start_date'] ?></td>
                        <td><?= $leave['end_date'] ?></td>
                        <td><?= ucfirst($leave['status']) ?></td>
                        <td><?= $leave['reason'] ?></td>
                        <td><?= ($leave['comment_text']) ?: 'No Comment' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginations">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>" class="page-link" data-page="<?= $i ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>

    <?php include "../includes/footer.php"; ?>
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

        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = urlParams.get('page') || 1;

        const pageLinks = document.querySelectorAll('.page-link');

        pageLinks.forEach(link => {
            if (link.getAttribute('data-page') === currentPage) {
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>
