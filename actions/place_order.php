<?php

$expire = 30 * 60; // 30 minut
session_set_cookie_params($expire);
session_start();

include 'connection.php';

if(isset($_POST["place_order"])) {

//if user is not logged in
    if(!isset($_SESSION["logged_in"])) {
        header("location: login.php?message=Please login/register first to place an order");
        exit;
    }

//get user info and store it in database
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $order_cost = $_SESSION["total"];
    $order_status = "Not Paid";
    $user_id = $_SESSION["user_id"];
    $order_date = date("Y-m-d H:i:s");

    $stmt = $conn1->prepare("INSERT INTO orders (order_cost, order_status, user_id, user_phone, user_city, user_address, order_date)
                       VALUES (?,?,?,?,?,?,?);");

    $stmt->bind_param('isiisss', $order_cost, $order_status, $user_id, $phone, $city, $address, $order_date);

    $stmt_status = $stmt->execute();

    if (!$stmt_status) {
        header("Location:checkout.php");
    } else {

        //issue new order and store order info in database
        $order_id = $stmt->insert_id;

        echo "Zamówienie pomyślnie dodano!";
        //get products from cart

        foreach ($_SESSION["cart"] as $key => $value) {
            $product = $_SESSION["cart"][$key]; // []
            $product_id = $product['product_id'];
            $product_name = $product['product_name'];
            $product_image = $product['product_image'];
            $product_price = $product['product_price'];
            $product_quantity = $product['product_quantity'];
            $product_amount = $product['product_amount'];

            //store each single item in order_items database
            $stmt1 = $conn1->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_image, product_price, product_quantity, user_id, order_date) 
                        VALUES (?,?,?,?,?,?,?,?);");
            $stmt1->bind_param('iissiiis', $order_id, $product_id, $product_name, $product_image, $product_price, $product_quantity, $user_id, $order_date);

            $stmt1->execute();
//
//            $stmt2 = $conn1->prepare("UPDATE products JOIN order_items ON products.product_id = order_items.product_id SET product_amount = product_amount-product_quantity WHERE product_id = ?");
//            $stmt2->execute();


        }

        unset($_SESSION["cart"]);
        $total = $_POST['total'];
        $total = 0;


        //inform user whether everything is fine or there is a problem
        header("Location: ../payment.php?message=order placed successfully");
    }
}


?>