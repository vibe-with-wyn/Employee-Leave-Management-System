# Installation and Setup

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/your-group-repo/leave-management-system.git
   ```
   - The folder must be named employee-leave-management-system
2. **Install Dependencies**:
   - Install Apache web server and PHP 8.2.12.
   - Install MariaDB 10.4.32 (or MySQL equivalent) for the database.
   - Install phpMyAdmin 5.2.1 for database management.
3. **Database Setup**:
   - Create a `leave_management` database in MariaDB/MySQL via phpMyAdmin.
   - Import the provided [database.sql](../leave_management.sql) (`leave_management.sql`) to set up tables and initial data, including:
     - 10 departments (e.g., Human Resources, Information Technology).
     - 10 leave types (e.g., Vacation, Sick Leave).
     - A manager account for testing (`email: user@example.com`, `password:Test@user1`, `role: manager`, `department: Information Technology`).
4. **Configure Integration**:
   - Place the mock HR CSV file (e.g., `employees.csv`) in frontend/assets/imports/.
5. **Run the Application**:
   - Deploy to Apache and access via `http://localhost/employee-leave-management-system/frontend/public/login.php`.
6. **Access the System**:
   - Log in using the manager account (`user@example.com`) to test features, including CSV imports via [hr_import.php](code-structure.md#key-files-and-their-functionalities).
