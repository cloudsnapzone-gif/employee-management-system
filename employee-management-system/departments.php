<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

$page_title = "Departments";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';

// Fetch departments with employee count
$departments = $pdo->query("
    SELECT d.*, COUNT(e.id) as emp_count 
    FROM departments d 
    LEFT JOIN employees e ON d.id = e.department_id 
    GROUP BY d.id
    ORDER BY d.department_name
")->fetchAll();

$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-dark">Department Management</h4>
    </div>

    <?php if(isset($_SESSION['success_msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i> <?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <ul class="mb-0">
                <?php foreach($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Add Department Form -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Add New Department</h5>
                </div>
                <div class="card-body p-4">
                    <form action="actions/department_actions.php" method="POST">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Department Name <span class="text-danger">*</span></label>
                            <input type="text" name="department_name" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-medium">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Department</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Departments List -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">All Departments</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Department Name</th>
                                    <th>Description</th>
                                    <th>Employees</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($departments as $dept): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-dark"><?= htmlspecialchars($dept['department_name']) ?></td>
                                    <td class="text-muted text-truncate" style="max-width: 200px;"><?= htmlspecialchars($dept['description']) ?></td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1">
                                            <?= $dept['emp_count'] ?> Employees
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group shadow-sm">
                                            <!-- Delete Form -->
                                            <form action="actions/department_actions.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this department?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?= $dept['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(empty($departments)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No departments found.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
