<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: employees.php");
    exit();
}

$id = (int)$_GET['id'];

// Fetch employee
$stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->execute([$id]);
$emp = $stmt->fetch();

if (!$emp) {
    header("Location: employees.php");
    exit();
}

$page_title = "Delete Employee";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';
?>

<div class="container-fluid p-4">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-3 border-top border-danger border-4">
                <div class="card-body p-5 text-center">
                    <div class="mb-4 text-danger">
                        <i class="fa-solid fa-triangle-exclamation" style="font-size: 60px;"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-3">Delete Employee?</h3>
                    <p class="text-muted mb-4 fs-5">
                        Are you sure you want to delete <strong><?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?></strong> (<?= htmlspecialchars($emp['employee_code']) ?>)? <br>This action cannot be undone.
                    </p>
                    
                    <form action="actions/delete_employee_action.php" method="POST">
                        <input type="hidden" name="id" value="<?= $emp['id'] ?>">
                        <div class="d-flex gap-3 justify-content-center">
                            <a href="employees.php" class="btn btn-light border px-4 py-2 fw-medium text-secondary">Cancel</a>
                            <button type="submit" class="btn btn-danger px-4 py-2 fw-medium">Yes, Delete Employee</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
