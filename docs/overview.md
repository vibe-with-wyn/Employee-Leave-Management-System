# Overview — Employee Leave Management System

A web-based leave request system that supports employee leave submissions, manager approvals, HR CSV-based employee import, and basic reporting with PDF export. The project follows a simple 3-tier structure (frontend UI, backend PHP logic, MariaDB database).

## Live Deployment
- Domain: https://absynq.is-great.net/
- App Base (subfolder): https://absynq.is-great.net/employee-leave-management-system
- Login page: https://absynq.is-great.net/employee-leave-management-system/frontend/public/login.php
- Source repository: https://github.com/vibe-with-wyn/absynq-elms

## Roles
### Employee
- Login + first-login forced password change
- Submit leave requests (type, date range, reason)
- View leave history + filters/pagination
- View notifications

### Manager
- Login + access restricted to manager pages
- View a dashboard (summary + charts)
- Approve/reject leave requests (with optional comment for approve, required reason for reject)
- Reporting module (monthly summary table + PDF download)
- HR Import (upload CSV to add employees)

## Main Modules (as deployed)
- Authentication (login, remember-me option, logout)
- Employee dashboard, leave submission, leave history
- Manager dashboard, manage requests, manager leave history
- Reporting module (year filter + PDF export)
- HR import module (CSV upload + results)

## Data Model (high level)
Core tables in `leave_management.sql` include:
- `employees` (role: employee|manager, first_login flag)
- `leave_types`
- `leave_requests` (pending/approved/rejected)
- `leave_balances`
- `departments`
- `notifications`
- `audit_logs`
- `remember_tokens`

## Entry Points / Routing
- Deployed app base is a subfolder (`/employee-leave-management-system`).
- Default entry is the Login page (see link above).
- After login, routing is based on session role (employee vs manager).
