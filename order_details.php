<?php

$expire = 30 * 60; // 30 minut
session_set_cookie_params($expire);
session_start();

include 'actions/connection.php';

if(isset($_GET['order_details_btn']) && isset($_GET['order_id'])){
    $order_id = $_GET['order_id'];
    $order_status = $_GET['order_status'];

    $stmt = $conn1->prepare("SELECT * FROM order_items WHERE order_id = ?");

    $stmt->bind_param("i", $order_id);

    $stmt->execute();

    $order_details = $stmt->get_result();

    $order_total_price =  calculateTotalCartOrderPrice($order_details);
} else {
    header('location: account.php');
    exit;
}

function calculateTotalCartOrderPrice($order_details){

    $total = 0;

    foreach($order_details as $row) {
        $product_price = $row['product_price'];
        $product_quantity = $row['product_quantity'];

        $total = $total + ($product_price * $product_quantity);
    }

    return $total;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>

<!--navbar-->

<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">FAKE<img src="../assets/imgs/2560px-Allegro.pl_sklep.svg.png" style="height: 50px; width: 150px;"><?php if ($_SESSION['account_type'] == 'admin') {?> ADMIN <?php } ?></a>
        <div id="menu-search">
            <div id="search-block">
                <form class="search-bar"  method="GET" action="search_result.php">
                    <input type="text" placeholder="Wpisz czego szukasz" name="search">
                </form>
            </div>
        </div>
        <div class="collapse navbar-collapse nav-buttons" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-3 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="shop.php">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">Cart</a>
                </li>
                <li class="nav-item">
                    <?php if (isset($_SESSION['account_type']) && ($_SESSION['account_type'] == 'admin')) {?>
                        <a class="nav-link" href="account/account.php">Admin Konto</a>
                    <?php } else if (isset($_SESSION['user_id'])){ ?>
                        <a class="nav-link" href="account/account.php">Konto</a>
                    <?php } else { ?>
                        <a class="nav-link" href="account/login.php">Zaloguj się</a>
                    <?php } ?>
                </li>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!--Order details-->
<section class="orders container my-5 py3">
    <div class="container mt-5" style="min-height: 300px">
        <h2 class="font-weight-bold text-center">Order Details</h2>
        <hr class="mx-auto">
    </div>
    <table class="mt-5 pt-5 mx-auto">
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
        </tr>

        <?php foreach($order_details as $o){?>

            <tr>
                <td>
                    <div class="product-info">
                        <img src="assets/imgs/<?php echo $o['product_image']?>"/>
                        <div>
                            <p class="mt-3"><?php echo $o['product_name']?></p>
                        </div>
                    </div>
                </td>
                <td>
                    <span>$<?php echo $o['product_price']?></span>
                </td>
                <td>
                    <span><?php echo $o['product_quantity']?></span>
                </td>
                <td>
                    <span>$<?php echo $o['product_quantity']*$o['product_price']?></span>
                </td>
            </tr>
        <?php } ?>
    </table>
</section>









<!--Footer-->
<footer class="mt-5 py-5">
    <div class="row container mx-auto pt-5">
        <div class="footer-one col-lg-3 col-md-6 col-sm-12">
            <h5 class="pt-3 text-uppercase">Wiktor Wilk</h5>
            <h5 class="pt-3">s30897@pjwstk.edu.pl</h5>
            <h5 class="pt-3 text-uppercase">82-500, Kwidzyn</h5>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>