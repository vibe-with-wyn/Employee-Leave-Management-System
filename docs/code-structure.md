# Code Structure

The project is organized as follows:

```
EMPLOYEE-LEAVE-MANAGEMENT-SYSTEM/
├── backend/
│   ├── controllers/
│   │   ├── EmployeeDashboardController.php
│   │   ├── HRImportController.php
│   │   ├── LeaveRequestController.php
│   │   ├── LeaveSubmissionController.php
│   │   ├── LoginController.php
│   │   ├── LogoutController.php
│   │   ├── ManagerDashboardController.php
│   ├── middlewares/
│   │   ├── AuthMiddleware.php
│   ├── models/
│   │   ├── Auth.php
│   │   ├── LeaveModel.php
│   ├── services/
│   │   ├── generate_report.php
│   ├── src/
│   │   ├── Database.php
│   │   ├── Session.php
│   ├── utils/
│   │   ├── redirect.php
│   ├── vendor/
├── frontend/
│   ├── assets/
│   │   ├── css/
│   │   │   ├── dashboard.css
│   │   │   ├── login.css
│   │   │   ├── manager_dashboard.css
│   ├── img/
│   │   ├── employees-ill.png
│   ├── imports/
│   │   ├── employees.csv
│   ├── js/
│   │   ├── dashboard.js
│   │   ├── login.js
│   │   ├── manager_dashboard.js
│   │   ├── password_validation.js
│   ├── public/
│   │   ├── login.php
│   ├── views/
│   │   ├── manager/
│   │   │   ├── hr_import.php
│   │   │   ├── leave_history.php
│   │   │   ├── manage_requests.php
│   │   │   ├── manager_dashboard.php
│   │   │   ├── reporting.php
│   │   ├── employee_dashboard.php
│   │   ├── leave_history.php
│   │   ├── leave_submission.php
│   │   ├── login_view.php
├── diagrams/
│   ├── Architecture.pdf
│   ├── DFD.pdf
│   ├── ERD.pdf
├── report/
│   ├── IM and SIA Project Report.pdf
├── presentation-slide/
│   ├── Presentation Slides.odp
│   ├── Presentation Slides.pdf
├── logs/
│   ├── imported_default_password.log
├── tests/
├── leave_management.sql
├── .gitignore
├── index.php
├── README.md
```

