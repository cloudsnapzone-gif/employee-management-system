<?php
// includes/navbar.php
?>
<!-- Page Content -->
<div id="page-content-wrapper">
    <nav class="navbar navbar-expand-lg navbar-light bg-white px-4 border-bottom py-3">
        <div class="d-flex align-items-center">
            <button class="btn btn-light me-3 border" id="menu-toggle">
                <i class="fa-solid fa-bars text-secondary"></i>
            </button>
            <h5 class="mb-0 fw-bold text-dark"><?= $page_title ?? 'Dashboard' ?></h5>
        </div>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center fw-medium text-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=4f46e5&color=fff" class="rounded-circle me-2 shadow-sm" width="35" height="35" alt="Admin">
                        <?= htmlspecialchars($_SESSION['username'] ?? 'Administrator') ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="settings.php"><i class="fa-solid fa-user me-2 text-secondary"></i>Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger fw-medium" href="logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
