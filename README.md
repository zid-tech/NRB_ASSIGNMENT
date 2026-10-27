# NRB National ID Registration System

A PHP 8.2 + MySQL/MariaDB demo project based on the CIS-DMS-421 assignment and the dashboard screenshot. Includes sign-in, responsive sidebar, dashboard metrics, applicant registration/search/management, card issuance tracking, role-based access, and footer.

## Requirements
- PHP 8.2+ with PDO MySQL extension
- MySQL 8+ or MariaDB 10.4+
- Apache (XAMPP/WAMP/LAMP) or PHP's local development server

## Setup (XAMPP)
1. Copy this folder to `C:\xampp\htdocs\nrb_registration_system`.
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin and import `database/schema.sql`.
4. If needed, edit database credentials in `config.php` or set `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASS` environment variables.
5. Visit `http://localhost/nrb_registration_system/setup_admin.php`.
6. Create the first administrator account using a strong password (10+ characters).
7. Sign in at `http://localhost/nrb_registration_system/login.php`.
8. After successfully creating the administrator, remove or restrict access to `setup_admin.php` on any deployed environment.

## Roles
- **Administrator:** full access, user creation, and applicant deletion.
- **Officer:** register/update applicants and manage cards.
- **Viewer:** read-only applicant/card access.

## Included features
- Dashboard counts and recent registrations
- Applicant registration with server-side validation
- Search by name, National ID, or phone; filter by status
- Applicant detail view and controlled status/ID updates
- ID card status, issue date, expiry date, and card number management
- PDO prepared statements, HTML output escaping, CSRF tokens, password hashing, and role checks
- Responsive sidebar and mobile navigation

## Notes
- No real citizen records or passwords are included.
- New applicant records get a placeholder card number `NRB-PENDING-{record id}`. Replace it with an official card number only after the proper verification and issuance workflow.
- This is an academic demonstration, not a certified production government identity system. Before production, add audit logging, account lockout/rate limiting, password reset, HTTPS, backups, formal privacy controls, and security testing.
- The UI date and example names shown in the reference screenshot are not preloaded as real records. The database only seeds registration centres.
