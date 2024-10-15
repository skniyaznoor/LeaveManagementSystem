Sure! Here's the revised **`README.md`** file for your **Leave Management System** project:

---

### `README.md`  

```markdown
# Leave Management System

A PHP-based **Employee Leave Management System** that allows employees to submit leave requests, managers to approve/reject them, and Manager to manage leave balances. The system includes features like leave history tracking and a calendar view to display approved leaves.

---

## Features

- **User Roles**: Admin, Manager, Manager, and Employee  
- **User Authentication**: Secure login and logout functionality  
- **Leave Requests**: Employees can submit leave requests  
- **Leave Approval**: Managers can approve or reject leave requests  
- **Leave Calendar**: Approved leave requests are displayed in a calendar view  
- **Leave Balance Management**: Manager can update leave balances for all employees  
- **Comments System**: Admins can add and view comments on leave requests  

---

## Project Structure

```
LeaveManagementSystem
│
├── admin
│   ├── add_comment.php
│   ├── approve_leave.php
│   ├── get_comment.php
│   └── view_requests.php
│
├── calendar
│   └── leave_calendar.php
│
├── css
│   ├── style_calendar.css
│   ├── style_dashboard.css
│   ├── style_history.css
│   ├── style_submit.css
│   ├── style_update.css
│   ├── style.css
│   └── styles.css
│
├── Database
│   └── leave_management.sql
│
├── img
│   └── (multiple images)
│
├── includes
│   ├── dp.php
│   ├── footer.php
│   └── header.php
│
├── templates
│   ├── leave_form.php
│   ├── login_form.php
│   └── manager_dashboard.php
│
├── update
│   ├── process_leave_update.php
│   └── update_leave_balance.php
│
├── user
│   ├── dashboard.php
│   ├── leave_history.php
│   └── submit_leave.php
│
├── auth.php
├── index.php
├── logout.php
└── process_login.php
```

---

## Setup Instructions

### 1. Clone the Repository
```bash
git clone https://github.com/skniyaznoor/LeaveManagementSystem.git
cd LeaveManagementSystem
```

### 2. Set Up the Database  
- Open **phpMyAdmin** (or MySQL CLI).
- Create a new database named `leave_management`.
- Import the SQL file:
  - Navigate to the **Database** tab in phpMyAdmin.
  - Select the `leave_management.sql` file and click **Import**.

### 3. Configure the Database Connection  
Update the credentials in `includes/dp.php`:
```php
$host = 'localhost';
$user = 'your-username';
$password = 'your-password';
$database = 'leave_management';
```

### 4. Run the Application  
- Place the project folder in your web server directory (e.g., `C:/xampp/htdocs/` if using XAMPP).  
- Start the **Apache** and **MySQL** services in XAMPP.
- Open a browser and visit:  
  `http://localhost/LeaveManagementSystem/`

---

## Usage

1. **Login**: Use the login form to access the system.
2. **Submit Leave**: Employees can fill out a leave request.
3. **Approve Leave**: Managers can view and approve or reject requests.
4. **Leave Calendar**: Approved leaves are displayed in a calendar view.
5. **Update Leave Balances**: Manager can update the leave balance of employees.
6. **Comment System**: Admins can add and view comments on leave requests.

---

## Technologies Used

- **Backend**: PHP, MySQL  
- **Frontend**: HTML, CSS, JavaScript  
- **Database**: MySQL  
- **Server**: Apache (via XAMPP)  

---

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.

---

## Author

[skniyaznoor](https://github.com/skniyaznoor)  
Feel free to contact me for any questions or contributions!

---

## Acknowledgments

- Thanks to **XAMPP** for providing the local development environment.
- Credit to any open-source libraries used in this project.

```
