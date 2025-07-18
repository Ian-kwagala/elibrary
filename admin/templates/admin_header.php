<?php 
require_once __DIR__ . '/../../includes/config.php'; 

// Security checks
if (!isLoggedIn() || !isAdmin()) {
    redirect(BASE_URL);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?php echo SITE_NAME; ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    
    <!-- This is the corrected link to the dedicated admin stylesheet -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/admin/assets/css/admin_style.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <a href="<?php echo BASE_URL; ?>/admin/dashboard.php">
                    <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="Logo">
                    <h3>UMU E-Library</h3>
                </a>
            </div>
            <nav class="admin-sidebar-nav">
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>/admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/admin/manage_books.php"><i class="fas fa-book"></i> Manage Materials</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/admin/manage_users.php"><i class="fas fa-users"></i> Manage Users</a></li>
                    <li><hr style="border-color: rgba(255,255,255,0.1); margin: 10px 15px;"></li>
                    <li><a href="<?php echo BASE_URL; ?>/home.php" target="_blank"><i class="fas fa-eye"></i> View Site</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="admin-content">
            <!-- Page content from other admin files will be injected here -->