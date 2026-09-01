# NSBM EventHub — Frontend / Backend Split

This version separates the original project into two clear parts:

```text
NSBM-EventHub-Split/
├── frontend/        # ONLY HTML, CSS, JavaScript and frontend assets
│   ├── admin/
│   ├── student/
│   ├── assets/
│   ├── index.html
│   ├── login.html
│   └── register.html
└── backend/         # ONLY PHP API, database connection, installer and SQL
    ├── api.php
    ├── db.php
    ├── config.php
    ├── helpers.php
    ├── install.php
    └── eventhub.sql
```

## Setup
1. Install XAMPP/WAMP/Laragon with Apache, PHP and MySQL/MariaDB.
2. Put this whole folder inside the web server document root (for example `htdocs`).
3. Edit `backend/config.php` if your MySQL username/password/database host differs from the defaults.
4. Start Apache and MySQL.
5. Open `http://localhost/NSBM-EventHub-Split/backend/install.php` once, or import `backend/eventhub.sql` using phpMyAdmin.
6. Open `http://localhost/NSBM-EventHub-Split/frontend/index.html`. Do **not** open the HTML with `file://`, because the browser needs the PHP API over HTTP.

## Demo accounts
- Admin: `admin@nsbm.ac.lk` / `admin123`
- Student: `kamal@student.nsbm.ac.lk` / `student123`

## What was changed
- Removed PHP from frontend files; frontend is now static HTML + JS.
- Moved all database/session/business logic into `backend/api.php` and backend helper files.
- Added session-based authentication through the PHP API.
- Converted admin CRUD, student registration/cancellation, schedules, announcements and participant lists to API calls.
- Added atomic seat-capacity checking to reduce overbooking.
- Fixed the original invalid PHP/HTML connection files and broken relative paths.
- Replaced invalid seed password hashes with valid PHP hashes for the demo accounts.
- Kept the Bootstrap-based EventHub styling and responsive layout.

## API examples
The frontend calls endpoints such as `backend/api.php?action=login`, `events`, `registration`, `schedule`, `categories`, `announcements`, and `participants`.
