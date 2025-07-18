<?php
// FIX: Include config.php at the very top.
require_once 'includes/config.php';

$page_title = 'Books';
// Now this line works because BASE_URL is defined.
$extra_js = '<script src="' . BASE_URL . '/assets/js/filter.js"></script>'; 

// The header file still needs to be included to show the HTML.
require_once 'includes/templates/header.php';

// Fetch distinct genres/courses for the filter dropdown
try {
    $stmt = $pdo->query("SELECT DISTINCT genre_course FROM materials WHERE material_type = 'book' AND genre_course IS NOT NULL ORDER BY genre_course ASC");
    $genres = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch(PDOException $e) {
    $genres = [];
}
?>

<div class="page-section">
    <div class="container">
        <h1 class="section-title">Explore Our Books</h1>

        <!-- Filter Bar -->
        <form id="book-filter-form" class="filter-bar">
            <div class="filter-group">
                <label for="search">Search by Title or Author</label>
                <input type="text" id="search" name="search" placeholder="e.g., Introduction to Algorithms">
            </div>
            <div class="filter-group">
                <label for="genre">Filter by Genre/Course</label>
                <select id="genre" name="genre">
                    <option value="">All Genres</option>
                    <?php foreach ($genres as $genre): ?>
                        <option value="<?php echo htmlspecialchars($genre); ?>"><?php echo htmlspecialchars($genre); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <!-- Grid for displaying books -->
        <div id="materials-grid" class="materials-grid">
            <!-- Content will be loaded here by filter.js -->
        </div>
    </div>
</div>

<script>
    // Initialize the filter for this page
    document.addEventListener('DOMContentLoaded', () => {
        initializeFilter({
            filterFormId: 'book-filter-form',
            gridContainerId: 'materials-grid',
            pageType: 'book',
            baseUrl: '<?php echo BASE_URL; ?>' // Pass the base URL to JavaScript
        });
    });
</script>
<?php require_once 'includes/templates/footer.php'; ?>