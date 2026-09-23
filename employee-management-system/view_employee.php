<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: employees.php");
    exit();
}

$id = (int)$_GET['id'];

// Fetch employee with department
$stmt = $pdo->prepare("
    SELECT e.*, d.department_name 
    FROM employees e 
    LEFT JOIN departments d ON e.department_id = d.id 
    WHERE e.id = ?
");
$stmt->execute([$id]);
$emp = $stmt->fetch();

if (!$emp) {
    header("Location: employees.php");
    exit();
}

$page_title = "View Employee: " . htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']);
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-dark">Employee Profile</h4>
        <div>
            <a href="employees.php" class="btn btn-secondary me-2"><i class="fa-solid fa-arrow-left me-2"></i>Back to Employees</a>
            <a href="edit_employee.php?id=<?= $emp['id'] ?>" class="btn btn-info text-white"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Employee</a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Profile Sidebar -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 text-center h-100">
                <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center">
                    <?php if(!empty($emp['profile_image'])): ?>
                        <img src="assets/images/uploads/<?= htmlspecialchars($emp['profile_image']) ?>" class="rounded-circle shadow-sm mb-3 object-fit-cover" width="120" height="120" alt="Profile">
                    <?php else: ?>
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-3 shadow-sm fw-bold" style="width: 120px; height: 120px; font-size: 40px;">
                            <?= strtoupper(substr($emp['first_name'], 0, 1) . substr($emp['last_name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    
                    <h4 class="fw-bold text-dark mb-1"><?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?></h4>
                    <p class="text-muted mb-2"><?= htmlspecialchars($emp['designation'] ?: 'No Designation') ?></p>
                    
                    <div class="mb-3">
                        <span class="badge bg-light text-dark border"><?= htmlspecialchars($emp['employee_code']) ?></span>
                        <span class="badge bg-light text-dark border ms-1"><?= htmlspecialchars($emp['department_name'] ?? 'No Dept') ?></span>
                    </div>

                    <?php if($emp['status'] == 'Active'): ?>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2"><i class="fa-solid fa-circle text-success" style="font-size: 8px; margin-right: 6px;"></i> ACTIVE</span>
                    <?php else: ?>
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2"><i class="fa-solid fa-circle text-danger" style="font-size: 8px; margin-right: 6px;"></i> INACTIVE</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body p-4">
                    
                    <h5 class="text-primary border-bottom pb-2 mb-4"><i class="fa-solid fa-user me-2"></i>Personal Information</h5>
                    <div class="row mb-4">
                        <div class="col-sm-4 mb-3">
                            <h6 class="text-muted mb-1 fs-7 text-uppercase">Gender</h6>
                            <div class="fw-medium text-dark"><?= htmlspecialchars($emp['gender'] ?? 'N/A') ?></div>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <h6 class="text-muted mb-1 fs-7 text-uppercase">Date of Birth</h6>
                            <div class="fw-medium text-dark"><?= !empty($emp['date_of_birth']) ? date('M d, Y', strtotime($emp['date_of_birth'])) : 'N/A' ?></div>
                        </div>
                    </div>

                    <h5 class="text-primary border-bottom pb-2 mb-4"><i class="fa-solid fa-address-book me-2"></i>Contact Information</h5>
                    <div class="row mb-4">
                        <div class="col-sm-6 mb-3">
                            <h6 class="text-muted mb-1 fs-7 text-uppercase">Email</h6>
                            <div class="fw-medium text-dark"><a href="mailto:<?= htmlspecialchars($emp['email']) ?>"><?= htmlspecialchars($emp['email']) ?></a></div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <h6 class="text-muted mb-1 fs-7 text-uppercase">Phone</h6>
                            <div class="fw-medium text-dark"><?= htmlspecialchars($emp['phone'] ?? 'N/A') ?></div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <h6 class="text-muted mb-1 fs-7 text-uppercase">Address</h6>
                            <div class="fw-medium text-dark">
                                <?= htmlspecialchars($emp['address'] ?? 'N/A') ?>
                                <?php if(!empty($emp['city']) || !empty($emp['state'])): ?>
                                    <br><?= htmlspecialchars(trim($emp['city'] . ', ' . $emp['state'], ', ')) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <h5 class="text-primary border-bottom pb-2 mb-4"><i class="fa-solid fa-briefcase me-2"></i>Employment Information</h5>
                    <div class="row mb-4">
                        <div class="col-sm-4 mb-3">
                            <h6 class="text-muted mb-1 fs-7 text-uppercase">Department</h6>
                            <div class="fw-medium text-dark"><?= htmlspecialchars($emp['department_name'] ?? 'N/A') ?></div>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <h6 class="text-muted mb-1 fs-7 text-uppercase">Designation</h6>
                            <div class="fw-medium text-dark"><?= htmlspecialchars($emp['designation'] ?? 'N/A') ?></div>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <h6 class="text-muted mb-1 fs-7 text-uppercase">Employment Type</h6>
                            <div class="fw-medium text-dark"><?= htmlspecialchars($emp['employment_type'] ?? 'N/A') ?></div>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <h6 class="text-muted mb-1 fs-7 text-uppercase">Joining Date</h6>
                            <div class="fw-medium text-dark"><?= !empty($emp['joining_date']) ? date('M d, Y', strtotime($emp['joining_date'])) : 'N/A' ?></div>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <h6 class="text-muted mb-1 fs-7 text-uppercase">Salary</h6>
                            <div class="fw-medium text-dark"><?= !empty($emp['salary']) ? '$' . number_format($emp['salary'], 2) : 'N/A' ?></div>
                        </div>
                    </div>

                    <div class="text-muted small mt-4 pt-3 border-top">
                        <i class="fa-regular fa-clock me-1"></i> Created: <?= date('M d, Y H:i', strtotime($emp['created_at'])) ?> 
                        | <i class="fa-solid fa-clock-rotate-left me-1 ms-2"></i> Last Updated: <?= date('M d, Y H:i', strtotime($emp['updated_at'])) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
