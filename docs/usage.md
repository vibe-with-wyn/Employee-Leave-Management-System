# Usage

- **Employee**:
  - Log in at [login.php](code-structure.md#key-files-and-their-functionalities). First-time users must change their password.
  - Access the dashboard ([employee_dashboard.php](code-structure.md#key-files-and-their-functionalities)) to submit leave requests, view history, check balances, or manage notifications.
- **Manager**:
  - Log in using the provided manager account (`email: user@example.com`) at [login.php](code-structure.md#key-files-and-their-functionalities). If the password is unknown, reset it via phpMyAdmin or use the known credentials.
  - Access the dashboard ([manager_dashboard.php](code-structure.md#key-files-and-their-functionalities)) to:
    - Approve/reject requests ([manage_requests.php](code-structure.md#key-files-and-their-functionalities)).
    - View leave history with filters for employee name, leave type, status, and dates ([leave_history.php (Manager)](code-structure.md#key-files-and-their-functionalities)).
    - Access monthly leave summaries, filter by year, and download PDFs ([reporting.php](code-structure.md#key-files-and-their-functionalities)).
    - Import employee data via CSV, viewing import results ([hr_import.php](code-structure.md#key-files-and-their-functionalities)).
- **Reports**:
  - Managers use [reporting.php](code-structure.md#key-files-and-their-functionalities) for monthly leave summaries.
