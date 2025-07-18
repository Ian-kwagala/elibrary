<?php
require_once '../../includes/config.php';

// Security: Ensure user is logged in
if (!isLoggedIn()) {
    $_SESSION['error_message'] = "You must be logged in to borrow a book.";
    redirect(BASE_URL . '/auth/login.php');
}

// Security: Ensure it's not an admin trying to borrow
if (isAdmin()) {
    redirect(BASE_URL . '/admin/dashboard.php');
}

// Validate the material ID from the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect(BASE_URL . '/books.php');
}

$material_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Use a database transaction to ensure data integrity
$pdo->beginTransaction();

try {
    // 1. Check if the book is available and lock the row to prevent race conditions
    $stmt = $pdo->prepare("SELECT available_copies, material_type FROM materials WHERE id = :id FOR UPDATE");
    $stmt->execute(['id' => $material_id]);
    $material = $stmt->fetch();

    if (!$material || $material['material_type'] !== 'book' || $material['available_copies'] <= 0) {
        // Book is not available or doesn't exist
        $pdo->rollBack();
        redirect(BASE_URL . "/material_details.php?id=$material_id&status=borrow_error");
    }

    // 2. Check if the user has already borrowed this book and not returned it
    $stmt = $pdo->prepare("SELECT id FROM borrowings WHERE user_id = :user_id AND material_id = :material_id AND status = 'borrowed'");
    $stmt->execute(['user_id' => $user_id, 'material_id' => $material_id]);
    if ($stmt->fetch()) {
        // User already has this book
        $pdo->rollBack();
        redirect(BASE_URL . "/material_details.php?id=$material_id&status=borrow_error");
    }

    // 3. Decrease the available copies count
    $stmt = $pdo->prepare("UPDATE materials SET available_copies = available_copies - 1 WHERE id = :id");
    $stmt->execute(['id' => $material_id]);

    // 4. Create a new borrowing record
    $due_date = date('Y-m-d H:i:s', strtotime('+14 days')); // 14-day loan period
    $stmt = $pdo->prepare("INSERT INTO borrowings (user_id, material_id, due_date) VALUES (:user_id, :material_id, :due_date)");
    $stmt->execute([
        'user_id' => $user_id,
        'material_id' => $material_id,
        'due_date' => $due_date
    ]);

    // 5. Create a success notification for the user
    create_notification($pdo, $user_id, "You have successfully borrowed '" . $material['title'] . "'. It is due on " . date('F j, Y', strtotime($due_date)) . ".");

    // If all steps succeeded, commit the transaction
    $pdo->commit();

    // Redirect with success status
    redirect(BASE_URL . "/material_details.php?id=$material_id&status=borrow_success");

} catch (Exception $e) {
    // If any step fails, roll back the entire transaction
    $pdo->rollBack();
    // Log the error for the admin
    error_log("Borrowing failed: " . $e->getMessage());
    // Redirect with generic error
    redirect(BASE_URL . "/material_details.php?id=$material_id&status=borrow_error");
}