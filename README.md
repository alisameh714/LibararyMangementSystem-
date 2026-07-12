# Library Management System

**Student:** Ali Edris
**Student ID:** 220209960
**University:** Uskudar University
**Course:** COME309 – Web Programming
**Email:** alisameh714@gmail.com

---

## Prerequisites

Ensure the following tools are installed on your system:
- **XAMPP** (or any LAMP/WAMP stack): Used to run PHP and MySQL locally.
- **Web Browser** (e.g., Chrome, Firefox).

---

## Setup Instructions

### 1. Clone or Download the Repository

Clone the repository using Git:
```bash
git clone https://github.com/your-repo/library-management-system.git
```
Or download and extract the ZIP file.

---

### 2. Move the Project to `htdocs`

Move the project folder to your XAMPP `htdocs` directory:
```
C:/xampp/htdocs/COME309_Project_Web_Programming
```

---

### 3. Set Up the Database

1. Open **phpMyAdmin**: `http://localhost/phpmyadmin`
2. Create a new database named: `library_db`
3. Import `sql/schema.sql` to create the tables.
4. Import `sql/data.sql` to load the sample data.

---

### 4. Configure the Database Connection

Open `includes/db.php` and confirm the settings match your environment:
```php
$host     = "localhost";
$user     = "root";
$password = "";
$dbname   = "library_db";
```

---

### 5. Run the Project

1. Start **Apache** and **MySQL** from the XAMPP Control Panel.
2. Open your browser and navigate to:
```
http://localhost/COME309_Project_Web_Programming/
```

---

### 6. Login Credentials

| Role  | Email                    | Password |
|-------|--------------------------|----------|
| Admin | alisameh714@gmail.com    | ali123   |
| User  | user1@library.com        | user123  |
| User  | user2@library.com        | user123  |

---

## Features

- User registration and login with hashed passwords
- Role-based access (Admin / User)
- Book upload with PDF and cover image
- Admin approval workflow for uploaded books
- Search and filter books by title, author, category, language, price, and pages
- Comment system on book detail pages
- Admin panel: manage users, books, and comments
- CSRF protection on all forms
- File upload validation (type and size)
