# GMC AJM Payroll

PHP and MySQL application for employee records, attendance, leave, loans, expenses, and payroll.

## Local setup

1. Place the project in your web server document root (for XAMPP, `htdocs/gmc_amj_payroll`).
2. Copy `config.example.php` to `config.php`.
3. Set the database hostname, username, password, database name, and port in `config.php`. Set `WEB_URL` to your application's URL.
4. Restore the application's database from your existing backup. This repository does not include a database dump.
5. Start Apache and MySQL, then open `http://localhost/gmc_amj_payroll/`.

The database connection uses `DB_PORT`; set this to the port used by your MySQL server.

Local credentials, runtime logs, server-specific PHP settings, and uploaded files are excluded from version control. Existing installations should retain their own `config.php` and uploads.
