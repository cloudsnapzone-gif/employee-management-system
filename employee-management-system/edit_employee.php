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

$page_title = "Edit Employee";

// Fetch departments for dropdown
$departments = $pdo->query("SELECT id, department_name FROM departments ORDER BY department_name")->fetchAll();

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';

// If there was an error in submission, we use the old input. Otherwise we use DB data.
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? $emp;
unset($_SESSION['errors'], $_SESSION['old']);
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-dark">Edit Employee</h4>
        <a href="employees.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Back to List</a>
    </div>

    <?php if(!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <h6 class="alert-heading fw-bold"><i class="fa-solid fa-triangle-exclamation me-2"></i>Please fix the following errors:</h6>
            <ul class="mb-0">
                <?php foreach($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="actions/update_employee_action.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $emp['id'] ?>">
                
                <h5 class="text-primary border-bottom pb-2 mb-4">Personal Information</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-medium">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($old['first_name'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($old['last_name'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-medium">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="Male" <?= ($old['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= ($old['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= ($old['gender'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-medium">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control" value="<?= htmlspecialchars($old['date_of_birth'] ?? '') ?>">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-medium">Profile Image</label>
                        <div class="d-flex align-items-center">
                            <?php if(!empty($emp['profile_image'])): ?>
                                <img src="assets/images/uploads/<?= htmlspecialchars($emp['profile_image']) ?>" class="rounded-circle me-3 object-fit-cover shadow-sm" width="50" height="50" alt="Current Image">
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <input type="file" name="profile_image" class="form-control" accept="image/jpeg, image/png, image/webp">
                                <div class="form-text">Leave blank to keep current image. Accepted: JPG, PNG, WEBP. Max: 2MB.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="text-primary border-bottom pb-2 mb-4">Contact Information</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-medium">Address</label>
                        <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">City</label>
                        <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($old['city'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">State</label>
                        <input type="text" name="state" class="form-control" value="<?= htmlspecialchars($old['state'] ?? '') ?>">
                    </div>
                </div>

                <h5 class="text-primary border-bottom pb-2 mb-4">Employment Information</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Employee Code <span class="text-danger">*</span></label>
                        <input type="text" name="employee_code" class="form-control" value="<?= htmlspecialchars($old['employee_code'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Department</label>
                        <select name="department_id" class="form-select">
                            <option value="">Select Department</option>
                            <?php foreach($departments as $d): ?>
                                <option value="<?= $d['id'] ?>" <?= ($old['department_id'] ?? '') == $d['id'] ? 'selected' : '' ?>><?= htmlspecialchars($d['department_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Designation</label>
                        <input type="text" name="designation" class="form-control" value="<?= htmlspecialchars($old['designation'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Joining Date</label>
                        <input type="date" name="joining_date" class="form-control" value="<?= htmlspecialchars($old['joining_date'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Salary</label>
                        <input type="number" step="0.01" name="salary" class="form-control" value="<?= htmlspecialchars($old['salary'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Employment Type</label>
                        <select name="employment_type" class="form-select">
                            <option value="Full Time" <?= ($old['employment_type'] ?? '') == 'Full Time' ? 'selected' : '' ?>>Full Time</option>
                            <option value="Part Time" <?= ($old['employment_type'] ?? '') == 'Part Time' ? 'selected' : '' ?>>Part Time</option>
                            <option value="Intern" <?= ($old['employment_type'] ?? '') == 'Intern' ? 'selected' : '' ?>>Intern</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Status</label>
                        <select name="status" class="form-select">
                            <option value="Active" <?= ($old['status'] ?? '') == 'Active' ? 'selected' : '' ?>>Active</option>
                            <option value="Inactive" <?= ($old['status'] ?? '') == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top">
                    <a href="employees.php" class="btn btn-light border px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Update Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
