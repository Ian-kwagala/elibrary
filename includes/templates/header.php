<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>
    <!-- Google Fonts for a nice look -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <?php if (isset($extra_css)) echo $extra_css; // For page-specific CSS ?>
</head>
<body>
    <header class="main-header">
        <nav class="navbar">
            <a href="<?php echo BASE_URL; ?>/home.php" class="nav-logo">
                <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="Library Logo">
                <span>UMU Library</span>
            </a>
            <ul class="nav-menu">
                <li class="nav-item"><a href="<?php echo BASE_URL; ?>/home.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="<?php echo BASE_URL; ?>/books.php" class="nav-link">Books</a></li>
                <li class="nav-item"><a href="<?php echo BASE_URL; ?>/study_materials.php" class="nav-link">Study Materials</a></li>
                <li class="nav-item"><a href="<?php echo BASE_URL; ?>/contact.php" class="nav-link">Contact Us</a></li>
            </ul>
            <div class="nav-user-actions">
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="btn btn-primary">Admin Panel</a>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/student/dashboard.php" class="btn btn-primary">My Account</a>
                    <?php endif; ?>
                    <a href="<?php echo BASE_URL; ?>/auth/logout.php" class="btn btn-secondary">Logout</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/auth/login.php" class="btn btn-primary">Login</a>
                <?php endif; ?>
            </div>
            <div class="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </nav>
    </header>

    <main class="main-content">
        <!-- Page content will be injected here -->