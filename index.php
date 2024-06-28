<?php
$expire = 30 * 60; // 30 minut
session_set_cookie_params($expire);
session_start();

include 'actions/connection.php';

if (isset($_SESSION['recently_viewed'])) {
    $recently_viewed = $_SESSION['recently_viewed'];
    $placeholders = str_repeat('?,', count($recently_viewed) - 1) . '?';
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id IN ($placeholders)");
    $stmt->execute($recently_viewed);
    $recent_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if($_SESSION['account_type']== NULL){
    $_SESSION['account_type'] = "";
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
                <form class="search-bar"  action="search_result.php">
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

<!--Home-->

<section id="home">
    <div class="container">
        <h1>Welcome to Random Shop!</h1>
    </div>
</section>

<!--New-->
<section id="new" class="w-100">
    <div class="row p-0 m-0">
        <!--One-->
        <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
            <img class="img-fluid" src="assets/imgs/IKONKA_AF_Fik_.png"/>
            <div class="details">
                <h2>Extreamely Awesome Products</h2>
            </div>
        </div>
        <!--Two-->
        <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
            <img class="img-fluid" src="assets/imgs/L570_AQi_2.jpg"/>
            <div class="details">
                <h2>Awesome Sales</h2>
            </div>
        </div>
        <!--Three-->
        <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
            <img class="img-fluid" src="assets/imgs/pobrany plik.png"/>
            <div class="details">
                <h2>50$ OFF for all products</h2>
            </div>
        </div>
    </div>
</section>



<!--Featured-->
<section id="featured" class="my-5 py-5">
    <div class="container text-center mt-5 py-5">
        <h3>Our Featured</h3>
        <hr class="mx-auto">
        <p>Here you can check out our featured products</p>
    </div>
    <div class="row mx-auto container-fluid">
        <?php include 'actions/get_fetured_products.php'; ?>

        <?php foreach($featured_products as $f) {?>
        <div class="product text-center col-lg-3 col-md-4 col-sm-12">
            <img class="img-fluid mb-3" src="assets/imgs/<?php echo $f['product_image']; ?>"/>
            <h5 class="p-name"><?php echo $f['product_name']; ?></h5>
            <h4 class="p-price"><?php echo $f['product_price']; ?>zł</h4>
            <a href="<?php echo "single_product.php?product_id=". $f['product_id']; ?>"><button class="buy-btn">Buy Now</button></a>
        </div>
        <?php } ?>
    </div>
</section>
<?php if (isset($recently_viewed)){ ?>
<section id="featured" class="last-seen-gallery">
    <div class="container text-center mt-5 py-5">
        <h3>Recently Viewed</h3>
        <hr class="mx-auto">
        <p>Here are products that you've checked lastly</p>
    </div>
    <div class="row mx-auto container-fluid">
    <?php
    foreach ($recent_products as $recent_product){?>
            <div class="product text-center col-lg-3 col-md-4 col-sm-12">
                <img class="img-fluid mb-3" src="assets/imgs/<?php echo $recent_product['product_image']; ?>"/>
                <h5 class="p-name"><?php echo $recent_product['product_name']; ?></h5>
                <h4 class="p-price"><?php echo $recent_product['product_price']; ?>zł</h4>
                <a href="<?php echo "single_product.php?product_id=". $recent_product['product_id']; ?>"><button class="buy-btn">Buy Now</button></a>
            </div>
        <?php } }?>
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