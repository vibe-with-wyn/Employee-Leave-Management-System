# Employee Leave Management System with Data Reporting and Integration

This web-based system streamlines employee leave requests, manager approvals, and reporting with CSV-based data integration and a 3-tier architecture (frontend, logic, database).

## Requirements
- PHP 8.2.12
- MariaDB 10.4.32 (or MySQL equivalent)
- Apache HTTP Server
- phpMyAdmin 5.2.1

## Quick Start
1. Clone the repository:
  ```bash
  git clone https://github.com/your-group-repo/leave-management-system.git
  ```
  Ensure the folder is named `employee-leave-management-system`.
  
2. Create a `leave_management` database and import the schema:
  ```
  Import leave_management.sql via phpMyAdmin
  ```
3. Deploy to Apache and open:
  ```
  http://localhost/employee-leave-management-system/frontend/public/login.php
  ```
4. Log in with the provided manager account to explore features.

## Documentation
- [Overview](docs/overview.md)
- [Installation and Setup](docs/installation.md)
- [Code Structure](docs/code-structure.md)
- [Usage](docs/usage.md)
- [Testing](docs/testing.md)
- [Future Enhancements](docs/future-enhancements.md)
- [Notes](docs/notes.md)

