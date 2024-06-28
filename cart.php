<?php


$expire = 30 * 60; // 30 minut
session_set_cookie_params($expire);
session_start();

if(isset($_POST['add_to_cart'])){
    //if user has already added a product to cart
    if(isset($_SESSION['cart'])){

        $products_array_ids = array_column($_SESSION['cart'], 'product_id'); // [2,3,4,10,15]
        //If product has already been added to cart or not
        if(!in_array($_POST['product_id'], $products_array_ids)){

            $product_id = $_POST['product_id'];

            $product_array = array(
                'product_id' => $_POST['product_id'],
                'product_name' => $_POST['product_name'],
                'product_price' => $_POST['product_price'],
                'product_image' => $_POST['product_image'],
                'product_quantity' => $_POST['product_quantity'],
                'product_amount' => $_POST['product_amount']
            );

            $_SESSION['cart'][$product_id] = $product_array;

            //product has already been added
        } else {
            echo '<script>alert("Product was already to cart")</script>';
        }
        //if this is the first product
    } else{

        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];
        $product_quantity = $_POST['product_quantity'];
        $product_amount = $_POST['product_amount'];

        $product_array = array(
                'product_id' => $product_id,
                'product_name' => $product_name,
                'product_price' => $product_price,
                'product_image' => $product_image,
                'product_quantity' => $product_quantity,
                'product_amount' => $product_amount
        );

        $_SESSION['cart'][$product_id] = $product_array;
        //[ 2=>[] , 3=>[], 5=>[] ]
    }

    //calculate total
    calculateTotalCart();

}

//remove product from cart
else if(isset($_POST['remove_product'])) {

    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);

    calculateTotalCart();

}else if(isset($_POST['edit_quantity'])) {
    // we get id and quantity from the form
    $product_id = $_POST['product_id'];
    $product_quantity = $_POST['product_quantity'];

    //get the product array from the session
    $product_array = $_SESSION['cart'][$product_id];

    //update product quantity
    $product_array['product_quantity'] = $product_quantity;

    //return array back to its place
    $_SESSION['cart'][$product_id] = $product_array;

    //calculate total
    calculateTotalCart();

}

else {
//    header('location: index.php');
    echo "tu nic nie ma";
}



function calculateTotalCart(){

    $total = 0;

    foreach($_SESSION['cart'] as $key => $value){
        $product = $_SESSION['cart'][$key];

        $price = $product['product_price'];
        $quantity = $product['product_quantity'];

        $total = $total + $price * $quantity;
    }

    $_SESSION['total'] = $total;
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
<!--Cart-->
<section class="cart container my-5 py-5">
    <div class="container mt-5">
        <h2 class="font-weight-bold">Your Cart</h2>
        <hr>
    </div>

    <table class="mt-5 pt-5">
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>


        <tr>
            <?php if(empty($_SESSION['cart'])) { ?>
                <div id=" text-center no-product-info">
                    <h1>Twój koszyk jest pusty</h1>
                    <p>Dodaj produkty do koszyka!</p>
                </div>
            <?php } else {?>
            <?php foreach($_SESSION['cart'] as $key => $value){?>
            <td>
                <div class="product-info">
                    <img src="assets/imgs/<?php echo $value['product_image']; ?>"/>
                    <div>
                        <p><?php echo $value['product_name']; ?></p>
                        <small><span>$</span><?php echo $value['product_price']; ?></small>
                        <br>
                        <form method="POST" action="cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $value['product_id']?>"/>
                            <input type="submit" name="remove_product" class="remove-btn" value="remove"/>
                        </form>
                    </div>
                </div>
            </td>
            <td>
                <form method="POST" action="cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $value['product_id'] ?>"/>
                    <input type="number" name="product_quantity" value="<?php echo $value['product_quantity'] ?>"/>
                    <input type="submit" class="edit-btn" value="edit" name="edit_quantity"/>
                </form>
            </td>

            <td>
                <span>$</span>
                <span class="product-price"><?php echo $value['product_quantity']*$value['product_price']; ?></span>
            </td>
            <?php } ?>
        </tr>

        <?php }?>
    </table>
    <div class="cart-total">
        <table>
            <tr>
                <td>Total</td>
                <?php if(empty($_SESSION['cart'])) { ?>
                    <td>$ NONE</td>
                <?php } else {?>
                <td>$ <?php echo $_SESSION['total']; ?></td>
                <?php } ?>
            </tr>
        </table>
    </div>
    <div class="checkout-container">
        <form method="POST" action="checkout.php">
            <input type="submit" class="btn checkout-btn" value="Checkout" name="checkout"/>
        </form>
    </div>
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