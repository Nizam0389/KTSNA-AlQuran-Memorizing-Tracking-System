<?php
/**
 * Database Connection using PDO
 * This centralizes the connection and enables secure SQL handling.
 */

// Configuration
$host     = 'localhost';
$db_name  = 'ktsna_quran';
$username = 'root';
$password = '';

try {
    // Create connection
    $dbCon = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    
    // Set error mode to Exceptions (helps you find bugs faster)
    $dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to Associative Array
    $dbCon->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Hide sensitive info and show a clean error message
    error_log($e->getMessage()); // Logs the error for the developer
    die("Database connection failed. Please check your configuration.");
}