# E-Pass System

A simple PHP + MySQL web app for issuing, verifying and printing digital passes (e.g. lockdown/event passes). Built with PHP, HTML, CSS and JavaScript.

## Requirements

- PHP 7.4+ with the `mysqli` extension
- MySQL / MariaDB
- A local web server — Apache/Nginx, [Lando](https://lando.dev/), XAMPP, or PHP's built-in server

## Setup notes (for future use)

1. **Get the code**
   Clone or copy this project into your web server's document root.

2. **Import the database**
   - Create a database in MySQL (via phpMyAdmin or the CLI).
   - Import the dump from `SQL FILE/epass.sql`. It creates the `login`, `tblcategory` and `tblpass` tables and seeds a starter admin account.

3. **Configure the database connection**
   - Open `dbconnection.php` and set `$host`, `$user`, `$password` and `$db` to match your MySQL setup.
   - Every page (`index.php`, `AdminRegistration.php`, `ForgetPass.php`, and everything under `Dashboard/`) includes this one file — so the connection only needs to be configured here, in a single place.

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
   Open `index.php` in your browser and sign in with the seeded admin account from `SQL FILE/epass.sql` (`admin` / `admin`). Change this password (or create a new account via "New Registration") after your first login.

## Project layout

- `index.php` — Login page
- `AdminRegistration.php` — Create a new admin account
- `ForgetPass.php` — Recover a password by mobile number & email
- `Dashboard/` — Authenticated admin area: issue new passes, verify/print passes, manage categories, log out
- `dbconnection.php` — Single shared database connection, included by every page
- `assets/css/style.css` — Shared stylesheet for the whole site
- `Images/` — Background images and avatar used across the UI
- `SQL FILE/` — Database dump(s) to import

## A note on security

This project uses raw `mysqli` queries and stores passwords in plain text, which is fine for local learning/demo purposes but **not safe for production**. Before deploying it anywhere public, switch to prepared statements and hash passwords (e.g. with `password_hash`/`password_verify`).
