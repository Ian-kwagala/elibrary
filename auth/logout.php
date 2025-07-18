<?php
require_once '../includes/config.php';

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
redirect(BASE_URL . '/auth/login.php');
?>