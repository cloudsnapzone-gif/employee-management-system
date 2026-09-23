<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

$page_title = "Reports";

// Fetch Stats
$total_employees = $pdo->query("SELECT COUNT(*) FROM employees")->fetchColumn();
$active_employees = $pdo->query("SELECT COUNT(*) FROM employees WHERE status = 'Active'")->fetchColumn();
$inactive_employees = $pdo->query("SELECT COUNT(*) FROM employees WHERE status = 'Inactive'")->fetchColumn();

// Department-wise
$deptData = $pdo->query("
    SELECT d.department_name, COUNT(e.id) as emp_count 
    FROM departments d 
    LEFT JOIN employees e ON d.id = e.department_id 
    GROUP BY d.id
    ORDER BY emp_count DESC
")->fetchAll();

// Employment Type
$typeData = $pdo->query("
    SELECT employment_type, COUNT(*) as emp_count 
    FROM employees 
    GROUP BY employment_type
    ORDER BY emp_count DESC
")->fetchAll();

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';
?>

<div class="container-fluid p-4" id="report-container">
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <h4 class="fw-bold mb-0 text-dark">Employee Reports</h4>
        <button onclick="window.print()" class="btn btn-primary"><i class="fa-solid fa-print me-2"></i>Print Report</button>
    </div>

    <!-- Print Header (Hidden on screen) -->
    <div class="d-none d-print-block text-center mb-5">
        <h2>Employee Management System</h2>
        <h4 class="text-muted">Summary Report</h4>
        <p>Generated on: <?= date('Y-m-d H:i:s') ?></p>
        <hr>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-primary text-white">
                <div class="card-body text-center p-4">
                    <i class="fa-solid fa-users fs-1 mb-3 opacity-75"></i>
                    <h6 class="text-uppercase mb-1 fw-medium">Total Employees</h6>
                    <h2 class="mb-0 fw-bold"><?= $total_employees ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-success text-white">
                <div class="card-body text-center p-4">
                    <i class="fa-solid fa-user-check fs-1 mb-3 opacity-75"></i>
                    <h6 class="text-uppercase mb-1 fw-medium">Active Employees</h6>
                    <h2 class="mb-0 fw-bold"><?= $active_employees ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-danger text-white">
                <div class="card-body text-center p-4">
                    <i class="fa-solid fa-user-xmark fs-1 mb-3 opacity-75"></i>
                    <h6 class="text-uppercase mb-1 fw-medium">Inactive Employees</h6>
                    <h2 class="mb-0 fw-bold"><?= $inactive_employees ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Department-wise Employees</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Department</th>
                                <th class="text-end pe-4">Employee Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($deptData as $d): ?>
                            <tr>
                                <td class="ps-4 fw-medium"><?= htmlspecialchars($d['department_name']) ?></td>
                                <td class="text-end pe-4">
                                    <span class="badge bg-light text-dark border px-3 py-2"><?= $d['emp_count'] ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Employment Type Distribution</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Employment Type</th>
                                <th class="text-end pe-4">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($typeData as $t): ?>
                            <tr>
                                <td class="ps-4 fw-medium"><?= htmlspecialchars($t['employment_type'] ?: 'N/A') ?></td>
                                <td class="text-end pe-4">
                                    <span class="badge bg-light text-dark border px-3 py-2"><?= $t['emp_count'] ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Print Styles -->
<style>
@media print {
    body { background-color: #fff; }
    #sidebar-wrapper, .navbar, .btn { display: none !important; }
    #page-content-wrapper { width: 100%; margin: 0; padding: 0; }
    .card { box-shadow: none !important; border: 1px solid #ddd !important; }
    .bg-primary, .bg-success, .bg-danger { color: #000 !important; background-color: #fff !important; }
}
</style>

<?php require_once 'includes/footer.php'; ?>
