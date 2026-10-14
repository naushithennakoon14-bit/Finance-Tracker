# 💰 Finance Tracker

A web-based finance management application built with **PHP and MySQL** to help users manage their income and expenses, organize financial transactions, and generate reports.

## 📌 About the Project

Finance Tracker is a personal finance management web application developed to simplify income and expense tracking. It provides user account functionality, transaction management, and reporting features through a web interface.

This project was developed as a practical software development project to strengthen my skills in PHP, MySQL, database management, and web application development.

## ✨ Features

- **User Registration** — Create a user account.
- **User Login and Logout** — Access and exit the application.
- **Dashboard** — View financial information through a dashboard.
- **Transaction Management** — Manage income and expense records.
- **Financial Reports** — View transaction reports.
- **Excel Export** — Export report data to an Excel-compatible file.
- **Print Reports** — Print financial reports.
- **Profile Settings** — Manage available account settings.
- **Profile Pictures** — Support for user profile images.

*Note: Features depend on the current implementation of the application.*

## 🛠️ Technologies Used

- **Backend:** PHP
- **Database:** MySQL
- **Local Development Environment:** XAMPP
- **Version Control:** Git and GitHub

## 📂 Project Structure

```text
Finance-Tracker/
├── uploads/           # Uploaded images
├── dashboard.php      # Dashboard
├── database.sql       # Database schema and demo user
├── db.php             # Database connection
├── export_excel.php   # Excel export
├── login.php          # User login
├── logout.php         # User logout
├── print_report.php   # Report printing
├── register.php       # User registration
├── reports.php        # Financial reports
├── settings.php       # Account settings
└── transactions.php   # Transaction management
```

## ⚙️ Installation and Setup

### Prerequisites

Install the following before running the application:

- [XAMPP](https://www.apachefriends.org/)
- A web browser
- Git (optional, for cloning the repository)

### 1. Clone the Repository

Open a terminal in the XAMPP `htdocs` directory and run:

```bash
git clone https://github.com/naushithennakoon14-bit/Finance-Tracker.git
```

### 2. Move into the Project Directory

```bash
cd Finance-Tracker
```

### 3. Start XAMPP

Open the XAMPP Control Panel and start:

- Apache
- MySQL

### 4. Create the Database

1. Open `http://localhost/phpmyadmin/`.
2. Select the **Import** tab.
3. Choose the `database.sql` file from the project folder.
4. Click **Import** or **Go** to execute the SQL file.

The SQL script creates the `finance_tracker` database and its required tables.

### 5. Configure the Database Connection

Open `db.php` and configure the MySQL connection settings for your local XAMPP environment.

Use your own local database credentials. Do not publish real passwords or other secrets in the repository.

### 6. Run the Application

Open the following URL in your browser:

```text
http://localhost/Finance-Tracker/
```

If your project folder is named `finance` instead, use:

```text
http://localhost/finance/
```

## 🗄️ Database

The database schema includes the following tables:

- **users** — Stores user account information and profile picture references.
- **transactions** — Stores income and expense transaction details, including category, amount, description, and date.

The transactions table references the users table to associate transactions with user accounts.

## 🔒 Security Notes

- Do not commit database passwords, API keys, or other private credentials.
- Use secure password hashing and verify passwords safely.
- Use prepared statements for database queries.
- Ensure each user can access only their own financial records.
- Do not upload real users' personal or financial information.
- Treat the included demo account as a development-only account.

## 📸 Screenshots

Screenshots of the dashboard, transaction management, and reports can be added here to demonstrate the application's user interface.

## 🚀 Future Improvements

- Improve the dashboard with financial charts and summaries.
- Add advanced transaction filtering.
- Improve input validation and error handling.
- Strengthen authentication and session security.
- Enhance the user interface and mobile responsiveness.

## 👩‍💻 Author

**Nausha Thennakoon**

- GitHub: [@naushithennakoon14-bit](https://github.com/naushithennakoon14-bit)

---

*Developed as a personal finance management web application using PHP and MySQL.*