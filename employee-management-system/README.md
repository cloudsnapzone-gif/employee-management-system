# Employee Management System

A complete, professional, responsive Employee Management System built with PHP, MySQL, HTML, CSS, and JavaScript.

## Features
- **Dashboard**: Overview of total employees, departments, and active/inactive statuses.
- **Employee CRUD**: Add, view, edit, and delete employee records with profile image uploads.
- **Search & Filters**: Search by name/ID/email and filter by department, status, and employment type.
- **Department Management**: Add and delete company departments.
- **Reports**: View summary reports and print them.
- **Responsive Design**: Works perfectly on mobile, tablet, and desktop devices.
- **Secure**: Uses PDO prepared statements to prevent SQL injection and `password_hash()` for secure authentication.

## Requirements
- **XAMPP** (or any server stack with Apache and MySQL)
- **PHP 8.0+**
- **MySQL**

## Installation Instructions

1. **Install XAMPP**
   - Download and install XAMPP if you haven't already.
   
2. **Start Services**
   - Open the XAMPP Control Panel and start both **Apache** and **MySQL**.

3. **Copy the Project**
   - Copy the entire `employee-management-system` folder into your XAMPP `htdocs` directory.
   - Example path: `C:\xampp\htdocs\employee-management-system`

4. **Setup the Database**
   - Open your browser and navigate to `http://localhost/phpmyadmin/`
   - You don't need to create the database manually; the SQL file will do it.
   - Go to the **Import** tab.
   - Choose the file located at: `employee-management-system/database/employee_management.sql`
   - Click **Import** (or **Go**).
   - *This will create the database `employee_management`, the necessary tables, and insert 20 sample employees and the default admin account.*

5. **Verify Configuration**
   - Open `config/database.php` in a text editor.
   - Ensure the database credentials match your setup (default XAMPP settings are root user with no password).
   ```php
   $host = 'localhost';
   $dbname = 'employee_management';
   $username = 'root';
   $password = ''; // Leave empty for default XAMPP
   ```

6. **Run the Project**
   - Open your browser and go to: `http://localhost/employee-management-system/`
   - You will be redirected to the login page.

## Default Administrator Login
- **Username:** `admin`
- **Password:** `password`

*Note: Once logged in, it is highly recommended that you change your password from the Settings page.*

## Troubleshooting
- **Database Connection Error:** Ensure MySQL is running in XAMPP and `config/database.php` has the correct `username` and `password`.
- **Image Upload Issues:** Ensure the `assets/images/uploads/` directory has write permissions.

## Technologies Used
- Backend: PHP (PDO)
- Database: MySQL
- Frontend: HTML5, CSS3, JavaScript
- Framework: Bootstrap 5
- Icons: Font Awesome
- Charts: Chart.js
