<?php
require_once 'includes/config.php';

$page_title = 'Home';
require_once 'includes/templates/header.php';

// Fetch a few featured books (e.g., the 4 most recently added)
try {
    $stmt = $pdo->query("SELECT * FROM materials WHERE material_type = 'book' ORDER BY created_at DESC LIMIT 4");
    $featured_books = $stmt->fetchAll();
} catch (PDOException $e) {
    // Handle error gracefully
    $featured_books = [];
    error_log($e->getMessage()); // Log error for admin
}
?>

<!-- Hero Section with Animation -->
<section class="hero-section">
    <div class="hero-content">
        <h1>Welcome to the UMU E-Library</h1>
        <p>Your digital gateway to a world of knowledge. Access books, past papers, and more.</p>
        <a href="<?php echo BASE_URL; ?>/books.php" class="btn btn-secondary">Explore Our Collection</a>
    </div>
</section>

<!-- NEW: Explore by Category Section -->
<section class="page-section hidden">
    <div class="container">
        <h2 class="section-title">Explore by Category</h2>
        <div class="category-grid">
            <div class="category-card">
                <i class="fas fa-book-open"></i>
                <h3>Academic Books</h3>
                <p>Access textbooks and reference materials across various disciplines.</p>
            </div>
            <div class="category-card">
                <i class="fas fa-file-alt"></i>
                <h3>Past Papers</h3>
                <p>Prepare for your exams with our extensive collection of past papers.</p>
            </div>
             <div class="category-card">
                <i class="fas fa-lightbulb"></i>
                <h3>Research & Journals</h3>
                <p>Dive into academic journals and research from various fields.</p>
            </div>
            <div class="category-card">
                <i class="fas fa-video"></i>
                <h3>Video Tutorials</h3>
                <p>Watch enhancement videos and tutorials to enrich your learning.</p>
            </div>
        </div>
    </div>
</section>

<!-- Recently Added Books Section (Formerly "Featured Books") -->
<section class="page-section hidden" style="background-color: var(--light-gray);">
    <div class="container">
        <h2 class="section-title">Recently Added Books</h2>
        <?php if (!empty($featured_books)): ?>
            <div class="materials-grid"> <!-- Changed class for consistency -->
                <?php foreach ($featured_books as $book): ?>
                    <div class="book-card">
                        <div class="card-img">
                             <a href="<?php echo BASE_URL; ?>/material_details.php?id=<?php echo $book['id']; ?>">
                                <img src="<?php echo BASE_URL . '/uploads/book_covers/' . ($book['cover_image'] ?: 'default_book.png'); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                             </a>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                            <p class="card-author">by <?php echo htmlspecialchars($book['author_lecturer']); ?></p>
                            <div class="card-status <?php echo ($book['available_copies'] > 0) ? 'status-available' : 'status-unavailable'; ?>">
                                <?php echo ($book['available_copies'] > 0) ? 'Available' : 'Out of Stock'; ?>
                            </div>
                            <div class="card-footer">
                                <span class="card-copies">Copies: <?php echo $book['available_copies']; ?>/<?php echo $book['total_copies']; ?></span>
                                <a href="<?php echo BASE_URL; ?>/material_details.php?id=<?php echo $book['id']; ?>" class="btn btn-primary btn-sm">Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="text-align:center;">No books available at the moment. Please check back later.</p>
        <?php endif; ?>
    </div>
</section>

<!-- NEW: How It Works Section -->
<section class="page-section hidden">
    <div class="container">
        <h2 class="section-title">How It Works</h2>
        <div class="how-it-works-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3>Browse</h3>
                <p>Search our extensive collection of books and study materials by category, course, or keyword.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h3>Sign Up</h3>
                <p>Create an account with your university email to access borrowing features and recommendations.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h3>Borrow</h3>
                <p>Borrow books digitally or download study materials to meet your academic needs instantly.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/templates/footer.php'; ?>