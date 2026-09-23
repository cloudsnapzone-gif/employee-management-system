<?php
// config/database.php

$host = 'localhost';
$dbname = 'employee_management';
$username = 'root';
$password = ''; // Default XAMPP password is empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // In a production environment, you should not display the exact error.
    // However, for this project, it helps with debugging initial setup.
    die("ERROR: Could not connect to the database. " . $e->getMessage());
}
?>
