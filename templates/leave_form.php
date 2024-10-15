<?php 
session_start();
include '../auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Leave Request</title>
    <link rel="stylesheet" href="../css/style_submit.css">
    <script>
        function checkLeaveBalance() {
            const leaveType = document.getElementById('leave_type').value;
            const vacationBalance = <?= $vacation_balance ?>;
            const sickBalance = <?= $sick_balance ?>;
            const personalBalance = <?= $personal_balance ?>;
            
            if (leaveType === 'vacation' && vacationBalance <= 0) {
                alert("Your vacation leave balance is 0. Please select a different leave type.");
                return false;
            }
            if (leaveType === 'sick' && sickBalance <= 0) {
                if (personalBalance > 0) {
                    alert("Your sick leave balance is 0. Consider using personal leave.");
                } else {
                    alert("Your sick leave balance is 0, and you have no personal leave available.");
                    return false;
                }
            }
            return true;
        }
    </script>
</head>
<body>
<?php include "../includes/header.php"; ?>

    <div class="dashboard-control-manager">
        <div class="" style="width:50%; align-content: center;">
            <div class="dashbboard-manager">
                <div class="welcome-manager">
                    <img src="../img/request1.png" alt="" srcset="">
                    <h2>Submit your leave requests for approval</h2>
                </div>
                <div class="view-request">
                    <a href="#targetDiv" id="scrollButton">Submit Leave Requests</a>
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
        <h3>Leave Form<img id="request-icon" src="../img/request.png" alt="" srcset="" style="width:20px;height:20px;margin-left:8px"></h3>
    </div>

    <div class="submit-form">
        <form action="../user/submit_leave.php" method="POST" onsubmit="return checkLeaveBalance()">
            <label for="leave_type">Leave Type:</label>
            <select name="leave_type" id="leave_type" required>
                <option value="">-select-</option>
                <option value="vacation">Vacation</option>
                <option value="sick">Sick Leave</option>
                <option value="personal">Personal Leave</option>
            </select>
            <br>
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" id="start_date" required>
            <br>
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" id="end_date" required>
            <br>
            <label for="reason">Reason:</label>
            <textarea name="reason" id="reason" required></textarea>
            <br>
            <button type="submit">Submit Request</button>
        </form>
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

        const img = document.getElementById('request-icon');

        document.querySelector('.cards-title-heading h3').addEventListener('mouseenter', () => {
        img.src = '../img/request1.png';
        });

        document.querySelector('.cards-title-heading h3').addEventListener('mouseleave', () => {
        img.src = '../img/request.png';
        });
    </script>
</body>
</html>
