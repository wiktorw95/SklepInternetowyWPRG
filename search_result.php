<?php
session_start();

// Dołączenie skryptu do połączenia z bazą danych
include 'actions/connection.php';

$search_results = array();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $search_query = $_GET['search'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE product_name LIKE :search_query");
    $stmt->execute(['search_query' => '%' . $search_query . '%']);
    $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
<section id="featured" class="my-5 py-5">
    <section id="featured" class="my-5 py-5">
        <div class="container text-center mt-5">
            <h2 class="font-weight-bold">Products</h2>
            <hr class="mx-auto">
        </div>
        <div class="row mx-auto container-fluid">

            <?php foreach($search_results as $products) {?>
                <div class="product text-center col-lg-3 col-md-4 col-sm-12">
                    <img class="img-fluid mb-3" src="assets/imgs/<?php echo $products['product_image']; ?>"/>
                    <h5 class="p-name"><?php echo $products['product_name']; ?></h5>
                    <h4 class="p-price"><?php echo $products['product_price']; ?></h4>
                    <a href="<?php echo "single_product.php?product_id=". $products['product_id']; ?>"><button class="buy-btn">Buy Now</button></a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>