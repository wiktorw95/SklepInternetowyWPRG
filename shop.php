<?php
include 'actions/connection.php';

$expire = 30 * 60; // 30 minut
session_set_cookie_params($expire);
session_start();

//use the search section
if(isset($_POST['search'])){
    $category = $_POST['category'];

    $stmt = $conn1->prepare("SELECT * FROM products WHERE product_category = ?");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $products = $stmt->get_result();

    //return all products
} else {
    $stmt = $conn1->prepare("SELECT * FROM products");
    $stmt->execute();
    $products = $stmt->get_result();
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



<!--Shop-->
<!--You can add as many products as you want-->
<section id="featured" class="my-5 py-5">
    <section id="featured" class="my-5 py-5">
        <div class="container text-center mt-5 py-5">
            <h3>Products</h3>
            <hr class="mx-auto">
        </div>
        <div class="row mx-auto container-fluid">
            <?php foreach($products as $p){?>
                <div class="product text-center col-lg-3 col-md-4 col-sm-12">
                    <img class="img-fluid mb-3" src="assets/imgs/<?php echo $p['product_image']; ?>"/>
                    <h5 class="p-name"><?php echo $p['product_name']; ?></h5>
                    <h4 class="p-price"><?php echo $p['product_price']; ?></h4>
                    <a href="<?php echo "single_product.php?product_id=". $p['product_id']; ?>"><button class="buy-btn">Buy Now</button></a>
                </div>
            <?php } ?>
        </div>
    </section>


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

</body>
</html>