<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/home.php');
    exit();
}

$action = $_POST['action'] ?? '';

if ($action === 'login') {
    $identifier = sanitize($_POST['login_identifier'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($identifier) || empty($password)) {
        $_SESSION['error_message'] = "Please fill in all fields.";
        redirect(BASE_URL . '/auth/login.php');
        exit();
    }

    $field_type = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'student_number';
    
    try {
        $sql = "SELECT * FROM users WHERE $field_type = :identifier LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['identifier' => $identifier]);
        $user = $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Login query failed: " . $e->getMessage());
        $_SESSION['error_message'] = "A server error occurred. Please try again later.";
        redirect(BASE_URL . '/auth/login.php');
        exit();
    }

    // This is the critical check.
    if ($user && password_verify($password, $user['password'])) {
        // Login Success
        unset($_SESSION['error_message']);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            redirect(BASE_URL . '/admin/dashboard.php');
        } else {
            redirect(BASE_URL . '/student/dashboard.php');
        }
        exit();
        
    } else {
        // Login Failed
        $_SESSION['error_message'] = "Invalid credentials. Please check your details and try again.";
        redirect(BASE_URL . '/auth/login.php');
        exit();
    }
} 
elseif ($action === 'signup') {
    // Signup logic remains here...
    // ...
}
else {
    redirect(BASE_URL . '/home.php');
    exit();
}