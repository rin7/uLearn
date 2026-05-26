# uLearn

uLearn is a web-based Learning Management System (LMS) built as an Aptech final project (2005).
It is written in PHP with a MySQL database and is designed to run on XAMPP.

## Features

- Student registration and login
- Course listing and detail pages
- Course enrollment
- Chapter/lesson content per course
- Multiple-choice quizzes with automatic scoring
- Admin panel: manage courses and users
- Dashboard statistics (students, courses, enrollments)

## Requirements

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP 7.4+)
- Web browser

## Setup

1. **Copy files** – Place the `ulearn/` folder inside your XAMPP `htdocs` directory.

2. **Create the database** – Open phpMyAdmin (`http://localhost/phpmyadmin`) and import `db/schema.sql`.
   This creates the `ulearn` database, all tables, and inserts sample data.

3. **Configure database** – Edit `includes/config.php` if your MySQL username/password differ from the defaults (`root` / empty password).

4. **Open the site** – Navigate to `http://localhost/ulearn/` in your browser.

## Default Admin Account

| Field    | Value    |
|----------|----------|
| Username | `admin`  |
| Password | `password` |

> Change the admin password after your first login.

## Project Structure

```
ulearn/
├── index.php            # Homepage
├── login.php            # Login page
├── register.php         # Student registration
├── logout.php           # Session logout
├── courses.php          # All courses listing
├── course_detail.php    # Single course with chapters and quizzes
├── quiz.php             # Take a quiz
├── admin/
│   ├── index.php        # Admin dashboard
│   ├── manage_courses.php
│   ├── add_course.php
│   ├── edit_course.php
│   └── manage_users.php
├── includes/
│   ├── config.php       # Database connection settings
│   ├── functions.php    # Shared helper functions
│   ├── header.php       # Page header/nav
│   └── footer.php       # Page footer
├── css/
│   └── style.css        # Stylesheet
└── db/
    └── schema.sql       # Database schema + seed data
```
