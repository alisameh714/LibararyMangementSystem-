# 📚 Library Management System

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)
![HTML](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)

A full-stack web-based **Library Management System** built with PHP and SQLite. Features role-based access control, book uploads, an admin approval workflow, a comment system, and a modern responsive UI.

---

## ✨ Features

- 🔐 **Authentication** — Secure login & registration with bcrypt password hashing
- 👥 **Role-Based Access** — Admin and User roles with different permissions
- 📖 **Book Management** — Upload books with PDF files and cover images
- ✅ **Approval Workflow** — Admin approves/rejects uploaded books before they go live
- 🔍 **Search & Filter** — Filter books by title, author, category, language, and price
- 💬 **Comment System** — Users can leave comments on book detail pages
- 🛡️ **CSRF Protection** — All forms are protected against cross-site request forgery
- 📱 **Responsive Design** — Works on desktop and mobile devices

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP 8.x |
| Database | SQLite (auto-initialized) |
| Frontend | HTML5, CSS3, JavaScript |
| Icons | Font Awesome 6 |
| Server | PHP Built-in Server |

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.x installed on your machine
- No additional tools required — SQLite is built into PHP

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/alisameh714/LibararyMangementSystem-.git
cd LibararyMangementSystem-
```

2. **Start the server**
```bash
php -S localhost:8000
```
Or on Windows, double-click **`start_server.bat`**

3. **Open your browser**
```
http://localhost:8000
```

The database is created **automatically** on first run — no setup needed.

---

## 👤 Default Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | alisameh714@gmail.com | admin123 |
| User | user1@library.com | user1pass |
| User | user2@library.com | user2pass |

---

## 📁 Project Structure

```
├── admin/                  # Admin panel pages
│   ├── manage_users.php
│   ├── manage_books.php
│   ├── manage_comments.php
│   └── pending_books.php
├── assets/
│   ├── css/                # Stylesheets
│   └── uploads/            # Book files & cover images
├── includes/               # Shared PHP includes
│   ├── db.php              # Database connection (SQLite)
│   ├── auth.php            # Session authentication
│   ├── header.php          # Navigation bar
│   └── csrf.php            # CSRF protection
├── sql/
│   ├── init.sql            # Database schema + seed data
│   └── schema.sql          # Table definitions
├── user/                   # User panel pages
│   ├── my_books.php
│   ├── upload_book.php
│   └── edit_book.php
├── landing.php             # Home / book listing page
├── dashboard.php           # Admin & user dashboard
├── login.php               # Login page
├── register.php            # Registration page
└── book_details.php        # Book detail & comments page
```

---

## 📸 Pages Overview

| Page | Description |
|------|-------------|
| Landing Page | Browse and search all approved books |
| Dashboard | Admin stats & quick actions / User actions |
| Book Details | Full book info, download, and comments |
| Admin Panel | Manage users, books, comments, and pending approvals |
| User Panel | Upload books, manage own submissions |

---

## 👨‍💻 Author

**Ali Edris**
- Student ID: 220209960
- University: Uskudar University
- Graduation Project
- Email: alisameh714@gmail.com
