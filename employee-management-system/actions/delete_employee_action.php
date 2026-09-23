<?php
session_start();
require_once '../includes/auth.php'; // ensure logged in
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
    header("Location: ../employees.php");
    exit();
}

$id = (int)$_POST['id'];

try {
    // Optional: get image path to delete it
    $stmt = $pdo->prepare("SELECT profile_image FROM employees WHERE id = ?");
    $stmt->execute([$id]);
    $emp = $stmt->fetch();
    
    if($emp) {
        // delete from DB
        $del = $pdo->prepare("DELETE FROM employees WHERE id = ?");
        $del->execute([$id]);
        
        // delete image file
        if (!empty($emp['profile_image']) && file_exists('../assets/images/uploads/' . $emp['profile_image'])) {
            unlink('../assets/images/uploads/' . $emp['profile_image']);
        }
        
        $_SESSION['success_msg'] = "Employee deleted successfully.";
    }

} catch (PDOException $e) {
    // Set a session error if it fails (e.g. FK constraint)
    $_SESSION['errors'] = ["Unable to delete employee. " . $e->getMessage()];
}

header("Location: ../employees.php");
exit();
?>
