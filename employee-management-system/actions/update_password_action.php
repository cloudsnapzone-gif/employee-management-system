<?php
session_start();
require_once '../includes/auth.php'; // ensure logged in
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../settings.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$current = $_POST['current_password'] ?? '';
$new = $_POST['new_password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

$errors = [];

if (empty($current) || empty($new) || empty($confirm)) {
    $errors[] = "All fields are required.";
} elseif ($new !== $confirm) {
    $errors[] = "New password and confirm password do not match.";
} elseif (strlen($new) < 6) {
    $errors[] = "New password must be at least 6 characters long.";
}

if (empty($errors)) {
    try {
        $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($user && password_verify($current, $user['password_hash'])) {
            $new_hash = password_hash($new, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $update->execute([$new_hash, $user_id]);
            
            $_SESSION['success_msg'] = "Password updated successfully.";
        } else {
            $errors[] = "Current password is incorrect.";
        }
    } catch (PDOException $e) {
        $errors[] = "Database error: " . $e->getMessage();
    }
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
}

header("Location: ../settings.php");
exit();
?>
