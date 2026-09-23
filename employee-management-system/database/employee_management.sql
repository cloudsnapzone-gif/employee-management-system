CREATE DATABASE IF NOT EXISTS employee_management;
USE employee_management;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE departments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    department_name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_code VARCHAR(20) NOT NULL UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    gender ENUM('Male', 'Female', 'Other'),
    date_of_birth DATE,
    department_id INT,
    designation VARCHAR(100),
    joining_date DATE,
    salary DECIMAL(10,2),
    employment_type ENUM('Full Time', 'Part Time', 'Intern'),
    address TEXT,
    city VARCHAR(50),
    state VARCHAR(50),
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
);

-- Default admin user (password is 'password')
INSERT INTO users (username, password_hash) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert Departments
INSERT INTO departments (department_name, description) VALUES
('IT', 'Information Technology'),
('HR', 'Human Resources'),
('Finance', 'Finance and Accounts'),
('Marketing', 'Marketing and PR'),
('Sales', 'Sales Department'),
('Operations', 'Business Operations'),
('Administration', 'General Administration');

-- Insert 20 Sample Employees
INSERT INTO employees (employee_code, first_name, last_name, email, phone, gender, date_of_birth, department_id, designation, joining_date, salary, employment_type, address, city, state, status) VALUES
('EMP001', 'Rahul', 'Sharma', 'rahul.s@example.com', '9876543210', 'Male', '1990-05-15', 1, 'Software Developer', '2020-01-10', 75000.00, 'Full Time', '123 Tech Park', 'Bangalore', 'Karnataka', 'Active'),
('EMP002', 'Priya', 'Singh', 'priya.s@example.com', '9876543211', 'Female', '1992-08-20', 2, 'HR Executive', '2021-03-15', 55000.00, 'Full Time', '456 Business Road', 'Mumbai', 'Maharashtra', 'Active'),
('EMP003', 'Amit', 'Patel', 'amit.p@example.com', '9876543212', 'Male', '1988-11-05', 3, 'Accountant', '2019-06-01', 60000.00, 'Full Time', '789 Finance Hub', 'Ahmedabad', 'Gujarat', 'Active'),
('EMP004', 'Sneha', 'Reddy', 'sneha.r@example.com', '9876543213', 'Female', '1995-02-14', 4, 'Marketing Executive', '2022-01-20', 50000.00, 'Full Time', '321 Market Street', 'Hyderabad', 'Telangana', 'Active'),
('EMP005', 'Vikram', 'Malhotra', 'vikram.m@example.com', '9876543214', 'Male', '1985-09-30', 5, 'Sales Manager', '2018-04-10', 85000.00, 'Full Time', '654 Sales Ave', 'Delhi', 'Delhi', 'Active'),
('EMP006', 'Pooja', 'Iyer', 'pooja.i@example.com', '9876543215', 'Female', '1993-07-25', 1, 'System Administrator', '2020-11-05', 65000.00, 'Full Time', '987 IT Hub', 'Chennai', 'Tamil Nadu', 'Active'),
('EMP007', 'Karan', 'Johar', 'karan.j@example.com', '9876543216', 'Male', '1991-12-12', 6, 'Operations Manager', '2019-08-15', 80000.00, 'Full Time', '147 Ops Center', 'Pune', 'Maharashtra', 'Active'),
('EMP008', 'Neha', 'Gupta', 'neha.g@example.com', '9876543217', 'Female', '1994-04-18', 7, 'Office Administrator', '2021-09-01', 45000.00, 'Full Time', '258 Admin Block', 'Noida', 'Uttar Pradesh', 'Active'),
('EMP009', 'Rohan', 'Desai', 'rohan.d@example.com', '9876543218', 'Male', '1996-01-22', 1, 'Junior Developer', '2023-02-10', 40000.00, 'Full Time', '369 Startup Hub', 'Bangalore', 'Karnataka', 'Active'),
('EMP010', 'Anjali', 'Verma', 'anjali.v@example.com', '9876543219', 'Female', '1989-10-10', 2, 'HR Manager', '2017-05-20', 90000.00, 'Full Time', '159 Corporate Park', 'Gurgaon', 'Haryana', 'Active'),
('EMP011', 'Suresh', 'Nair', 'suresh.n@example.com', '9876543220', 'Male', '1987-03-08', 3, 'Senior Accountant', '2016-11-11', 75000.00, 'Full Time', '753 Trade Tower', 'Kochi', 'Kerala', 'Active'),
('EMP012', 'Meera', 'Rajput', 'meera.r@example.com', '9876543221', 'Female', '1997-06-30', 4, 'Social Media Manager', '2022-07-15', 55000.00, 'Full Time', '852 Media House', 'Jaipur', 'Rajasthan', 'Active'),
('EMP013', 'Rajesh', 'Kumar', 'rajesh.k@example.com', '9876543222', 'Male', '1990-11-25', 5, 'Sales Executive', '2021-01-10', 45000.00, 'Full Time', '951 Business Center', 'Lucknow', 'Uttar Pradesh', 'Active'),
('EMP014', 'Kavita', 'Mishra', 'kavita.m@example.com', '9876543223', 'Female', '1992-02-28', 6, 'Project Coordinator', '2020-03-01', 60000.00, 'Full Time', '357 Ops Tower', 'Indore', 'Madhya Pradesh', 'Active'),
('EMP015', 'Arun', 'Bansal', 'arun.b@example.com', '9876543224', 'Male', '1986-08-14', 7, 'Facility Manager', '2015-09-10', 70000.00, 'Full Time', '456 Admin Complex', 'Chandigarh', 'Punjab', 'Active'),
('EMP016', 'Deepika', 'Padukone', 'deepika.p@example.com', '9876543225', 'Female', '1995-12-05', 1, 'UI/UX Designer', '2021-06-20', 65000.00, 'Full Time', '789 Design Studio', 'Bangalore', 'Karnataka', 'Active'),
('EMP017', 'Sunil', 'Shetty', 'sunil.s@example.com', '9876543226', 'Male', '1988-04-12', 2, 'Recruitment Specialist', '2019-02-15', 50000.00, 'Full Time', '123 Talent Hub', 'Mumbai', 'Maharashtra', 'Active'),
('EMP018', 'Anita', 'Bose', 'anita.b@example.com', '9876543227', 'Female', '1991-07-19', 3, 'Financial Analyst', '2020-10-10', 80000.00, 'Full Time', '456 Finance Park', 'Kolkata', 'West Bengal', 'Inactive'),
('EMP019', 'Manoj', 'Tiwari', 'manoj.t@example.com', '9876543228', 'Male', '1994-09-02', 4, 'Content Writer', '2022-04-05', 40000.00, 'Part Time', '789 Writers Block', 'Patna', 'Bihar', 'Active'),
('EMP020', 'Ritu', 'Agarwal', 'ritu.a@example.com', '9876543229', 'Female', '1998-01-15', 1, 'Software Tester', '2023-07-01', 35000.00, 'Intern', '321 QA Lab', 'Pune', 'Maharashtra', 'Active');
