# Student Portfolio & FYP Showcase System

**DFP40443 Full Stack Web Development - Mini Project 2 (Session I 2026/2027)**
Politeknik Mersing, Jabatan Teknologi Maklumat & Komunikasi

A database-driven web application built with **PHP, MySQL (PDO), Bootstrap 5 and JavaScript**.
Students showcase their software projects and reports; lecturers/admins review submissions,
manage categories and download uploaded files.

## Database

- **Database name:** `assignment_system`
- **Tables:** `users`, `categories`, `projects`
- One user can submit many projects, and one category can contain many projects.

![ERD](docs/erd_assignment_system.png)

The full structure and sample data are in [`database/assignment_system.sql`](database/assignment_system.sql).

## Features

| Area | What it does |
|---|---|
| Authentication | Register and log in with `password_hash()` / `password_verify()`; Admin and Student roles; `$_SESSION`; redirect to login when the session is not valid |
| Authorization | Admin pages and Student pages are restricted by role |
| Form validation | Client-side (JavaScript) and server-side (PHP) validation on all forms, with error and success messages |
| Student | Dashboard with AJAX live search (fetch API), submit a project with file upload, view/edit/delete own submissions |
| Admin | Dashboard summary, create categories, view all submissions (SQL JOIN) and download uploaded files |
| File upload | PDF, DOCX, ZIP or TXT, maximum 5MB, saved in `uploads/` with the path stored in the database |
| Code quality | PDO prepared statements, reusable `header.php`, `footer.php` and `db.php`, external CSS and JS |

## Setup (Laragon on Windows)

1. Start **Apache** and **MySQL** in Laragon.
2. Clone the repository into Laragon's web folder:
   ```
   cd C:\laragon\www
   git clone https://github.com/harreshrajakumar08-commits/portfolio-showcase.git
   ```
3. Import the database in **HeidiSQL** (or phpMyAdmin): connect to your Laragon MySQL session, then
   **File > Run SQL file** and choose `database/assignment_system.sql`.
   The file creates the database, the tables and the sample data.
4. Check `db.php`. The defaults are:
   ```php
   $host = 'localhost';
   $dbname = 'assignment_system';
   $username = 'root';
   $password = '';
   ```
5. Open `http://localhost/portfolio-showcase/` in your browser (use your own folder name in the URL).

## Login accounts

| Role | Email | Password |
|---|---|---|
| Admin | `admin@pmj.edu.my` | `Admin@123` |
| Student | Create one on the Register page | |

## Troubleshooting

| Message | Fix |
|---|---|
| `Unknown database 'portfolio_db'` | `db.php` has the old name. Set `$dbname = 'assignment_system';` |
| `Unknown database 'assignment_system'` | The SQL file has not been imported yet (step 3) |
| `Access denied for user 'root'` | Put your MySQL password in `$password` in `db.php` |
| `Connection refused` | MySQL is not running in Laragon |
| `404 Not Found` | The folder is not directly inside `C:\laragon\www`, or the URL has the wrong folder name |

## Project structure

```
db.php  header.php  footer.php          shared components
index.php  register.php  logout.php     public pages (index.php is the login page)
dashboard.php  search_projects.php      student dashboard and AJAX endpoint
submit_project.php  my_portfolio.php  edit_project.php  delete_project.php
admin_dashboard.php  admin_categories.php  admin_submissions.php
css/style.css  js/main.js               styling and client-side validation
uploads/                                uploaded files (not tracked by Git)
database/assignment_system.sql          database export
docs/erd_assignment_system.png          entity relationship diagram
```

