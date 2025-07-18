<?php
require_once '../../includes/config.php';

if (!isLoggedIn() || isAdmin()) {
    redirect(BASE_URL);
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect(BASE_URL . '/student/dashboard.php');
}

$borrowing_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$pdo->beginTransaction();

try {
    // Security: Verify the borrowing record belongs to the current user and is currently 'borrowed'
    $stmt = $pdo->prepare("SELECT material_id FROM borrowings WHERE id = :id AND user_id = :user_id AND status = 'borrowed' FOR UPDATE");
    $stmt->execute(['id' => $borrowing_id, 'user_id' => $user_id]);
    $borrowing = $stmt->fetch();

    if (!$borrowing) {
        // Record not found or doesn't belong to the user, or already returned.
        $pdo->rollBack();
        redirect(BASE_URL . '/student/dashboard.php');
    }

    // Update the borrowing record to 'returned'
    $stmt = $pdo->prepare("UPDATE borrowings SET status = 'returned', return_date = NOW() WHERE id = :id");
    $stmt->execute(['id' => $borrowing_id]);

    // Increment the available copies for the material
    $stmt = $pdo->prepare("UPDATE materials SET available_copies = available_copies + 1 WHERE id = :material_id");
    $stmt->execute(['material_id' => $borrowing['material_id']]);

    create_notification($pdo, $user_id, "You have successfully returned a book.");

    $pdo->commit();

    redirect(BASE_URL . '/student/dashboard.php?status=return_success');

} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Return failed: " . $e->getMessage());
    redirect(BASE_URL . '/student/dashboard.php?status=return_error');
}