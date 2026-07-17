# E-Pass System

A PHP + MySQL admin app for issuing, verifying and printing digital passes (e.g. lockdown/event/site passes). Built with plain PHP (PDO), HTML, CSS and vanilla JavaScript — no framework or Composer dependencies required.

## Requirements

- PHP 7.4+ with the `pdo_mysql` extension
- MySQL / MariaDB
- A local web server — Apache/Nginx, [Lando](https://lando.dev/), XAMPP, or PHP's built-in server

## Setup notes

1. **Get the code**
   Clone or copy this project into your web server's document root.

2. **Import the database**
   - Create a database in MySQL (via phpMyAdmin or the CLI).
   - Import the dump from `SQL FILE/epass.sql`. It creates the `login`, `tblcategory` and `tblpass` tables and seeds a starter **superadmin** account.

3. **Configure the database connection**
   - Open `dbconnection.php` and set `$host`, `$user`, `$password` and `$db` to match your MySQL setup.
   - Every page includes `includes/bootstrap.php`, which loads this file — so the connection only needs to be configured here, in one place.

   Example for a typical XAMPP / local MySQL setup:
   ```php
   $host="localhost";
   $user="root";
   $password="";
   $db="epass";
   ```

   Example for Lando (this repo ships with a `.lando.yml` using the `lamp` recipe and a phpMyAdmin service):
   ```php
   $host="database";
   $user="lamp";
   $password="lamp";
   $db="lamp";
   ```

4. **Run it**
   - Lando: run `lando start` from the project root.
   - XAMPP/Apache: place the project under `htdocs` and open `http://localhost/E-Pass-System`.
   - PHP built-in server: run `php -S localhost:8000` from the project root.

5. **Log in**
   Open `index.php` and sign in with the seeded superadmin account from `SQL FILE/epass.sql`: **admin / admin**. Change this password (via "Forgot Password", or from Admin Users once logged in) after your first login.

## Project layout

- `index.php` — Login page (rate-limited, CSRF-protected)
- `ForgetPass.php` — Reset a password after verifying mobile number + email (never reveals the old password)
- `dbconnection.php` — Database credentials, loaded once via `includes/bootstrap.php`
- `includes/` — Shared PDO connection, auth guards, CSRF helpers, flash messages, and the dashboard layout chrome
- `Dashboard/` — Authenticated admin area:
  - `Dashboard.php` — stats overview (totals, active/revoked, by-category breakdown, recent activity)
  - `NewPass.php` — create or edit a pass (`?id=`)
  - `Passes.php` — search, filter, paginate, edit/revoke/reactivate/delete passes
  - `ViewPass.php` — quick gate-style lookup by mobile number
  - `PrintPass.php` — print-friendly pass card with a QR code
  - `AddCategory.php` — manage pass categories
  - `AdminUsers.php` — superadmin-only: create admin accounts, reset passwords, activate/deactivate accounts
  - `Logout.php`
- `assets/css/style.css`, `assets/js/app.js` — shared styling and small UI behaviors (confirm dialogs, sidebar toggle, submit-guard)
- `Images/` — background images and avatar used across the UI
- `SQL FILE/` — database dump to import (blocked from direct web access via `.htaccess`)

## Security

- All queries use PDO prepared statements — no string-concatenated SQL.
- Passwords are hashed with `password_hash` (bcrypt); nothing is ever stored or displayed in plaintext.
- Every state-changing form is CSRF-protected and follows POST/redirect/GET.
- Login is rate-limited (5 failed attempts locks out for 60 seconds), and session IDs are regenerated on login.
- New admin accounts can only be created by an existing superadmin from inside the dashboard — there is no public self-registration page.
- The `SQL FILE/` directory and any `.sql` file are blocked from direct HTTP access.

This is still a learning/demo-scale project (no email delivery, no audit log, no automated tests) — review it further before exposing it publicly.
