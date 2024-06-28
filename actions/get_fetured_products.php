<?php

include 'connection.php';

$stmt = $conn1 -> prepare("SELECT * FROM products LIMIT 4");

$stmt->execute();

$featured_products=$stmt->get_result();
?>