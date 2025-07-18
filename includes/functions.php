<?php
// Function to check if a user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to check if the logged-in user is an admin
function isAdmin() {
    return (isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
}

// Function to redirect to a given page
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Function to sanitize user input
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to create a notification for a user
function create_notification($pdo, $user_id, $message) {
    $sql = "INSERT INTO notifications (user_id, message) VALUES (:user_id, :message)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id, 'message' => $message]);
}
?>