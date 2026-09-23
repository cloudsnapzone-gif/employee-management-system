<?php
session_start();
require_once '../includes/auth.php'; // ensure logged in
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['action'])) {
    header("Location: ../departments.php");
    exit();
}

$action = $_POST['action'];

if ($action === 'add') {
    $name = trim($_POST['department_name'] ?? '');
    $desc = trim($_POST['description'] ?? '');

    if (empty($name)) {
        $_SESSION['errors'] = ["Department name is required."];
    } else {
        try {
            // check duplicate
            $stmt = $pdo->prepare("SELECT id FROM departments WHERE department_name = ?");
            $stmt->execute([$name]);
            if ($stmt->fetch()) {
                $_SESSION['errors'] = ["Department name already exists."];
            } else {
                $ins = $pdo->prepare("INSERT INTO departments (department_name, description) VALUES (?, ?)");
                $ins->execute([$name, $desc]);
                $_SESSION['success_msg'] = "Department added successfully.";
            }
        } catch (PDOException $e) {
            $_SESSION['errors'] = ["Database error: " . $e->getMessage()];
        }
    }
} elseif ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        try {
            // Check if employees exist in this department
            $check = $pdo->prepare("SELECT COUNT(*) FROM employees WHERE department_id = ?");
            $check->execute([$id]);
            if ($check->fetchColumn() > 0) {
                $_SESSION['errors'] = ["Cannot delete department because employees are currently assigned to it."];
            } else {
                $del = $pdo->prepare("DELETE FROM departments WHERE id = ?");
                $del->execute([$id]);
                $_SESSION['success_msg'] = "Department deleted successfully.";
            }
        } catch (PDOException $e) {
            $_SESSION['errors'] = ["Database error: " . $e->getMessage()];
        }
    }
}

header("Location: ../departments.php");
exit();
?>
