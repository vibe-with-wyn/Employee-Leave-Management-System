# Installation & Setup

This project can be used in two ways:
1) **Deployed (recommended for demo/testing)** via the live URL.
2) **Local development** using Apache + PHP + MariaDB/MySQL.

## Option A — Use the Deployed App
- App Base: https://absynq.is-great.net/
- Login: https://absynq.is-great.net/employee-leave-management-system/frontend/public/login.php

Use the Testing Guide for accounts, CSV import, and end-to-end scenarios:
- `docs/testing.md`

## Option B — Local Development Setup

### Requirements
- PHP 8.2+
- Apache (or equivalent web server)
- MariaDB 10.4+ (or MySQL equivalent)
- phpMyAdmin (optional, for import convenience)

### Steps
1. Clone:
   ```bash
   git clone https://github.com/vibe-with-wyn/absynq-elms.git
   ```
   The folder will be named employee-leave-management-system.

2. Put the folder under your web root so the URL becomes:
   - `http://localhost/employee-leave-management-system/`

3. Create a database named `leave_management` and import:
   - `leave_management.sql`

4. Configure database credentials:
   - Update the backend config used by `backend/src/Database.php`
   - Typical location: `backend/config/config.php`
   - (Keep credentials out of Git if you add secrets.)

5. Open:
   - `http://localhost/employee-leave-management-system/frontend/public/login.php`

## Hosting Notes (InfinityFree-style deployments)
- The app is designed to run under a **subfolder base path**:
  - `/employee-leave-management-system`
- Ensure `.htaccess` rewriting (if supported) and that the project folder name matches the base path you’re using.
