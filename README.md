# TaskFlow

A luxury minimalist task management web application built with **Core PHP (MVC)** and **MySQL**.

Live demo: [mytaskflow.free.nf](https://mytaskflow.free.nf)

---

## Features

- Secure authentication (register, login, logout)
- Create, edit, delete, and search tasks
- Due dates with overdue indicators
- AJAX status toggle (no page reload)
- Pagination and filtering by status
- Luxury minimalist UI with responsive design
- CSRF protection and bcrypt password hashing
- Landing page with hero, features, and CTA sections

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Core PHP 8 (MVC pattern) |
| Database | MySQL / PDO |
| Frontend | Vanilla CSS, Vanilla JS |
| Fonts | Cormorant + DM Sans (Google Fonts) |
| Images | Unsplash |
| Server | Apache + mod_rewrite |

---

## Project Structure

```
taskflow/
├── public/               # Web root (point Apache here)
│   ├── index.php         # Front controller / router
│   └── .htaccess         # URL rewriting rules
├── controllers/
│   ├── AuthController.php
│   └── TaskController.php
├── models/
│   ├── User.php
│   └── Task.php
├── views/
│   ├── layouts/
│   │   ├── main.php      # Master layout
│   │   └── 404.php
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   ├── home/
│   │   └── landing.php
│   └── tasks/
│       ├── index.php
│       ├── create.php
│       └── edit.php
├── config/
│   └── db.php            # Database connection (update before running)
├── middleware/
│   └── auth.php          # Route protection
├── database.sql          # Schema + seed data
└── .htaccess             # Root rewrite rules
```

---

## Local Setup (XAMPP)

### 1. Requirements
- XAMPP with PHP 8.x and MySQL
- Apache mod_rewrite enabled

### 2. Clone the repository
```bash
git clone https://github.com/AmashiAththanagoda/taskflow.git
```

### 3. Copy to XAMPP
Copy the project folder to:
```
C:\xampp\htdocs\task-manager\
```

### 4. Add Virtual Host

In `C:\xampp\apache\conf\extra\httpd-vhosts.conf` add:
```apache
<VirtualHost *:80>
    ServerName taskflow.local
    DocumentRoot "C:/xampp/htdocs/task-manager/public"
    <Directory "C:/xampp/htdocs/task-manager/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Add to `C:\Windows\System32\drivers\etc\hosts`:
```
127.0.0.1  taskflow.local
```

### 5. Import database

Open **phpMyAdmin** → create a database named `task_manager` → import `database.sql`

### 6. Configure database

Edit `config/db.php` with your credentials:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'task_manager');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 7. Run

Restart Apache → visit **http://taskflow.local**

**Demo account:** username `demo_user` / password `password`

---


## License

MIT

