<?php 
require_once 'templates/admin_header.php'; 

// Fetch all materials to display in the main table
try {
    $stmt = $pdo->query("SELECT * FROM materials ORDER BY created_at DESC");
    $materials = $stmt->fetchAll();
} catch (PDOException $e) {
    $materials = [];
    error_log("Failed to fetch materials: " . $e->getMessage());
}
?>

<div class="page-header">
    <h1 class="page-title">Manage Materials</h1>
    <button class="btn btn-primary" onclick="openMaterialModal()">
        <i class="fas fa-plus"></i> Add New Material
    </button>
</div>

<!-- Materials Table Card -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Library Collection</h3>
        <div class="card-tools" style="width: 300px;">
            <input type="text" id="material-search" class="form-control" placeholder="Search by title, author, course...">
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author/Lecturer</th>
                    <th>Type</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="materials-table-body">
                <?php if (empty($materials)): ?>
                    <tr class="no-hover"><td colspan="5" style="text-align: center; padding: 2rem;">No materials found. Click "Add New Material" to begin.</td></tr>
                <?php else: ?>
                    <?php foreach ($materials as $material): 
                        // Prepare the material data as a JSON string for our JavaScript functions
                        $material_json = htmlspecialchars(json_encode($material), ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($material['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($material['author_lecturer']); ?></td>
                        <td><span class="badge badge-<?php echo str_replace('_', '-', $material['material_type']); ?>"><?php echo htmlspecialchars($material['material_type']); ?></span></td>
                        <td><?php echo $material['available_copies'] . ' / ' . $material['total_copies']; ?></td>
                        <td class="table-actions">
                            <!-- Edit button opens the main modal with material data -->
                            <button class="btn btn-sm btn-secondary" onclick='openMaterialModal("<?php echo urlencode($material_json); ?>")'>
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <!-- Delete button now opens the confirmation modal -->
                            <a href="#" class="btn btn-sm btn-danger" 
                               onclick='return openDeleteConfirmation("handlers/book_handler.php?delete_id=<?php echo $material['id']; ?>", "<?php echo htmlspecialchars($material['title'], ENT_QUOTES); ?>")'>
                               <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Include the modal HTML for adding/editing a material from the partial file -->
<?php include_once __DIR__ . '/manage_books_modal.php'; ?>

<!-- HTML for the Delete Confirmation Modal -->
<div id="confirmation-modal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fas fa-exclamation-triangle" style="color: var(--admin-danger-color); margin-right: 10px;"></i>Confirm Deletion</h3>
            <button type="button" class="close-button" onclick="closeModal('confirmation-modal')">×</button>
        </div>
        <div class="modal-body">
            <p id="confirmation-message" style="font-size: 1.1rem;">Are you sure you want to delete this item? This action is permanent and cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('confirmation-modal')">Cancel</button>
            <a href="#" id="confirm-delete-btn" class="btn btn-danger">Yes, Delete Permanently</a>
        </div>
    </div>
</div>

<?php 
// This is the closing part of the layout from admin_header
echo '</main></div>'; 
// This single script file contains all the necessary modal and search logic
echo '<script src="' . BASE_URL . '/admin/assets/js/admin_script.js"></script>';
?>
</body>
</html>