## Key Files and Their Functionalities
- **[index.php](../index.php)**: Routes requests to login, logout, or dashboards, redirecting unmatched routes to the login page.
- **[login.php](../frontend/public/login.php)**: Entry point for the login page, invoking [LoginController.php](#key-files-and-their-functionalities) and rendering [login_view.php](#key-files-and-their-functionalities).
- **[login_view.php](../frontend/views/login_view.php)**: HTML for login and password change forms, supporting email/password input, "Remember Me," and validation feedback.
- **[LoginController.php](../backend/controllers/LoginController.php)**: Manages authentication, session handling, CSRF protection, brute force lockout, first-time password changes, and audit logging.
- **[login.css](../frontend/assets/css/login.css)**: Styles the login page with responsive design, animated shapes, and a modern login card.
- **[login.js](../frontend/assets/js/login.js)**: Handles client-side login form validation, toggling the `filled` class for inputs.
- **[password_validation.js](../frontend/assets/js/password_validation.js)**: Validates password changes client-side, enforcing requirements (length, uppercase, number, special character) with a strength bar.
- **[employee_dashboard.php](../frontend/views/employee_dashboard.php)**: Renders the employee dashboard with navigation (Dashboard, Leave Submission, Leave History, Settings), showing leave balances, request counts, and notifications.
- **[EmployeeDashboardController.php](../backend/controllers/EmployeeDashboardController.php)**: Fetches employee data, leave requests, balances, and notifications, managing AJAX-based notification actions and leave history.
- **[LogoutController.php](../backend/controllers/LogoutController.php)**: Clears session data and remember tokens, redirecting to the homepage.
- **[dashboard.css](../frontend/assets/css/dashboard.css)**: Styles the employee dashboard with a responsive sidebar, cards, and toast notifications.
- **[dashboard.js](../frontend/assets/js/dashboard.js)**: Manages client-side dashboard interactions, including sidebar toggling, dropdowns, and AJAX notification updates.
- **[redirect.php](../backend/utils/redirect.php)**: Utility for redirects with optional success/error messages.
- **[Database.php](../backend/src/Database.php)**: Singleton for secure MySQL/MariaDB PDO connections with error handling.
- **[Session.php](../backend/src/Session.php)**: Manages secure PHP sessions (HTTPS, HTTP-only, SameSite=Strict), handling role checks and authentication.
- **[Auth.php](../backend/models/Auth.php)**: Handles login, password updates, remember tokens, and logout, integrating with [Session.php](#key-files-and-their-functionalities) for role validation.
- **[AuthMiddleware.php](../backend/middlewares/AuthMiddleware.php)**: Enforces authentication and role-based access, redirecting unauthenticated users and ensuring password changes.
- **[LeaveModel.php](../backend/models/LeaveModel.php)**: Manages leave-related database operations (leave types, requests, balances, history).
- **[leave_submission.php](../frontend/views/leave_submission.php)**: Renders the leave request form, allowing employees to select leave types, dates, and reasons, with notifications.
- **[LeaveSubmissionController.php](../backend/controllers/LeaveSubmissionController.php)**: Processes leave request submissions via AJAX, validating CSRF tokens, dates, and balances, and managing notifications.
- **[leave_history.php (Employee)](../frontend/views/leave_history.php)**: Displays an employee’s leave history with status, date, and leave type filters, plus pagination.
- **[leave_history.php (Manager)](../frontend/views/manager/leave_history.php)**: Shows approved/rejected leave requests with filters for employee name, leave type, status, and date range, plus pagination, restricted to managers.
- **[manager_dashboard.php](../frontend/views/manager/manager_dashboard.php)**: Renders the manager dashboard with navigation (Home, Manage Requests, Leave History, Reporting, HR Import, Settings), showing statistics, trends, and notifications.
- **[ManagerDashboardController.php](../backend/controllers/ManagerDashboardController.php)**: Fetches manager-specific data (statistics, trends, notifications, leave history), handling AJAX-based notification updates and audit logging.
- **[manager_dashboard.js](../frontend/assets/js/manager_dashboard.js)**: Manages client-side manager dashboard interactions, including Chart.js visualizations and AJAX notification updates.
- **[manager_dashboard.css](../frontend/assets/css/manager_dashboard.css)**: Styles the manager dashboard with responsive layouts for cards, charts, and notifications.
- **[manage_requests.php](../frontend/views/manager/manage_requests.php)**: Allows managers to review and action pending leave requests with a paginated table and approval/rejection modals.
- **[LeaveRequestController.php](../backend/controllers/LeaveRequestController.php)**: Processes manager approvals/rejections, validating department and balances, sending notifications, and logging actions.
- **[HRImportController.php](../backend/controllers/HRImportController.php)**: Handles CSV employee imports, validating headers, emails, and roles, generating passwords, and displaying import results.
- **[hr_import.php](../frontend/views/manager/hr_import.php)**: Renders the HR import page, allowing CSV uploads and showing import results.
- **[reporting.php](../frontend/views/manager/reporting.php)**: Displays a monthly leave summary table (pending, approved, rejected requests, total leave days) with year filtering and PDF download, restricted to managers.
- **[database.sql](../leave_management.sql)**: SQL script to create the `leave_management` database and tables (`employees`, `leave_requests`, `leave_types`, `leave_balances`, `departments`, `audit_logs`, `notifications`, `remember_tokens`).
- **[employees.csv](../frontend/assets/imports/employees.csv)**: Mock HR data for CSV imports.
