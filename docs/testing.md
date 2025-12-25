# Employee Leave Management System — Testing Guide

This guide walks you through testing the deployed system end-to-end using real links, a manager test account, and mock employee data for HR import.

## Base URL (Deployed)
- Domain: https://vibe-with-wyn.infinityfree.me
- App Base: https://vibe-with-wyn.infinityfree.me/employee-leave-management-system

Quick links:
- Login: https://vibe-with-wyn.infinityfree.me/employee-leave-management-system/frontend/public/login.php
- Employee Dashboard: https://vibe-with-wyn.infinityfree.me/employee-leave-management-system/frontend/views/employee_dashboard.php
- Manager Dashboard: https://vibe-with-wyn.infinityfree.me/employee-leave-management-system/frontend/views/manager/manager_dashboard.php
- Manager Reporting: https://vibe-with-wyn.infinityfree.me/employee-leave-management-system/frontend/views/manager/reporting.php
- Manager HR Import: https://vibe-with-wyn.infinityfree.me/employee-leave-management-system/frontend/views/manager/hr_import.php
- Logout: https://vibe-with-wyn.infinityfree.me/employee-leave-management-system/backend/controllers/LogoutController.php?action=logout

## Test Account (Manager)
Use this account to access manager features:
- Email: user@example.com
- Password: Test@user1
- Role: manager
- Department: Information Technology

## Mock Employee Data (CSV)
Use this file to test HR import:
- Repository path: frontend/assets/imports/employees.csv
- Direct URL (download and import in the HR Import page):
  https://vibe-with-wyn.infinityfree.me/employee-leave-management-system/frontend/assets/imports/employees.csv

Default password for imported employees (algorithm):
- Format: first letter of first name + first 3 letters of last name + day-of-hire + "!"
- All lowercase letters
- Example (James Wilson, 2024-01-15): jwil15!
- Example (Laura Bennett, 2023-06-01): lben01!

Note: On import, passwords are also logged to server: logs/imported_default_password.log (server-side file).

---

## Test Scenarios

### 1) Manager Login and Dashboard
Steps:
1. Open the Login page.
2. Log in with manager credentials (user@example.com / Test@user1).
3. You should land on the Manager Dashboard.

Expected:
- Manager greeting appears.
- Summary cards show counts for pending/approved/rejected.
- Chart sections display leave type distribution and monthly trends.
- Profile dropdown includes Logout.

Troubleshooting:
- If redirected back to login, check credentials and ensure cookies are enabled.

---

### 2) HR Import — Upload employees.csv
Steps:
1. Go to Manager HR Import page.
2. Download the CSV using the link above, then upload it.
3. Submit the form.

Expected:
- A success toast appears (top-right).
- “Import Results” section shows:
  - Employees Added Successfully count.
  - Skipped Records count (duplicates or invalid).
  - Tables of successes and any issues.
- New employees are added with default dynamic passwords as per the algorithm.

Troubleshooting:
- If “Invalid CSV headers” appears, ensure you used the provided CSV.
- If “File size/type error,” ensure the file is .csv and < 5MB.

---

### 3) Imported Employee — First Login and Password Change
Steps:
1. Pick any imported employee from the CSV (e.g., james.wilson@company.com).
2. Compute their default password using the algorithm above.
3. Log in via the Login page.
4. On first login, you’ll be asked to change the password.
   - Requirements: min 8 chars, 1 uppercase, 1 number, 1 special char.

Expected:
- After changing the password, redirect to the Employee Dashboard.
- You should see greeting and cards.

Troubleshooting:
- If “Please enter a different password” appears, don’t reuse the default password.
- If “Passwords do not match,” re-enter carefully.

---

### 4) Employee Dashboard — Notifications and UI
Steps:
1. As an employee, open the Employee Dashboard.
2. Open the notification dropdown (top-right).
3. If there are pending notifications, they should display; the badge reduces when marked read.

Expected:
- Toast messages appear for API actions (success/error).
- Dropdown opens and closes as expected.

---

### 5) Leave Submission (Form)
Steps:
1. Navigate to Leave Submission: https://vibe-with-wyn.infinityfree.me/employee-leave-management-system/frontend/views/leave_submission.php
2. Fill out the form:
   - Leave type: choose from available list (e.g., "Sick Leave", "Vacation Leave")
   - Start/End dates: future dates, end after start
   - Reason: any text
3. Submit.

Expected:
- If insufficient balance, you’ll see an error.
- If eligible and with balance, you’ll get a success message “Awaiting approval.”
- HR/Manager should later see this in reports and/or pending lists.

Troubleshooting:
- If “Start date cannot be in the past,” pick a future date.
- If “Invalid CSRF token,” refresh the page and try again.

---

### 6) Manager — Reporting Module and PDF Download
Steps:
1. Open Reporting: https://vibe-with-wyn.infinityfree.me/employee-leave-management-system/frontend/views/manager/reporting.php
2. Pick a year in the dropdown.
3. Click “Download PDF.”

Expected:
- Monthly summary table (pending/approved/rejected/total days).
- A PDF file is generated and downloaded.

Troubleshooting:
- If no data, import employees and submit test leave requests first.

---

### 7) Manager — Approvals (API fallback)
If UI for Manage Requests is blank, use API to approve/reject:
- Endpoint: /employee-leave-management-system/backend/controllers/LeaveRequestController.php
- Method: POST
- Body (JSON):
  - Approve: {"action":"approve","id":123,"comment":"Approved"}
  - Reject: {"action":"reject","id":123,"comment":"Insufficient balance"}

Expected:
- Response: {"success":true,"message":"Leave request approved successfully."} or similar.
- Employee receives a notification.

Tip: Use browser DevTools or a tool like Postman for API calls.

---

### 8) Logout
Steps:
1. Use Logout link in profile dropdown, or:
2. Visit: https://vibe-with-wyn.infinityfree.me/employee-leave-management-system/backend/controllers/LogoutController.php?action=logout

Expected:
- Redirected to base with “You have been logged out.” toast.

---

## Test Data Summary
- Manager account: user@example.com / Test@user1
- Mock CSV: frontend/assets/imports/employees.csv
- CSV headers: FIRST_NAME, LAST_NAME, EMAIL, PHONE_NUMBER, HIRE_DATE, MANAGER_ID, DEPARTMENT_ID, ROLE
- Default employee password format: f + lll + DD + "!" (lowercase)
  - f = first letter of first name
  - lll = first three letters of last name
  - DD = day-of-hire (01–31)

Examples:
- James Wilson, hire date 2024-01-15 → jwil15!
- Laura Bennett, hire date 2023-06-01 → lben01!

---

## Troubleshooting Checklist
- 404 when visiting pages:
  - Use the exact URLs above with the /employee-leave-management-system base.
- Session errors:
  - Ensure cookies enabled; retry login.
- “Database connection failed”:
  - Contact hosting; verify DB credentials are live and correct in backend config.
- “Invalid CSRF token”:
  - Refresh the page before submitting.
- Assets not loading:
  - Clear cache; ensure paths begin with /employee-leave-management-system.

---

## Notes
- InfinityFree can cache pages; use Ctrl+F5 to hard refresh.
- HR import writes default passwords into a server log: logs/imported_default_password.log (admin-only access).
