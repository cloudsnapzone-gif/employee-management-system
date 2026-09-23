<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

$page_title = "Employees";

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search and Filters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$dept_filter = isset($_GET['department']) ? $_GET['department'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$type_filter = isset($_GET['employment_type']) ? $_GET['employment_type'] : '';

// Build Query
$query = "SELECT e.*, d.department_name FROM employees e LEFT JOIN departments d ON e.department_id = d.id WHERE 1=1";
$params = [];

if ($search !== '') {
    $query .= " AND (e.first_name LIKE :search OR e.last_name LIKE :search OR e.employee_code LIKE :search OR e.email LIKE :search OR e.phone LIKE :search OR e.designation LIKE :search)";
    $params['search'] = "%$search%";
}
if ($dept_filter !== '') {
    $query .= " AND e.department_id = :dept";
    $params['dept'] = $dept_filter;
}
if ($status_filter !== '') {
    $query .= " AND e.status = :status";
    $params['status'] = $status_filter;
}
if ($type_filter !== '') {
    $query .= " AND e.employment_type = :type";
    $params['type'] = $type_filter;
}

// Get Total for Pagination
$countQuery = str_replace("SELECT e.*, d.department_name", "SELECT COUNT(e.id)", $query);
$stmt = $pdo->prepare($countQuery);
$stmt->execute($params);
$total_records = $stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);

// Add Order and Limit
$query .= " ORDER BY e.id DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($query);
foreach($params as $key => &$val) {
    $stmt->bindParam($key, $val);
}
$stmt->execute();
$employees = $stmt->fetchAll();

// Fetch departments for filter dropdown
$departments = $pdo->query("SELECT id, department_name FROM departments ORDER BY department_name")->fetchAll();

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-dark">Employee Management</h4>
        <a href="add_employee.php" class="btn btn-primary"><i class="fa-solid fa-plus me-2"></i>Add Employee</a>
    </div>

    <!-- Filters and Search -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body">
            <form method="GET" action="employees.php" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search by name, ID, email..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="department" class="form-select">
                        <option value="">All Departments</option>
                        <?php foreach($departments as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= $dept_filter == $d['id'] ? 'selected' : '' ?>><?= htmlspecialchars($d['department_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Active" <?= $status_filter == 'Active' ? 'selected' : '' ?>>Active</option>
                        <option value="Inactive" <?= $status_filter == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="employment_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="Full Time" <?= $type_filter == 'Full Time' ? 'selected' : '' ?>>Full Time</option>
                        <option value="Part Time" <?= $type_filter == 'Part Time' ? 'selected' : '' ?>>Part Time</option>
                        <option value="Intern" <?= $type_filter == 'Intern' ? 'selected' : '' ?>>Intern</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="employees.php" class="btn btn-light border w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <?php if(isset($_SESSION['success_msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i> <?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Employees Table -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Employee</th>
                            <th>Contact</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($employees as $emp): ?>
                        <tr>
                            <td class="ps-4 fw-medium text-muted"><?= htmlspecialchars($emp['employee_code']) ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if(!empty($emp['profile_image'])): ?>
                                        <img src="assets/images/uploads/<?= htmlspecialchars($emp['profile_image']) ?>" class="rounded-circle me-3 object-fit-cover" width="40" height="40" alt="Profile">
                                    <?php else: ?>
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 40px; height: 40px; font-size: 14px;">
                                            <?= strtoupper(substr($emp['first_name'], 0, 1) . substr($emp['last_name'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?></div>
                                        <div class="text-muted small"><?= htmlspecialchars($emp['employment_type']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark"><i class="fa-regular fa-envelope me-2 text-muted"></i><?= htmlspecialchars($emp['email']) ?></div>
                                <div class="text-muted small"><i class="fa-solid fa-phone me-2 text-muted"></i><?= htmlspecialchars($emp['phone']) ?></div>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($emp['department_name'] ?? 'N/A') ?></span></td>
                            <td class="text-muted"><?= htmlspecialchars($emp['designation']) ?></td>
                            <td>
                                <?php if($emp['status'] == 'Active'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="fa-solid fa-circle text-success" style="font-size: 8px; margin-right: 4px;"></i> Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="fa-solid fa-circle text-danger" style="font-size: 8px; margin-right: 4px;"></i> Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group shadow-sm">
                                    <a href="view_employee.php?id=<?= $emp['id'] ?>" class="btn btn-sm btn-light border text-primary" title="View"><i class="fa-solid fa-eye"></i></a>
                                    <a href="edit_employee.php?id=<?= $emp['id'] ?>" class="btn btn-sm btn-light border text-info" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a href="delete_employee.php?id=<?= $emp['id'] ?>" class="btn btn-sm btn-light border text-danger" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($employees)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted mb-2"><i class="fa-solid fa-folder-open fs-1"></i></div>
                                <h6 class="fw-bold">No employees found</h6>
                                <p class="text-muted small">Try adjusting your search or filter criteria.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php if($total_pages > 1): ?>
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
            <span class="text-muted small">Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $total_records) ?> of <?= $total_records ?> entries</span>
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&department=<?= $dept_filter ?>&status=<?= $status_filter ?>&employment_type=<?= $type_filter ?>">Previous</a>
                    </li>
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&department=<?= $dept_filter ?>&status=<?= $status_filter ?>&employment_type=<?= $type_filter ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&department=<?= $dept_filter ?>&status=<?= $status_filter ?>&employment_type=<?= $type_filter ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
