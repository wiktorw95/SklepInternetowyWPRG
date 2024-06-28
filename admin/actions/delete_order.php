<?php

session_start();

include "../../actions/connection.php";

if(isset($_GET['order_id'])){
    $order_id = $_GET['order_id'];
    $stmt = $conn1->prepare("DELETE FROM orders WHERE order_id=?");
    $stmt->bind_param("i",$order_id);
    $stmt->execute();

    header("Location: orders.php?deleted_successfully=Product has been deleted");

}
?>