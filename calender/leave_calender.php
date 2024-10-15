<?php
session_start();
include '../auth.php';
include_once "../includes/dp.php";

$stmt = $conn->prepare("SELECT lr.*, u.username FROM leave_requests lr JOIN users u ON lr.user_id = u.id WHERE lr.status = 'approved'");
$stmt->execute();
$result = $stmt->get_result(); 
$approved_leaves = $result->fetch_all(MYSQLI_ASSOC);

$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

$employees = [];
foreach ($approved_leaves as $leave) {
    $employees[$leave['username']] = $leave['leave_type'];
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$rows_per_page = 9; 
$offset = ($page - 1) * $rows_per_page;

$sql = "
    SELECT lr.*, u.username, c.comment_text 
    FROM leave_requests lr
    JOIN users u ON lr.user_id = u.id
    LEFT JOIN comments c ON lr.id = c.leave_request_id
    WHERE lr.status = 'approved'
";

if ($filter) {
    $sql .= " AND lr.leave_type = ?";
}

if ($search) {
    $sql .= " AND (u.username LIKE ? OR lr.reason LIKE ?)";
}

$sql .= " LIMIT ? OFFSET ?";

$stmtt = $conn->prepare($sql);

if ($filter && $search) {
    $search_term = "%$search%";
    $stmtt->bind_param("sssii", $filter, $search_term, $search_term, $rows_per_page, $offset);
} elseif ($filter) {
    $stmtt->bind_param("sii", $filter, $rows_per_page, $offset);
} elseif ($search) {
    $search_term = "%$search%";
    $stmtt->bind_param("ssii", $search_term, $search_term, $rows_per_page, $offset);
} else {
    $stmtt->bind_param("ii", $rows_per_page, $offset);
}

$stmtt->execute();
$resultt = $stmtt->get_result();
$approved_leaves_calender = $resultt->fetch_all(MYSQLI_ASSOC);

$total_sql = "
    SELECT COUNT(*) FROM leave_requests lr
    JOIN users u ON lr.user_id = u.id
    WHERE lr.status = 'approved'
";

if ($filter) {
    $total_sql .= " AND lr.leave_type = '$filter'";
}

if ($search) {
    $total_sql .= " AND (u.username LIKE '%$search%' OR lr.reason LIKE '%$search%')";
}

$total_result = $conn->query($total_sql);
$total_rows = $total_result->fetch_row()[0];
$total_pages = ceil($total_rows / $rows_per_page);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Calendar</title>
    <link rel="stylesheet" href="../css/style_calender.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include "../includes/header.php"; ?>

    <div class="dashboard-control-manager">
        <div class="" style="width:50%; align-content: center;">
            <div class="dashbboard-manager">
                <div class="welcome-manager">
                    <img src="../img/calendar1.png" alt="" srcset="">
                    <h2>View all the granded leaves</h2>
                </div>
                <div class="view-request">
                    <a href="#targetDiv" id="scrollButton">Leave Calendar</a>
                </div>
            </div>
        </div>
        <div class="" style="width:50%; align-content: center;">
            <div class="leave-request-image">
                <img src="../img/about-hero.webp" alt="Hero Image" style="width:550px;">
            </div>
        </div>
    </div>

    <div class="keep-all-contain">
        <div class="controls-container">
            <div class="cards-title-heading" id="targetDiv">
                <h3>Requests <img id="request-icon" src="../img/track.png" alt="" srcset="" style="width:20px;height:20px;margin-left:8px"></h3>
            </div>
            <form method="GET" action="">
                <label for="year">Year:</label>
                <select name="year" id="year">
                    <?php for ($y = 2020; $y <= 2030; $y++): ?>
                        <option value="<?= $y ?>" <?= ($y == $year) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>

                <label for="month">Month:</label>
                <select name="month" id="month">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= ($m == $month) ? 'selected' : '' ?>>
                            <?= date("F", mktime(0, 0, 0, $m, 10)) ?>
                        </option>
                    <?php endfor; ?>
                </select>

                <button type="submit">Update</button>
            </form>
        </div>
        <div class="calender-flex">
            <div class="calendar-container">
                <div class="calendar-grid">
                    <?php
                        for ($day = 1; $day <= $days_in_month; $day++):
                            $leave_count = 0;
                            $leave_html = '';
                        
                            foreach ($approved_leaves as $leave):
                                $leave_start = new DateTime($leave['start_date']);
                                $leave_end = new DateTime($leave['end_date']);
                                $current_date = new DateTime("$year-$month-$day");
                        
                                if ($current_date >= $leave_start && $current_date <= $leave_end):
                                    $leave_count++;
                        
                                    $leave_color = "#FF5722";
                                    if ($leave['leave_type'] == 'vacation') {
                                        $leave_color = "#4CAF50";
                                    } elseif ($leave['leave_type'] == 'personal') {
                                        $leave_color = "#FFC107";
                                    } elseif ($leave['leave_type'] == 'sick') {
                                        $leave_color = "#2196F3";
                                    }
                        
                                    $leave_html .= '<div class="employee-leave" style="background-color: ' . $leave_color . ';" title="' . $leave['username'] . ' - ' . $leave['leave_type'] . '"></div>';
                                endif;
                            endforeach;
                        
                            $day_class = ($leave_count > 7) ? 'day red-background' : 'day';
                        
                            echo '<div class="' . $day_class . '" onmouseenter="expandDay(this)" onmouseleave="collapseDay(this)" data-leave-html="' . htmlspecialchars($leave_html) . '">';
                            echo '<div class="date-number">' . $day . '</div>';
                            echo '<div class="employee-leave-container">';
                            
                            if ($leave_count <= 7) {
                                echo $leave_html;
                            }
                        
                            echo '</div>';
                            echo '</div>';
                        endfor;
                    
                    ?>
                </div>
            </div>

            <div class="legend-container">
                <div class="inner-legend-container">
                    <h2>Employee Leave Color Legend</h2>
                    <ul>
                        <?php 
                        $leave_types = [
                            'vacation' => '#4CAF50', 
                            'personal' => '#FFC107', 
                            'sick'     => '#2196F3', 
                        ];

                        foreach ($leave_types as $type => $color): 
                        ?>
                            <li>
                                <span class="legend-color" style="background-color: <?= $color ?>;"></span>
                                <?= ucfirst($type) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php
                $leave_types = [
                    'vacation' => '#4CAF50',
                    'personal' => '#FFC107',
                    'sick'     => '#2196F3',
                ];

                $leave_counts = [
                    'vacation' => 0,
                    'personal' => 0,
                    'sick'     => 0,
                ];

                foreach ($approved_leaves as $leave) {
                    $leave_counts[$leave['leave_type']]++;
                }
                ?>
                <div class="leave-chart">
                    <canvas id="leaveChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="table-display-record">
        <div class="form-and-title">
            <div class="view-request">
                <h3><a href="../calender/leave_calender.php">Approved Leave Calendar</a></h3>
            </div>
            <form method="GET" action="" class="filter-search-form">
                <img src="../img/search1.png" alt="" srcset="" style="width:30px;">
                <input type="text" name="search" placeholder="Search by employee or reason" value="<?= htmlspecialchars($search) ?>">

                <label for="filter">Filter by Leave Type:</label>
                <select name="filter" id="filter">
                    <option value="">All</option>
                    <option value="vacation" <?= $filter == 'vacation' ? 'selected' : '' ?>>Vacation</option>
                    <option value="personal" <?= $filter == 'personal' ? 'selected' : '' ?>>Personal</option>
                    <option value="sick" <?= $filter == 'sick' ? 'selected' : '' ?>>Sick</option>
                </select>

                <button type="submit">Search</button>
            </form>
        </div>
        <div class="table-record">
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Reason</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($approved_leaves_calender)): ?>
                        <tr>
                            <td colspan="6">No results found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($approved_leaves_calender as $leaves): ?>
                        <tr>
                            <td><?= htmlspecialchars($leaves['username']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($leaves['leave_type'])) ?></td>
                            <td><?= htmlspecialchars(date('d M Y', strtotime($leaves['start_date']))) ?></td>
                            <td><?= htmlspecialchars(date('d M Y', strtotime($leaves['end_date']))) ?></td>
                            <td><?= htmlspecialchars($leaves['reason']) ?></td>
                            <td><?= htmlspecialchars($leaves['comment_text']) ?: 'No comment' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&filter=<?= urlencode($filter) ?>"
                class="<?= ($i == $page) ? 'active' : '' ?>">
                <?= $i ?>
                </a>
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
        img.src = '../img/track1.png';
        });

        document.querySelector('.cards-title-heading h3').addEventListener('mouseleave', () => {
        img.src = '../img/track.png';
        });

        
        function expandDay(dayElement) {
            const leaveHtml = dayElement.getAttribute('data-leave-html');

            let expandedDotsContainer = dayElement.querySelector('.expanded-dots');
            if (!expandedDotsContainer) {
                expandedDotsContainer = document.createElement('div');
                expandedDotsContainer.className = 'expanded-dots';
                expandedDotsContainer.innerHTML = leaveHtml;
                dayElement.appendChild(expandedDotsContainer);
            }

            expandedDotsContainer.style.display = 'block';
        }

        function collapseDay(dayElement) {
            const expandedDotsContainer = dayElement.querySelector('.expanded-dots');
            if (expandedDotsContainer) {
                expandedDotsContainer.style.display = 'none';
            }
        }

        const leaveCounts = <?= json_encode(array_values($leave_counts)) ?>;
        const leaveLabels = <?= json_encode(array_keys($leave_counts)) ?>;
        const leaveColors = <?= json_encode(array_values($leave_types)) ?>;

        const ctx = document.getElementById('leaveChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: leaveLabels,
                datasets: [{
                    data: leaveCounts,
                    backgroundColor: leaveColors,
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const label = tooltipItem.label || '';
                                const value = tooltipItem.raw || 0;
                                return `${label}: ${value} leaves`;
                            }
                        }
                    }
                },
            },
        });
    </script>
</body>
</html>
