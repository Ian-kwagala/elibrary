<?php 
require_once 'templates/admin_header.php'; 

// Fetch all non-admin users to display in the main table
try {
    $stmt = $pdo->query("SELECT * FROM users WHERE role != 'admin' ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $users = [];
    error_log("Failed to fetch users: " . $e->getMessage());
}
?>

<div class="page-header">
    <h1 class="page-title">Manage Users</h1>
    <button class="btn btn-primary" onclick="openUserModal()">
        <i class="fas fa-user-plus"></i> Add New User
    </button>
</div>

<!-- Users Table Card -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Student & Staff Accounts</h3>
        <div class="card-tools" style="width: 300px;">
            <input type="text" id="user-search" class="form-control" placeholder="Search by name, email, or student no...">
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Student Number</th>
                    <th>Course</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="users-table-body">
                <?php if (empty($users)): ?>
                    <tr class="no-hover"><td colspan="5" style="text-align: center; padding: 2rem;">No users found. Click "Add New User" to begin.</td></tr>
                <?php else: ?>
                    <?php foreach ($users as $user): 
                        // Prepare the user data as a JSON string for our JavaScript functions
                        $user_json = htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($user['full_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['student_number']); ?></td>
                        <td><?php echo htmlspecialchars($user['course'] ?? 'N/A'); ?></td>
                        <td class="table-actions">
                            <!-- Edit button opens the Add/Edit modal with user data -->
                            <button class="btn btn-sm btn-secondary" onclick='openUserModal("<?php echo urlencode($user_json); ?>")'>
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <!-- Delete button now opens the confirmation modal -->
                            <a href="#" class="btn btn-sm btn-danger" 
                               onclick='return openDeleteConfirmation("handlers/user_handler.php?delete_id=<?php echo $user['id']; ?>", "<?php echo htmlspecialchars($user['full_name'], ENT_QUOTES); ?>")'>
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

<!-- Include the modal HTML for adding/editing a user from the partial file -->
<?php include_once __DIR__ . '/manage_users_modal.php'; ?>

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