<?php
// config/db.php

$host = 'localhost';
$dbname = 'ratoghar_db';
$username = 'root'; // default XAMPP username
$password = ''; // default XAMPP password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Fetch objects by default
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch(PDOException $e) {
    // If the database doesn't exist, we might want to fail gracefully or attempt to create it.
    // For now, we just output the error. 
    // In production, log this instead of showing it to the user.
    die("Database Connection failed: " . $e->getMessage() . "<br>Please ensure XAMPP MySQL is running and the database 'ratoghar_db' is created using database.sql.");
}
?>
