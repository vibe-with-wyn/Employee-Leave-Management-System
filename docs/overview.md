# Employee Leave Management System with Data Reporting and Integration

## Table of Contents
- [Project Overview](#project-overview)
- [Objectives](#objectives)
- [Key Features](#key-features)
- [System Architecture](#system-architecture)
  - [Data Model](#data-model)
  - [Workflow](#workflow)
- [Tools and Technologies](#tools-and-technologies)

## Project Overview
The [Employee Leave Management System](code-structure.md) is a web-based application designed to streamline leave requests, manager approvals, and reporting. It integrates with a mock HR database via CSV import, supports data management, and generates detailed leave reports. This project aligns with Information Management and System Integration and Architecture, showcasing database administration, data integration, workflow automation, and a 3-tier architecture.

## Objectives
- Develop a functional system for employees to submit and managers to approve leave requests.
- Import employee data from a mock HR system via CSV, displaying successes and errors.
- Generate monthly leave summary reports with pending, approved, rejected counts, and total leave days, filterable by year.
- Implement a 3-tier architecture with front-end, logic, and database layers.
- Automate leave request and approval workflows with database-driven notifications.

## Key Features
- **Employee Portal**: Employees submit leave requests with leave type, dates, and reasons.
- **Manager Dashboard**: Managers approve or reject requests, view filtered leave history, and access detailed reports.
- **Reporting Module**: Provides monthly leave summaries (pending, approved, rejected counts, total leave days) with year filtering and PDF download.
- **Data Integration**: Imports employee data via CSV, displaying results for successful imports or errors (e.g., invalid emails, duplicates).
- **Notifications**: Database-driven notifications displayed as toasts or dropdowns with unread counts, triggered for request submissions, approvals, or rejections, with AJAX-based mark-as-read functionality.
- **Role-Based Access Control (RBAC)**: Restricts access to `employee` or `manager` roles. Employees access leave submission and history; managers access approvals, reporting, and HR imports.
- **Secure Login**: Enforces CSRF protection, brute force lockout, audit logging, and mandatory first-time password changes.

## System Architecture
The system follows a [3-tier architecture](#system-architecture):
- **Front-End**: HTML, CSS, JavaScript for user interfaces.
- **Logic Layer**: PHP for business logic, including leave processing and approvals.
- **Database Layer**: MySQL/MariaDB for data storage, managed via phpMyAdmin.

### Data Model
- **Entities** (defined in [leave_management.sql](../leave_management.sql)):
  - `employees`: Stores employee details (ID, name, department, role).
  - `leave_requests`: Tracks leave requests (request ID, employee ID, dates, status, duration).
  - `leave_types`: Defines leave types (e.g., vacation, sick leave).
  - `leave_balances`: Manages leave balances per leave type.
  - `departments`: Stores department details.
  - `audit_logs`: Records security actions (e.g., logins, password changes).
  - `notifications`: Stores notification messages for users.
  - `remember_tokens`: Manages "Remember Me" login tokens.

### Workflow
1. Employee submits a leave request ([leave_submission.php](code-structure.md#key-files-and-their-functionalities)).
2. Manager reviews and approves/rejects the request ([manage_requests.php](code-structure.md#key-files-and-their-functionalities)).
3. System updates the request status and triggers a notification ([Notifications](#key-features)).

## Tools and Technologies
- **Frontend**: HTML, CSS, JavaScript, [Chart.js](https://www.chartjs.org/) for reports, [Font Awesome](https://fontawesome.com/) for icons.
- **Backend**: PHP 8.2.12.
- **Database**: MariaDB 10.4.32 (compatible with MySQL), managed via phpMyAdmin 5.2.1.
- **Integration**: CSV file import for employee data.
- **Version Control**: GitHub for code and documentation.
- **Server**: Apache web server (recommended for PHP deployment).
