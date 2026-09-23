<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

$page_title = "Dashboard";

// Fetch Stats
$total_employees = $pdo->query("SELECT COUNT(*) FROM employees")->fetchColumn();
$active_employees = $pdo->query("SELECT COUNT(*) FROM employees WHERE status = 'Active'")->fetchColumn();
$inactive_employees = $pdo->query("SELECT COUNT(*) FROM employees WHERE status = 'Inactive'")->fetchColumn();
$total_departments = $pdo->query("SELECT COUNT(*) FROM departments")->fetchColumn();

// Fetch Recent Employees
$recent_employees = $pdo->query("
    SELECT e.first_name, e.last_name, e.designation, d.department_name, e.created_at 
    FROM employees e 
    LEFT JOIN departments d ON e.department_id = d.id 
    ORDER BY e.id DESC LIMIT 5
")->fetchAll();

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';
?>

<div class="container-fluid p-4">
    
    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card dashboard-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box icon-primary me-3">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 fs-7 text-uppercase">Total Employees</h6>
                        <h3 class="mb-0 fw-bold"><?= $total_employees ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card dashboard-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box icon-success me-3">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 fs-7 text-uppercase">Active</h6>
                        <h3 class="mb-0 fw-bold"><?= $active_employees ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card dashboard-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box icon-danger me-3">
                        <i class="fa-solid fa-user-xmark"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 fs-7 text-uppercase">Inactive</h6>
                        <h3 class="mb-0 fw-bold"><?= $inactive_employees ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card dashboard-card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box icon-info me-3">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 fs-7 text-uppercase">Departments</h6>
                        <h3 class="mb-0 fw-bold"><?= $total_departments ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark fs-6">Recent Employees</h5>
                    <a href="employees.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Name</th>
                                    <th>Designation</th>
                                    <th>Department</th>
                                    <th>Joined Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_employees as $emp): ?>
                                <tr>
                                    <td class="ps-4 fw-medium text-dark">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2 text-primary fw-bold" style="width: 35px; height: 35px; font-size: 14px;">
                                                <?= strtoupper(substr($emp['first_name'], 0, 1) . substr($emp['last_name'], 0, 1)) ?>
                                            </div>
                                            <?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?>
                                        </div>
                                    </td>
                                    <td class="text-muted"><?= htmlspecialchars($emp['designation']) ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($emp['department_name'] ?? 'N/A') ?></span></td>
                                    <td class="text-muted"><?= date('M d, Y', strtotime($emp['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(empty($recent_employees)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No employees found.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-dark fs-6">Department Distribution</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 300px;">
                    <canvas id="deptChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Prepare data for the chart
$deptData = $pdo->query("
    SELECT d.department_name, COUNT(e.id) as emp_count 
    FROM departments d 
    LEFT JOIN employees e ON d.id = e.department_id 
    GROUP BY d.id
")->fetchAll();

$deptNames = [];
$deptCounts = [];
foreach($deptData as $row) {
    $deptNames[] = $row['department_name'];
    $deptCounts[] = $row['emp_count'];
}
?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('deptChart');
    if (ctx) {
        new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($deptNames) ?>,
                datasets: [{
                    data: <?= json_encode($deptCounts) ?>,
                    backgroundColor: [
                        '#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#64748b'
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                cutout: '75%'
            }
        });
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>
