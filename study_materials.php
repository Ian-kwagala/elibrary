<?php
// FIX: Include config.php at the very top.
require_once 'includes/config.php';

$page_title = 'Study Materials';
// Now this line works.
$extra_js = '<script src="' . BASE_URL . '/assets/js/filter.js"></script>';

require_once 'includes/templates/header.php';
?>

<div class="page-section">
    <div class="container">
        <h1 class="section-title">Find Study Materials</h1>

        <!-- Filter Bar -->
        <form id="study-material-filter-form" class="filter-bar">
            <div class="filter-group">
                <label for="search">Search by Title or Lecturer</label>
                <input type="text" id="search" name="search" placeholder="e.g., Financial Accounting">
            </div>
            <div class="filter-group">
                <label for="material_type">Filter by Type</label>
                <select id="material_type" name="material_type">
                    <option value="">All Types</option>
                    <option value="pdf">PDF Notes</option>
                    <option value="powerpoint">PowerPoint</option>
                    <option value="video">Lecture Video</option>
                    <option value="past_paper">Past Paper</option>
                </select>
            </div>
        </form>

        <!-- Grid for displaying materials -->
        <div id="materials-grid" class="materials-grid">
            <!-- Content will be loaded here by filter.js -->
        </div>
    </div>
</div>

<script>
    // Initialize the filter for this page
    document.addEventListener('DOMContentLoaded', () => {
        initializeFilter({
            filterFormId: 'study-material-filter-form',
            gridContainerId: 'materials-grid',
            pageType: 'study_material',
            baseUrl: '<?php echo BASE_URL; ?>' // Pass the base URL to JavaScript
        });
    });
</script>
<?php require_once 'includes/templates/footer.php'; ?>