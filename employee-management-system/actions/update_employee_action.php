<?php
session_start();
require_once '../includes/auth.php'; // ensure logged in
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
    header("Location: ../employees.php");
    exit();
}

$id = (int)$_POST['id'];

// Check if employee exists
$stmt = $pdo->prepare("SELECT profile_image FROM employees WHERE id = ?");
$stmt->execute([$id]);
$current_emp = $stmt->fetch();

if (!$current_emp) {
    header("Location: ../employees.php");
    exit();
}

// Helper to sanitize input
function sanitize($data) {
    return htmlspecialchars(trim($data));
}

$errors = [];
$data = [
    'id' => $id,
    'first_name' => sanitize($_POST['first_name'] ?? ''),
    'last_name' => sanitize($_POST['last_name'] ?? ''),
    'gender' => $_POST['gender'] ?? 'Male',
    'date_of_birth' => !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null,
    'email' => filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL),
    'phone' => sanitize($_POST['phone'] ?? ''),
    'address' => sanitize($_POST['address'] ?? ''),
    'city' => sanitize($_POST['city'] ?? ''),
    'state' => sanitize($_POST['state'] ?? ''),
    'employee_code' => sanitize($_POST['employee_code'] ?? ''),
    'department_id' => !empty($_POST['department_id']) ? $_POST['department_id'] : null,
    'designation' => sanitize($_POST['designation'] ?? ''),
    'joining_date' => !empty($_POST['joining_date']) ? $_POST['joining_date'] : null,
    'salary' => !empty($_POST['salary']) ? $_POST['salary'] : null,
    'employment_type' => $_POST['employment_type'] ?? 'Full Time',
    'status' => $_POST['status'] ?? 'Active'
];

// Validations
if (empty($data['first_name'])) $errors[] = "First name is required.";
if (empty($data['last_name'])) $errors[] = "Last name is required.";
if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
if (empty($data['employee_code'])) $errors[] = "Employee code is required.";

// Check duplicates for other users
if (empty($errors)) {
    // Check employee code
    $stmt = $pdo->prepare("SELECT id FROM employees WHERE employee_code = ? AND id != ?");
    $stmt->execute([$data['employee_code'], $id]);
    if ($stmt->fetch()) $errors[] = "Employee code already exists.";

    // Check email
    $stmt = $pdo->prepare("SELECT id FROM employees WHERE email = ? AND id != ?");
    $stmt->execute([$data['email'], $id]);
    if ($stmt->fetch()) $errors[] = "Email address already exists.";
}

// Handle Image Upload
$profile_image = $current_emp['profile_image']; // default to current
if (empty($errors) && isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
    $allowed = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
    $filename = $_FILES['profile_image']['name'];
    $filetype = $_FILES['profile_image']['type'];
    $filesize = $_FILES['profile_image']['size'];

    // Verify file extension
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!array_key_exists(strtolower($ext), $allowed)) {
        $errors[] = "Please select a valid file format (JPG, PNG, WEBP).";
    }

    // Verify file size - 2MB maximum
    $maxsize = 2 * 1024 * 1024;
    if ($filesize > $maxsize) {
        $errors[] = "File size is larger than the allowed limit (2MB).";
    }

    // Verify MIME type
    if (in_array($filetype, $allowed)) {
        if(empty($errors)){
            $new_filename = 'employee_' . time() . '_' . rand(1000,9999) . '.' . $ext;
            $upload_path = '../assets/images/uploads/' . $new_filename;
            
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                $profile_image = $new_filename;
                // Optionally delete old image here
                if($current_emp['profile_image'] && file_exists('../assets/images/uploads/'.$current_emp['profile_image'])) {
                    unlink('../assets/images/uploads/'.$current_emp['profile_image']);
                }
            } else {
                $errors[] = "Failed to upload image.";
            }
        }
    } else {
        $errors[] = "There was a problem with the file format.";
    }
}

// Update DB if no errors
if (empty($errors)) {
    try {
        $sql = "UPDATE employees SET 
            employee_code = :employee_code, 
            first_name = :first_name, 
            last_name = :last_name, 
            email = :email, 
            phone = :phone, 
            gender = :gender, 
            date_of_birth = :date_of_birth, 
            department_id = :department_id, 
            designation = :designation, 
            joining_date = :joining_date, 
            salary = :salary, 
            employment_type = :employment_type, 
            address = :address, 
            city = :city, 
            state = :state, 
            status = :status, 
            profile_image = :profile_image
            WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $data['profile_image'] = $profile_image;
        $stmt->execute($data);
        
        $_SESSION['success_msg'] = "Employee updated successfully.";
        header("Location: ../employees.php");
        exit();

    } catch (PDOException $e) {
        $errors[] = "Database error: " . $e->getMessage();
    }
}

// If errors, redirect back with data
$_SESSION['errors'] = $errors;
$_SESSION['old'] = $_POST;
header("Location: ../edit_employee.php?id=" . $id);
exit();
?>
