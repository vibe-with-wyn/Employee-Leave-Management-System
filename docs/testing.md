# Testing

- **Unit Testing**: Validates form submissions and inputs (e.g., login, password validation).
- **Integration Testing**: Ensures CSV imports work and display accurate results.
- **User Testing**: Verifies manager workflows (approvals, reporting, leave history filtering) and notification usability.
- **Instructor Testing**:
  - Use the manager account (`user@example.com`) to log in and test CSV imports via [hr_import.php](code-structure.md#key-files-and-their-functionalities).
  - Verify reporting and leave history features using [reporting.php](code-structure.md#key-files-and-their-functionalities) and [leave_history.php (Manager)](code-structure.md#key-files-and-their-functionalities).
