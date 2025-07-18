<?php
require_once '../includes/config.php';

if (isLoggedIn()) {
    $redirect_url = isAdmin() ? '/admin/dashboard.php' : '/student/dashboard.php';
    redirect(BASE_URL . $redirect_url);
}
$page_title = 'Sign Up';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/login.css">
</head>
<body class="login-body">

    <div class="form-container">
        <!-- New Structure -->
        <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="Logo" class="logo">
        <h1 class="main-title">Online Library System</h1>
        <h2>Create Account</h2>

        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<div class="error-message">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>

        <form action="auth_handler.php" method="POST">
            <input type="hidden" name="action" value="signup">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="full_name" placeholder="Full Name" required>
            </div>
             <div class="input-group">
                <i class="fas fa-id-card"></i>
                <input type="text" name="student_number" placeholder="Student Number" required>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="University Email (@umu.ac.ug)" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <button type="submit" class="form-btn">Sign Up</button>
            <div class="form-link">
                Already have an account? <a href="login.php">Login</a>
            </div>
        </form>
    </div>

</body>
</html>