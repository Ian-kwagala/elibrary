<!-- Add/Edit User Modal -->
<div id="user-modal" class="modal">
    <div class="modal-content">
        <form id="user-form" method="POST">
            <div class="modal-header">
                <h3 class="modal-title" id="user-modal-title">Add New User</h3>
                <button type="button" class="close-button" onclick="closeModal('user-modal')">×</button>
            </div>
            <div class="modal-body">
                <!-- Hidden inputs to control logic -->
                <input type="hidden" name="action" value="add_user">
                <input type="hidden" name="user_id" value="0">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="student_number">Student Number</label>
                        <input type="text" name="student_number" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="course">Course</label>
                        <input type="text" name="course" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="role">User Role</label>
                        <select name="role" class="form-control" required>
                            <option value="student">Student</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Required for new user">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('user-modal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save User</button>
            </div>
        </form>
    </div>
</div>