<?php
require_once 'includes/config.php';

$page_title = 'Material Details';
require_once 'includes/templates/header.php';

// Check if an ID is provided in the URL (e.g., material_details.php?id=5)
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<div class="container"><p class="error-message">Invalid request. No material specified.</p></div>';
    require_once 'includes/templates/footer.php';
    exit();
}

// THIS IS THE MISSING LINE TO ADD. It takes the ID from the URL.
$material_id = intval($_GET['id']);

// Fetch material details from the database
try {
    $stmt = $pdo->prepare("SELECT * FROM materials WHERE id = :id");
    // Now this line works because $material_id exists.
    $stmt->execute(['id' => $material_id]); 
    $material = $stmt->fetch();
} catch (PDOException $e) {
    $material = null;
    // Log error: error_log($e->getMessage());
}

if (!$material) {
    echo '<div class="container"><p class="error-message">Sorry, the requested material could not be found.</p></div>';
    require_once 'includes/templates/footer.php';
    exit();
}
?>

<!-- ... -->
<div class="page-section">
    <div class="container">
        <div class="material-detail-card">
            <div class="detail-img">
                <img src="<?php echo BASE_URL . '/uploads/book_covers/' . ($material['cover_image'] ?: 'default_book.png'); ?>" alt="<?php echo htmlspecialchars($material['title']); ?>">
            </div>
            <div class="detail-info">
                <h1><?php echo htmlspecialchars($material['title']); ?></h1>
                <p class="author">By <?php echo htmlspecialchars($material['author_lecturer']); ?></p>
                <p class="description"><?php echo nl2br(htmlspecialchars($material['description'])); ?></p>
                
                <div class="detail-meta">
                    <div class="meta-item">
                        <strong>Status:</strong> 
                        <span class="<?php echo ($material['available_copies'] > 0) ? 'status-available' : 'status-unavailable'; ?>">
                            <?php echo ($material['available_copies'] > 0) ? 'Available' : 'Out of Stock'; ?>
                        </span>
                    </div>
                    <div class="meta-item">
                        <strong>Type:</strong> <?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $material['material_type']))); ?>
                    </div>
                    <div class="meta-item">
                        <strong>Subject/Course:</strong> <?php echo htmlspecialchars($material['genre_course']); ?>
                    </div>
                    <?php if ($material['publication_date']): ?>
                    <div class="meta-item">
                        <strong>Published:</strong> <?php echo date('F j, Y', strtotime($material['publication_date'])); ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($material['publisher']): ?>
                    <div class="meta-item">
                        <strong>Publisher:</strong> <?php echo htmlspecialchars($material['publisher']); ?>
                    </div>
                    <?php endif; ?>
                     <?php if ($material['pages']): ?>
                    <div class="meta-item">
                        <strong>Pages:</strong> <?php echo htmlspecialchars($material['pages']); ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="detail-actions">
                    <?php if (isLoggedIn()): ?>
                        <?php // --- Borrow Button Logic --- ?>
                        <?php if ($material['material_type'] === 'book' && $material['available_copies'] > 0): ?>
                            <a href="<?php echo BASE_URL; ?>/student/handlers/borrow_handler.php?id=<?php echo $material['id']; ?>" class="btn btn-primary">Borrow Book</a>
                        <?php elseif ($material['material_type'] === 'book'): ?>
                            <button class="btn btn-primary" disabled>Out of Stock</button>
                        <?php endif; ?>

                        <?php // --- Download Button Logic --- ?>
                        <?php if (!empty($material['file_path'])): ?>
                            <a href="<?php echo BASE_URL . '/uploads/study_materials/' . $material['file_path']; ?>" class="btn btn-secondary" download>Download</a>
                        <?php endif; ?>

                    <?php else: ?>
                        <p>Please <a href="<?php echo BASE_URL; ?>/auth/login.php">log in</a> to borrow or download materials.</p>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
// This script will show a notification based on the URL query string
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
    
    if (status === 'borrow_success') {
        showNotification('Book borrowed successfully!', 'success');
    } else if (status === 'borrow_error') {
        showNotification('Failed to borrow book. You may have already borrowed it or there are no copies left.', 'error');
    }
});
</script>


<?php require_once 'includes/templates/footer.php'; ?>