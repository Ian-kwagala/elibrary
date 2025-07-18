<?php
require_once '../../includes/config.php';

// Security check
if (!isLoggedIn() || !isAdmin()) {
    redirect(BASE_URL);
}

// --- ADD, UPDATE, DELETE LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // --- ADD NEW USER ---
    if ($action === 'add_user') {
        // Sanitize all POST data
        $full_name = sanitize($_POST['full_name']);
        $email = sanitize($_POST['email']);
        $student_number = sanitize($_POST['student_number']);
        $course = sanitize($_POST['course']);
        $role = sanitize($_POST['role']);
        $password = $_POST['password'];

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // TODO: Add validation to check if email/student number already exists

        $sql = "INSERT INTO users (full_name, email, student_number, password, course, role) 
                VALUES (:full_name, :email, :student_number, :password, :course, :role)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'full_name' => $full_name, 'email' => $email, 'student_number' => $student_number,
            'password' => $hashed_password, 'course' => $course, 'role' => $role
        ]);
    }
    // --- UPDATE EXISTING USER ---
    elseif ($action === 'update_user') {
        $user_id = intval($_POST['user_id']);
        $full_name = sanitize($_POST['full_name']);
        $email = sanitize($_POST['email']);
        $student_number = sanitize($_POST['student_number']);
        $course = sanitize($_POST['course']);
        $role = sanitize($_POST['role']);
        $password = $_POST['password'];

        // Base query
        $sql = "UPDATE users SET full_name = :full_name, email = :email, student_number = :student_number, course = :course, role = :role";
        $params = [
            'full_name' => $full_name, 'email' => $email, 'student_number' => $student_number,
            'course' => $course, 'role' => $role, 'id' => $user_id
        ];

        // If a new password is provided, hash it and add it to the query
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password = :password";
            $params['password'] = $hashed_password;
        }

        $sql .= " WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }
    
    redirect(BASE_URL . '/admin/manage_users.php');
}


// --- DELETE USER LOGIC (from GET request) ---
if (isset($_GET['delete_id'])) {
    $id_to_delete = intval($_GET['delete_id']);

    // Prevent admin from deleting themselves
    if ($id_to_delete === $_SESSION['user_id']) {
        // Optional: Set a flash message here
        redirect(BASE_URL . '/admin/manage_users.php');
    } else {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id_to_delete]);
        redirect(BASE_URL . '/admin/manage_users.php');
    }
}