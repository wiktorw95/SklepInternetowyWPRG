<?php

$servername = "localhost";
$username = "root";  // replace with your username
$password = "";  // replace with your password
$database = "php_project";  // replace with your database name
$charset = "utf8";

// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=$database;charset=$charset", $username, $password);
    $conn1 = new mysqli($servername, $username, $password, $database);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>