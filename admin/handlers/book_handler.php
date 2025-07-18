<?php
require_once '../../includes/config.php';

// Security check: only admins can access this
if (!isLoggedIn() || !isAdmin()) {
    redirect(BASE_URL);
    exit();
}

// --- File Upload Helper Function ---
// This function handles uploading files and returns the generated filename or null.
function upload_file($file_input_name, $upload_dir) {
    // Check if the file was uploaded without errors
    if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES[$file_input_name];
        
        // Create a unique filename to prevent overwriting existing files
        $filename = time() . '_' . uniqid() . '_' . basename($file['name']);
        $target_path = $upload_dir . $filename;
        
        // Ensure the upload directory exists and is writable
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Move the uploaded file from the temporary directory to the final destination
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            return $filename; // Return the new filename on success
        }
    }
    return null; // Return null if no file was uploaded or an error occurred
}

// --- Main Logic for POST Requests (Add/Update) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $type_choice = $_POST['type_choice'] ?? '';

    // --- Determine the final material_type and stock levels based on the form choice ---
    if ($type_choice === 'book') {
        $material_type = 'book';
        $total_copies = isset($_POST['total_copies']) ? intval($_POST['total_copies']) : 1;
        // When adding a new book, available copies start equal to the total
        $available_copies = ($action === 'add') ? $total_copies : null;
    } else { // It's a study material
        $material_type = sanitize($_POST['material_type'] ?? 'pdf'); // Default to 'pdf' if not set
        // Study materials (PDFs, Videos) are treated as having 1 copy for download purposes
        $total_copies = 1;
        $available_copies = 1;
    }

    // --- Sanitize all common form data ---
    $title = sanitize($_POST['title'] ?? '');
    $author_lecturer = sanitize($_POST['author_lecturer'] ?? '');
    $genre_course = sanitize($_POST['genre_course'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    
    // Sanitize book-specific data, providing null defaults if not set
    $publisher = sanitize($_POST['publisher'] ?? null);
    $publication_date = !empty($_POST['publication_date']) ? sanitize($_POST['publication_date']) : null;
    $pages = !empty($_POST['pages']) ? intval($_POST['pages']) : null;
    
    // --- Handle file uploads ---
    $cover_image_filename = upload_file('cover_image', '../../uploads/book_covers/');
    $material_file_filename = upload_file('file_path', '../../uploads/study_materials/');

    // --- ADD A NEW MATERIAL ---
    if ($action === 'add') {
        $sql = "INSERT INTO materials (title, author_lecturer, description, material_type, genre_course, total_copies, available_copies, publication_date, publisher, pages, cover_image, file_path) 
                VALUES (:title, :author_lecturer, :description, :material_type, :genre_course, :total_copies, :available_copies, :publication_date, :publisher, :pages, :cover_image, :file_path)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'title' => $title, 'author_lecturer' => $author_lecturer, 'description' => $description,
            'material_type' => $material_type, 'genre_course' => $genre_course, 'total_copies' => $total_copies,
            'available_copies' => $available_copies, 'publication_date' => $publication_date,
            'publisher' => $publisher, 'pages' => $pages, 'cover_image' => $cover_image_filename,
            'file_path' => $material_file_filename
        ]);
    } 
    // --- UPDATE AN EXISTING MATERIAL ---
    elseif ($action === 'update') {
        $material_id = intval($_POST['material_id']);
        
        // Smartly update available copies if total copies of a book changes
        if ($material_type === 'book') {
            $stmt_copies = $pdo->prepare("SELECT total_copies, available_copies FROM materials WHERE id = :id");
            $stmt_copies->execute(['id' => $material_id]);
            $current_book = $stmt_copies->fetch();
            $copy_difference = $total_copies - $current_book['total_copies'];
            $new_available_copies = $current_book['available_copies'] + $copy_difference;
            // Ensure available copies don't go below zero
            $available_copies = max(0, $new_available_copies);
        }

        // Build the base update query
        $sql = "UPDATE materials SET title = :title, author_lecturer = :author_lecturer, description = :description, genre_course = :genre_course, publication_date = :publication_date, publisher = :publisher, pages = :pages";
        $params = [
            'title' => $title, 'author_lecturer' => $author_lecturer, 'description' => $description,
            'genre_course' => $genre_course, 'publication_date' => $publication_date,
            'publisher' => $publisher, 'pages' => $pages, 'id' => $material_id
        ];

        // Only update total/available copies for books
        if ($material_type === 'book') {
            $sql .= ", total_copies = :total_copies, available_copies = :available_copies";
            $params['total_copies'] = $total_copies;
            $params['available_copies'] = $available_copies;
        }

        // Only update file paths if a new file was uploaded
        if ($cover_image_filename) { $sql .= ", cover_image = :cover_image"; $params['cover_image'] = $cover_image_filename; }
        if ($material_file_filename) { $sql .= ", file_path = :file_path"; $params['file_path'] = $material_file_filename; }
        
        $sql .= " WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }
    
    // Redirect back to the management page after the action
    redirect(BASE_URL . '/admin/manage_books.php');
    exit();
}

// --- DELETE LOGIC (from GET request) ---
if (isset($_GET['delete_id'])) {
    $id_to_delete = intval($_GET['delete_id']);

    // Optional but recommended: Also delete the associated files from the server
    $stmt = $pdo->prepare("SELECT cover_image, file_path FROM materials WHERE id = :id");
    $stmt->execute(['id' => $id_to_delete]);
    $files = $stmt->fetch();
    if ($files) {
        if ($files['cover_image'] && file_exists('../../uploads/book_covers/' . $files['cover_image'])) {
            unlink('../../uploads/book_covers/' . $files['cover_image']);
        }
        if ($files['file_path'] && file_exists('../../uploads/study_materials/' . $files['file_path'])) {
            unlink('../../uploads/study_materials/' . $files['file_path']);
        }
    }
    
    // Now delete the record from the database
    $sql = "DELETE FROM materials WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id_to_delete]);

    redirect(BASE_URL . '/admin/manage_books.php');
    exit();
}