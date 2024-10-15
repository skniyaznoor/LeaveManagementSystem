<?php
session_start();
include '../auth.php';
include_once "../includes/dp.php";

$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

$results_per_page = 8;
$offset = ($current_page - 1) * $results_per_page;

$stmt = $conn->prepare("
    SELECT u.id, u.username, lb.vacation_balance, lb.sick_balance, lb.personal_balance 
    FROM users u 
    JOIN leave_balances lb ON u.id = lb.user_id
    WHERE u.username LIKE CONCAT('%', ?, '%')
    LIMIT ?, ?
");
$search_query_param = "%" . $search_query . "%";
$stmt->bind_param("sii", $search_query_param, $offset, $results_per_page);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$total_stmt = $conn->prepare("
    SELECT COUNT(*) AS total FROM users u WHERE u.username LIKE CONCAT('%', ? ,'%')
");
$total_stmt->bind_param("s", $search_query_param);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_users = $total_row['total'];
$total_stmt->close();

$total_pages = ceil($total_users / $results_per_page); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Leave Balances</title>
    <link rel="stylesheet" href="../css/style_update.css">
</head>
<body>
    <?php include "../includes/header.php"; ?>

    <div class="dashboard-control-manager">
        <div class="" style="width:50%; align-content: center;">
            <div class="dashbboard-manager">
                <div class="welcome-manager">
                    <img src="../img/update1.png" alt="" srcset="">
                    <h2>Update the leave balances</h2>
                </div>
                <div class="view-request">
                    <a href="#targetDiv" id="scrollButton">Update Leave</a>
                </div>
            </div>
        </div>
        <div class="" style="width:50%; align-content: center;">
            <div class="leave-request-image">
                <img src="../img/about-hero.webp" alt="Hero Image" style="width:550px;">
            </div>
        </div>
    </div>
    <div class="update-controls">

        <div class="search-title">
            <div class="cards-title-heading" id="targetDiv">
                <h3>Updates <img id="request-icon" src="../img/update.png" alt="" srcset="" style="width:20px;height:20px;margin-left:8px"></h3>
            </div>
        
            <form method="GET" action="">
                <input id="search-bar" type="text" name="search" value="<?= htmlspecialchars($search_query) ?>" placeholder="Search Username" />
                <input id="search-submit" type="submit" value="Search" />
            </form>
        </div>


        <div class="form-controls">
            <form action="process_leave_update.php" method="POST">
                <div class="table-update">
                    <table>
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Vacation Balance</th>
                                <th>Sick Balance</th>
                                <th>Personal Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><input type="number" name="vacation_balance[<?= $user['id'] ?>]" value="<?= htmlspecialchars($user['vacation_balance']) ?>"></td>
                                <td><input type="number" name="sick_balance[<?= $user['id'] ?>]" value="<?= htmlspecialchars($user['sick_balance']) ?>"></td>
                                <td><input type="number" name="personal_balance[<?= $user['id'] ?>]" value="<?= htmlspecialchars($user['personal_balance']) ?>"></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="update-btn">
                    <button type="submit">Update Leave Balances</button>
                </div>
            </form>
        </div>

        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($search_query) ?>" class="<?= $i === $current_page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>



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
        img.src = '../img/update1.png';
        });

        document.querySelector('.cards-title-heading h3').addEventListener('mouseleave', () => {
        img.src = '../img/update.png';
        });

        document.addEventListener('DOMContentLoaded', function () {
        const paginationLinks = document.querySelectorAll('.pagination a');

        paginationLinks.forEach(link => {
            link.addEventListener('click', function () {
                paginationLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });

        const currentPage = new URLSearchParams(window.location.search).get('page') || 1;
        paginationLinks.forEach(link => {
            if (link.getAttribute('href').includes(`page=${currentPage}`)) {
                link.classList.add('active');
            }
        });
    });
    </script>
</body>
</html>
