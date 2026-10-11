# Portfolio Showcase

A web-based platform developed to showcase student projects and Final Year Projects (FYP). The system allows students to submit and display their projects, while lecturers or administrators can manage project information and categories.

## Features

- **Student Dashboard** – Manage and view submitted projects.
- **Project Submission** – Submit project details and upload project files.
- **Project Showcase** – Display student projects in an organized interface.
- **Category Management** – Organize projects by categories.
- **Search Functionality** – Search for projects easily.
- **Admin Dashboard** – Manage users, categories, and project submissions.
- **Responsive Design** – Provide a user-friendly interface across different screen sizes.
- **Form Validation** – Validate user input to improve data accuracy.

## Technologies Used

- **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Database Connection:** PDO
- **Development Environment:** Laragon
- **Version Control:** Git and GitHub

## Installation and Setup

### Prerequisites

Make sure you have installed:

- Laragon
- PHP
- MySQL
- A web browser

### Step 1: Clone the Repository

Open your terminal and run:

```bash
git clone https://github.com/harreshrajakumar08-commits/portfolio-showcase.git
```

### Step 2: Move the Project Folder

Place the downloaded project inside your Laragon web directory:

```text
C:\laragon\www\portfolio-showcase
```

### Step 3: Start Laragon

Open Laragon and start the Apache and MySQL services.

### Step 4: Create the Database

1. Open phpMyAdmin or your preferred MySQL client.
2. Create the database named `assignment_system`.
3. Import the SQL file located at `database/assignment_system.sql`, if included in the repository.

### Step 5: Configure the Database

Open the database configuration file and update the connection settings according to your local environment.

Example PDO connection:

```php
<?php
$host = "localhost";
$dbname = "assignment_system";
$username = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );
} catch (PDOException $e) {
    die("Database connection failed.");
}
?>
```

Use the actual configuration file and variable names in your project. Do not replace existing configuration blindly.

### Step 6: Run the Application

Open your browser and visit:

```text
http://localhost/portfolio-showcase/
```

If your Laragon virtual host is configured, you can also use its assigned local domain.

## Project Structure

```text
portfolio-showcase/
├── admin_dashboard.php
├── student_dashboard.php
├── submit_project.php
├── search_projects.php
├── database/
│   └── assignment_system.sql
├── css/
├── js/
├── uploads/
├── README.md
└── ...
```

*Note: The structure above is illustrative. Update it to match the actual files and folders in the repository.*

## Contribution Guidelines

Contributions are welcome! You can help improve the project by:

- Enhancing the website's user interface and CSS styling.
- Improving responsive layouts.
- Fixing bugs and improving error handling.
- Enhancing project search and filtering.
- Improving form validation and usability.
- Updating documentation.

### How to Contribute

1. Fork this repository.
2. Clone your fork to your computer.
3. Create a new branch for your changes.
4. Make and test your changes.
5. Commit and push your changes to your fork.
6. Open a Pull Request for review.

Example commands:

```bash
git checkout -b improve-website-design
git add .
git commit -m "Improve website design and responsiveness"
git push -u origin improve-website-design
```

Then open a Pull Request against the original repository.

## Future Improvements

- Advanced project filtering and sorting.
- Pagination for project listings.
- Improved accessibility.
- Enhanced security and input validation.
- Better project preview and file management.

## Author

**Harresh Rajakumar**

GitHub: [@harreshrajakumar08-commits](https://github.com/harreshrajakumar08-commits)

## License

No license has been specified in this README. Please check the repository for an existing license before reusing or redistributing the code.
