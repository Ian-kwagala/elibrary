<?php
// This file is for one-time use to generate a reliable password hash.
$passwordToHash = 'password123';
$hashedPassword = password_hash($passwordToHash, PASSWORD_DEFAULT);

echo "<h1>Password Hashing Tool</h1>";
echo "<p>Password to hash: <strong>" . htmlspecialchars($passwordToHash) . "</strong></p>";
echo "<p>Generated Hash (copy this entire line):</p>";
echo "<textarea rows='3' style='width: 100%; font-family: monospace;'>" . htmlspecialchars($hashedPassword) . "</textarea>";
?>