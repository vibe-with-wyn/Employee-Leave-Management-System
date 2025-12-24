# Notes

- Follow modular coding, commenting, and version control practices.
- Ensure repository organization with clear folders.
- Maintain data integrity and usability.
- **Security**: CSRF protection, brute force lockout, audit logging, and mandatory password changes enhance security ([LoginController.php](code-structure.md#key-files-and-their-functionalities)).
- **Notifications**: Stored in `notifications` table, fetched via controllers, displayed as toasts/dropdowns with unread badges, and updated via AJAX ([ManagerDashboardController.php](code-structure.md#key-files-and-their-functionalities)).
- **RBAC**: Uses `employee` and `manager` roles, enforced by [AuthMiddleware.php](code-structure.md#key-files-and-their-functionalities) and [Session.php](code-structure.md#key-files-and-their-functionalities). Managers access exclusive features; employees are restricted.
- Database operations use phpMyAdmin, with [Auth.php](code-structure.md#key-files-and-their-functionalities) for authentication and [LeaveModel.php](code-structure.md#key-files-and-their-functionalities) for leave queries.
- The manager account (`user@example.com`) is included in the database for testing, ensuring users who test can log in and import employees via CSV without creating a new account.
