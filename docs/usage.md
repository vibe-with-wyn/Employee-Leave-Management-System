# Usage Guide

This guide explains how to use the deployed system by role (Employee vs Manager) and points to the exact live pages.

## Live URLs (Deployed)
- App Base: https://absynq.is-great.net/employee-leave-management-system
- Login: https://absynq.is-great.net/employee-leave-management-system/frontend/public/login.php
- Logout: https://absynq.is-great.net/employee-leave-management-system/backend/controllers/LogoutController.php?action=logout

---

## Login + First Login Password Change
1. Open the Login page.
2. Sign in using your credentials.
3. If your account is marked as first login, you will be prompted to **change your password before proceeding**.

Password rules enforced by the UI:
- Minimum length: 8
- At least 1 uppercase letter
- At least 1 number
- At least 1 special character

---

## Employee Flow

### Employee Dashboard
- https://absynq.is-great.net/employee-leave-management-system/frontend/views/employee_dashboard.php

What you can do:
- Navigate to Leave Submission and Leave History
- See request counts (e.g., pending/approved)
- View remaining leave days (based on leave balances)

### Submit a Leave Request
- https://absynq.is-great.net/employee-leave-management-system/frontend/views/leave_submission.php

Steps:
1. Select a leave type.
2. Choose start/end dates (end must be after start; start cannot be in the past).
3. Provide a reason.
4. Submit.

Expected results:
- If valid and eligible, request is created as **pending** (awaiting manager action).

### Leave History (Employee)
- https://absynq.is-great.net/employee-leave-management-system/frontend/views/leave_history.php

Supports:
- Filtering (status/date/leave type)
- Pagination

### Notifications (Employee)
- Notifications are available from the bell icon in the header.
- You can mark notifications read and/or delete notifications (UI actions depend on the page).

---

## Manager Flow

### Manager Dashboard
- https://absynq.is-great.net/employee-leave-management-system/frontend/views/manager/manager_dashboard.php

Includes:
- Summary counts (pending/approved/rejected)
- Charts for leave type distribution and trends

### Manage Requests (Approve/Reject)
- https://absynq.is-great.net/employee-leave-management-system/frontend/views/manager/manage_requests.php

Steps:
1. Open Manage Requests.
2. For a pending request, choose Approve or Reject.
3. Provide a comment (optional for approve; rejection expects a reason).
4. Submit action.

### Manager Leave History
- https://absynq.is-great.net/employee-leave-management-system/frontend/views/manager/leave_history.php

Shows:
- Approved and rejected requests
- Filter by employee name, leave type, status, date range

### Reporting Module + PDF
- https://absynq.is-great.net/employee-leave-management-system/frontend/views/manager/reporting.php

Steps:
1. Select a year.
2. View the monthly summary.
3. Use “Download PDF” to export.

### HR Import (CSV Upload)
- https://absynq.is-great.net/employee-leave-management-system/frontend/views/manager/hr_import.php

CSV rules:
- Headers must match:
  `FIRST_NAME, LAST_NAME, EMAIL, PHONE_NUMBER, HIRE_DATE, MANAGER_ID, DEPARTMENT_ID, ROLE`

Mock CSV:
- Repo path: `frontend/assets/imports/employees.csv`
- Direct URL:
  https://absynq.is-great.net/employee-leave-management-system/frontend/assets/imports/employees.csv

Imported employee default password algorithm:
- first letter of first name + first 3 letters of last name + day-of-hire + `!` (lowercase)

Example:
- James Wilson (2024-01-15) → `jwil15!`
