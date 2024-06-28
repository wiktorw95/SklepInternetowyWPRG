<?php

include '../../actions/connection.php';

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['user_id'])){
    $user_id = $_POST['user_id'];
    $stmt = $conn1->prepare("DELETE FROM users WHERE user_id='$user_id'");
    $stmt->execute();

    header('Location: ../users.php' );

}