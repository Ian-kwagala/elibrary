<?php
require_once '../../includes/config.php';

// Security check
if (!isLoggedIn() || isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL);
}

if (isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $user_id = $_SESSION['user_id'];

    // --- Sanitize all inputs ---
    $full_name = sanitize($_POST['full_name']);
    $student_number = sanitize($_POST['student_number']);
    $email = sanitize($_POST['email']);
    $course_selection = sanitize($_POST['course']);
    $other_course = sanitize($_POST['other_course'] ?? '');
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // --- Define the list of approved courses (must match the dashboard) ---
    $approved_courses = [
        "Computer Science", "Business Administration", "Law",
        "Information Technology", "Education", "Social Sciences"
    ];

    // --- Validation ---
    
    // 1. Password validation
    if (!empty($new_password)) {
        if ($new_password !== $confirm_password) {
            $_SESSION['error_message'] = "New passwords do not match.";
            redirect(BASE_URL . '/student/dashboard.php');
        }
        if (strlen($new_password) < 6) {
            $_SESSION['error_message'] = "Password must be at least 6 characters long.";
            redirect(BASE_URL . '/student/dashboard.php');
        }
    }

    // 2. Course validation
    $final_course = $course_selection;
    if ($course_selection === 'Other') {
        if (empty($other_course)) {
            $_SESSION['error_message'] = "Please specify your course if you select 'Other'.";
            redirect(BASE_URL . '/student/dashboard.php');
        }
        $final_course = $other_course;
    } elseif (!in_array($course_selection, $approved_courses)) {
        $_SESSION['error_message'] = "Invalid course selected. Please choose from the list.";
        redirect(BASE_URL . '/student/dashboard.php');
    }

    // 3. Check for duplicate Email or Student Number (excluding the current user)
    $stmt = $pdo->prepare("SELECT id FROM users WHERE (email = :email OR student_number = :student_number) AND id != :user_id");
    $stmt->execute([
        'email' => $email,
        'student_number' => $student_number,
        'user_id' => $user_id
    ]);
    if ($stmt->fetch()) {
        $_SESSION['error_message'] = "The email or student number you entered is already in use by another account.";
        redirect(BASE_URL . '/student/dashboard.php');
    }

    // --- Build the SQL Query ---
    $sql = "UPDATE users SET full_name = :full_name, student_number = :student_number, email = :email, course = :course";
    $params = [
        'full_name' => $full_name,
        'student_number' => $student_number,
        'email' => $email,
        'course' => $final_course,
        'id' => $user_id
    ];

    // If a new password was provided, add it to the query
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql .= ", password = :password";
        $params['password'] = $hashed_password;
    }

    $sql .= " WHERE id = :id";

    // --- Execute the update ---
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        // Update session name if it changed
        $_SESSION['user_name'] = $full_name; 
        
        $_SESSION['success_message'] = "Profile updated successfully!";
        create_notification($pdo, $user_id, "Your account details were successfully updated.");

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Failed to update profile. Please try again.";
        error_log($e->getMessage()); // Log error for admin
    }

    redirect(BASE_URL . '/student/dashboard.php');

} else {
    // Redirect if the form action is not correct
    redirect(BASE_URL . '/student/dashboard.php');
}