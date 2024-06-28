<?php


include "../../actions/connection.php";

if(isset($_GET['product_id'])){
    $product_id = $_GET['product_id'];
    $stmt = $conn1->prepare("DELETE FROM products WHERE product_id=?");
    $stmt->bind_param("i",$product_id);
    $stmt->execute();

    header("Location: products.php?deleted_successfully=Product has been deleted");

}
?>