<?php
require_once '../includes/config.php';
$page_title = 'Forgot Password';
$extra_css = '<link rel="stylesheet" href="' . BASE_URL . '/assets/css/login.css">';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... head content from login.php ... -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <?php echo $extra_css; ?>
</head>
<body class="login-body">
    <div class="form-container">
        <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="Logo" class="logo">
        <h2>Password Assistance</h2>
        <div class="info-message" style="background: rgba(0, 255, 255, 0.2); padding: 20px; border-radius: 10px; text-align: left;">
            <p>To reset your password, please contact the library IT support team.</p>
            <p>You can reach them via email at:</p>
            <p style="font-weight: 600; text-align: center; margin-top: 10px;">it.support.library@umu.ac.ug</p>
            <p>Please provide your full name and student number in your email for faster assistance.</p>
        </div>
        <div class="form-link" style="margin-top: 20px;">
            <a href="login.php">Back to Login</a>
        </div>
    </div>
</body>
</html>