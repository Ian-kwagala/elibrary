<!-- Add/Edit Material Modal -->
<div id="material-modal" class="modal">
    <div class="modal-content">
        <form id="material-form" method="POST" enctype="multipart/form-data">
            <div class="modal-header">
                <h3 class="modal-title" id="material-modal-title">Add New Material</h3>
                <button type="button" class="close-button" onclick="closeModal('material-modal')">×</button>
            </div>
            <div class="modal-body">
                <!-- Hidden inputs to control logic -->
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="material_id" value="0">

                <div class="form-group">
                    <label for="type-choice">Material Category</label>
                    <select id="type-choice" name="type_choice" class="form-control">
                        <option value="book">Book (with physical copies)</option>
                        <option value="study_material">Study Material (Digital Download)</option>
                    </select>
                </div>
                <hr style="margin: 20px 0;">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="author_lecturer">Author / Lecturer</label>
                        <input type="text" name="author_lecturer" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="genre_course">Course / Genre</label>
                    <input type="text" name="genre_course" class="form-control">
                </div>

                <!-- Fields that change based on selection -->
                <div id="material-type-field" style="display: none;">
                    <div class="form-group">
                        <label for="material_type_select">Specific Material Type</label>
                        <select id="material_type_select" name="material_type" class="form-control">
                            <option value="pdf">PDF</option>
                            <option value="powerpoint">PowerPoint</option>
                            <option value="video">Video</option>
                            <option value="past_paper">Past Paper</option>
                        </select>
                    </div>
                </div>

                <div id="book-fields">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="publisher">Publisher</label>
                            <input type="text" name="publisher" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="publication_date">Publication Date</label>
                            <input type="date" name="publication_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="pages">Number of Pages</label>
                            <input type="number" name="pages" min="0" class="form-control">
                        </div>
                        <div class="form-group" id="copies-field">
                            <label for="total_copies">Total Copies</label>
                            <input type="number" name="total_copies" min="0" value="1" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="cover_image">Cover Image</label>
                        <input type="file" name="cover_image" accept="image/*" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="file_path">Material File (PDF, PPTX, MP4, etc.)</label>
                        <input type="file" name="file_path" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('material-modal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Material</button>
            </div>
        </form>
    </div>
</div>