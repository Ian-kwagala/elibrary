<?php
//Tempapry debubgging line
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set header to return JSON content
header('Content-Type: application/json');

// FIX: Use the __DIR__ magic constant for a completely reliable path.
// __DIR__ gives the path to the current directory ('.../handlers/').
// /../ goes up one level to the project root.
// Then we go into /includes/config.php.
require_once __DIR__ . '/../includes/config.php';

// Sanitize and get input parameters
$searchTerm = isset($_GET['search']) ? '%' . sanitize($_GET['search']) . '%' : '%';
$genre = isset($_GET['genre']) && !empty($_GET['genre']) ? sanitize($_GET['genre']) : null;
$materialTypeFilter = isset($_GET['material_type']) && !empty($_GET['material_type']) ? sanitize($_GET['material_type']) : null;
$pageType = isset($_GET['page_type']) ? sanitize($_GET['page_type']) : 'book'; // 'book' or 'study_material'

// --- Base SQL Query ---
// We start with a base query and add conditions dynamically
$sql = "SELECT * FROM materials WHERE (title LIKE :searchTerm OR author_lecturer LIKE :searchTerm)";

$params = [':searchTerm' => $searchTerm];

// --- Dynamic WHERE clauses ---
if ($pageType === 'book') {
    $sql .= " AND material_type = 'book'";
    if ($genre) {
        $sql .= " AND genre_course = :genre";
        $params[':genre'] = $genre;
    }
} else { // 'study_material'
    $sql .= " AND material_type != 'book'";
    if ($materialTypeFilter) {
        $sql .= " AND material_type = :materialTypeFilter";
        $params[':materialTypeFilter'] = $materialTypeFilter;
    }
}

$sql .= " ORDER BY title ASC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $materials = $stmt->fetchAll();

    // Prepare a clean array for JSON output
    $response = [];
    foreach($materials as $material) {
        $response[] = [
            'id' => $material['id'],
            'title' => htmlspecialchars($material['title']),
            'author_lecturer' => htmlspecialchars($material['author_lecturer']),
            'cover_image' => BASE_URL . '/uploads/book_covers/' . ($material['cover_image'] ?: 'default_book.png'),
            'available_copies' => $material['available_copies'],
            'total_copies' => $material['total_copies'],
            'is_available' => $material['available_copies'] > 0,
        ];
    }

    echo json_encode(['success' => true, 'data' => $response]);

// } catch (PDOException $e) {
//     // Return a JSON error response
//     echo json_encode(['success' => false, 'message' => 'Database query failed.']);
//     // For debugging: error_log($e->getMessage());
// }

//Tempary debugging line
} catch (Exception $e) { // Changed to catch any type of error, not just PDO
    // Return a JSON error response WITH THE ACTUAL ERROR MESSAGE
    http_response_code(500); // Send a server error status
    echo json_encode([
        'success' => false, 
        'message' => 'A server error occurred.',
        'error_details' => $e->getMessage(), // This will expose the real error
        'error_trace' => $e->getTraceAsString() // This shows where it happened
    ]);
}