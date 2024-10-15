<?php
session_start();
include '../auth.php';
include_once "../includes/dp.php";

$stmt = $conn->prepare("SELECT lr.*, u.username FROM leave_requests lr JOIN users u ON lr.user_id = u.id WHERE lr.status = 'pending'");
$stmt->execute();
$result = $stmt->get_result(); 
$pending_requests = $result->fetch_all(MYSQLI_ASSOC);

$cards_per_page = 8; 
$total_requests = count($pending_requests);
$total_pages = ceil($total_requests / $cards_per_page);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_index = ($page - 1) * $cards_per_page;

$current_page_requests = array_slice($pending_requests, $start_index, $cards_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Leave Requests</title>
    <link rel="stylesheet" href="../css/style_history.css">
</head>
<body>
    <?php include_once "../includes/header.php"; ?>

    <div class="dashboard-control-manager">
        <div class="" style="width:50%; align-content: center;">
            <div class="dashbboard-manager">
                <div class="welcome-manager">
                    <img src="../img/confirm1.png" alt="" srcset="">
                    <h2>All pending leave requests awaiting your approval</h2>
                </div>
                <div class="view-request">
                    <a href="#targetDiv" id="scrollButton">Pending Leave Requests</a>
                </div>
            </div>
        </div>
        <div class="" style="width:50%; align-content: center;">
            <div class="leave-request-image">
                <img src="../img/about-hero.webp" alt="Hero Image" style="width:550px;">
            </div>
        </div>
    </div>

    <div class="cards-title-heading" id="targetDiv">
        <h3>Requests <img id="request-icon" src="../img/request.png" alt="" srcset="" style="width:20px;height:20px;margin-left:8px"></h3>
    </div>
    <div class="cards-container">
        <?php if (empty($current_page_requests)): ?>
            <h3 style="color:red;">No Pending Requests</h3>
        <?php else: ?>
            <?php foreach ($current_page_requests as $request): ?>
                <div class="card">
                    <h4>Employee: <?= $request['username'] ?></h4>
                    <p><strong>Leave Type:</strong> <?= $request['leave_type'] ?></p>
                    <p><strong>Start Date:</strong> <?= $request['start_date'] ?></p>
                    <p><strong>End Date:</strong> <?= $request['end_date'] ?></p>
                    <p><strong>Reason:</strong> <?= $request['reason'] ?></p>
                    <button class="open-comment-modal" data-id="<?= $request['id'] ?>">Add Comment</button>

                    <div class="action-buttons">
                        <form action="../admin/approve_leave.php" method="POST">
                            <input type="hidden" name="id" value="<?= $request['id'] ?>">
                            <button type="submit" name="action" value="reject" class="btn-reject">Reject</button>
                            <button type="submit" name="action" value="approve" class="btn-approve">Approve</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>

    <div id="commentModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add Comment</h2>
            <form id="commentForm" style="margin-top:5px;">
                <textarea name="comment" id="commentText" placeholder="Manager's comment" required></textarea>
                <input type="hidden" name="leave_request_id" id="modalRequestId" value="">
                <button type="submit" name="action" value="comment">Submit</button>
            </form>
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
        img.src = '../img/request1.png';
        });

        document.querySelector('.cards-title-heading h3').addEventListener('mouseleave', () => {
        img.src = '../img/request.png';
        });



        const modal = document.getElementById("commentModal");
        const buttons = document.querySelectorAll(".open-comment-modal");

        buttons.forEach(button => {
            button.onclick = function() {
                const requestId = this.getAttribute("data-id");
                document.getElementById("modalRequestId").value = requestId; 

                fetch(`../admin/get_comment.php?id=${requestId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById("commentText").value = data && data.comment ? data.comment : ""; 
                        modal.style.display = "block"; 
                    })
                    .catch(error => console.error('Error fetching comment:', error));
            }
        });

        const span = document.getElementsByClassName("close")[0];

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        document.getElementById("commentForm").onsubmit = function(event) {
            event.preventDefault(); 
            const formData = new FormData(this); 

            fetch('../admin/add_comment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // alert(data.includes("Comment added/updated successfully") ? "Comment added/updated successfully!" : "Failed to add comment: " + data);
                if (data.includes("Comment added/updated successfully")) {
                    modal.style.display = "none"; 
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("An error occurred: " + error);
            });
        }
    </script>

</body>
</html>
