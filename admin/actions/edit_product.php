<?php

include '../../actions/connection.php';
$expire = 30 * 60; // 30 minut
session_set_cookie_params($expire);
session_start();

if(isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $stmt = $conn -> prepare("SELECT * FROM products WHERE product_id = :product_id");

    $stmt->execute(['product_id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
} else if(isset($_POST['edit_btn'])){
    $product_id = $_POST['product_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $stmt = $conn1->prepare("UPDATE products SET product_name=?, product_description=?, product_price=?, product_category=? WHERE product_id=?");
    $stmt->bind_param("ssssi", $title, $description, $price, $category, $product_id);
    if($stmt->execute()) {

        header('location: ../products.php?edit_success_message= Edit product successfully!');
    } else {
        header("Location: ../products.php?edit_fail_message= Edit product fail!");
    }

} else{
    header("Location: ../products.php?edit_fail_message= Edit product fail!");
    exit;
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/css/style.css"/>
</head>
<body>

<!--navbar-->

<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">FAKE<img src="../../assets/imgs/2560px-Allegro.pl_sklep.svg.png" style="height: 50px; width: 150px;"><?php if ($_SESSION['account_type'] == 'admin') {?> ADMIN <?php } ?></a>
        <div id="menu-search">
            <div id="search-block">
                <form class="search-bar"  action="../../search_result.php">
                    <input type="text" placeholder="Wpisz czego szukasz" name="search">
                </form>
            </div>
        </div>
        <div class="collapse navbar-collapse nav-buttons" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-3 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="../../index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../shop.php">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../cart.php">Cart</a>
                </li>
                <li class="nav-item">
                    <?php if (isset($_SESSION['account_type']) && ($_SESSION['account_type'] == 'admin')) {?>
                        <a class="nav-link" href="../../account/account.php">Admin Konto</a>
                    <?php } else if (isset($_SESSION['user_id'])){ ?>
                        <a class="nav-link" href="../../account/account.php">Konto</a>
                    <?php } else { ?>
                        <a class="nav-link" href="../../account/login.php">Zaloguj się</a>
                    <?php } ?>
                </li>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="container-fluid">
    <div class="row" style="min-height: 1000px">
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div style="margin-top: 200px;" class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1>Orders</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">

                    </div>
                </div>
            </div>

            <h2>Edit Product</h2>
            <div class="table-responsive">
                <div class="mx-auto container">
                    <form id="edit-form" method="POST" action="edit_product.php">
                        <p style="color: red;"><?php if(isset($_GET['error'])){echo $_GET['error'];}?></p>
                        <div class="form-group mt-2">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id'];?>"
                            <label>Title</label>
                            <input type="text" class="form-control" id="product-name" value="<?php echo $product['product_name']?>" name="title" placeholder="Title"/>
                        </div>
                        <div class="form-group mt-2">
                            <label>Description</label>
                            <input type="text" class="form-control" id="product-description" value="<?php echo $product['product_description']?>" name="description" placeholder="Description"/>
                        </div>
                        <div class="form-group mt-2">
                            <label>Price</label>
                            <input type="text" class="form-control" id="product-price" value="<?php echo $product['product_price']?>" name="price" placeholder="Price"/>
                        </div>
                        <div class="form-group mt-2">
                            <label>Category</label>
                            <select class="form-select" name="category" required >
                                <option value="cats">Cats</option>
                                <option value="shoes">Shoes</option>
                                <option value="cities">Cities</option>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <input type="submit" class="btn btn-primary" name="edit_btn" value="Edit"/>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>
</div>


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