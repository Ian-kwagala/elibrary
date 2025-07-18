<?php
// Start the session at the very beginning of your script
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- DATABASE CONFIGURATION ---
// These details seem correct for your setup.
$db_host = "localhost";
$db_user = "Library";
$db_pass = "";
$db_name = "online_library_db"; // Make sure your database in phpMyAdmin is named this.
$db_port = "3308"; // Custom port.

// --- SITE CONFIGURATION ---
// I've updated BASE_URL to match your folder structure from the error message.
define('SITE_NAME', 'UMU Online Library');
define('BASE_URL', 'http://localhost/online-library'); // Updated to match your folder 'online-library'
define('UNIVERSITY_EMAIL_DOMAIN', 'umu.ac.ug');

// --- DATABASE CONNECTION (PDO) ---
// This section is now fully corrected to use PDO.
try {
    // Create a new PDO instance and store it in the $pdo variable.
    // The connection string (DSN) now includes your custom port.
    $pdo = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name", $db_user, $db_pass);
    
    // --- The following lines will now work correctly ---

    // Set the PDO error mode to exception. This is crucial for error handling.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set the default fetch mode to associative array.
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch(PDOException $e){
    // If the connection fails, this will catch the error and stop the script.
    die("FATAL ERROR: Could not connect to the database. Please check your `config.php` settings. Details: " . $e->getMessage());
}

// Include the functions file, which is now available to any script that includes this config.
require_once __DIR__ . '/functions.php';
?>