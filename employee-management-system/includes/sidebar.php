<?php
// includes/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar -->
<div id="sidebar-wrapper">
    <div class="sidebar-heading">
        <i class="fa-solid fa-users-gear me-2 text-primary"></i> EMS Admin
    </div>
    <div class="list-group list-group-flush mt-3">
        <a href="dashboard.php" class="list-group-item list-group-item-action <?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-chart-line"></i> Dashboard
        </a>
        <a href="employees.php" class="list-group-item list-group-item-action <?= in_array($current_page, ['employees.php', 'add_employee.php', 'edit_employee.php', 'view_employee.php']) ? 'active' : '' ?>">
            <i class="fa-solid fa-users"></i> Employees
        </a>
        <a href="add_employee.php" class="list-group-item list-group-item-action <?= $current_page == 'add_employee.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-user-plus"></i> Add Employee
        </a>
        <a href="departments.php" class="list-group-item list-group-item-action <?= $current_page == 'departments.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-building"></i> Departments
        </a>
        <a href="reports.php" class="list-group-item list-group-item-action <?= $current_page == 'reports.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-chart-bar"></i> Reports
        </a>
        <a href="settings.php" class="list-group-item list-group-item-action <?= $current_page == 'settings.php' ? 'active' : '' ?>">
            <i class="fa-solid fa-gear"></i> Settings
        </a>
        <a href="logout.php" class="list-group-item list-group-item-action mt-5 text-danger">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
    </div>
</div>
<!-- /#sidebar-wrapper -->
