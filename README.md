# SchoolSystem

## MVC Architecture Overview

- `app/Controllers` contains controller classes that handle HTTP request flow:
  - `AuthController` (login/logout)
  - `HomeController` (dashboard)
  - `SubjectController` (subject list/create/edit)
  - `ProgramController` (program list/create/edit)
  - `UserController` (user list/create/edit)
  - `PasswordController` (change password)

- `app/Models` contains data access classes using prepared statements:
  - `User` for users, authentication, password verify/update
  - `Subject` for CRUD subject operations
  - `Program` for CRUD program operations

- `app/Views` contains HTML templates only (no SQL):
  - `auth/login.php`
  - `home.php`
  - `subject/list.php`, `subject/form.php`
  - `program/list.php`, `program/form.php`
  - `user/list.php`, `user/form.php`
  - `password/change.php`

- `app/Core` contains support classes:
  - `Database` (MySQL singleton)
  - `Auth` (session access checks)
  - `SessionManager` (session and flash messages)

- `public/index.php` is the single entry point (front controller), route by `?controller=...&action=...`.
- `bootstrap.php` loads configuration and starts session.
- `config/config.php` stores database and environment settings.

## Default Admin Credentials

- Username: `admin`
- Password: `admin123` (or as seeded in your database setup, update using SQL presumably for your setup)

## How to Run the Project

1. Place project in your web root (e.g., `C:/xampp/htdocs/test/Labor_Laurence_Exer2`).
2. Ensure MySQL database `school` exists, then run `database_setup.sql` to create tables and seed data.
3. Update `config/config.php` DB settings if needed.
4. Open browser and go to:
   - `http://localhost/test/Labor_Laurence_Exer2/public/index.php?controller=auth&action=login`
5. Login as admin to access users and manage features.
6. Use navigation on home for subjects, programs, change password, logout.

## Notes

- Old non-MVC root PHP pages were removed; use `public/index.php` routes.
- Session control and role access are enforced by `Auth` and `SessionManager`.
