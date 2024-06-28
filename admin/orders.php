<?php

$expire = 30 * 60; // 30 minut
session_set_cookie_params($expire);
session_start();

include '../actions/connection.php';

$stmt = $conn1->prepare("SELECT COUNT(*) As total_records FROM orders");
$stmt->execute();
$stmt->bind_result($total_records);
$stmt->store_result();
$stmt->fetch();

$stmt1 = $conn1->prepare("SELECT * FROM orders");
$stmt1->execute();
$orders = $stmt1->get_result();




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/style.css"/>
</head>
<body>

<!--navbar-->

<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">FAKE<img src="../assets/imgs/2560px-Allegro.pl_sklep.svg.png" style="height: 50px; width: 150px;"><?php if ($_SESSION['account_type'] == 'admin') {?> ADMIN <?php } ?></a>
        <div id="menu-search">
            <div id="search-block">
                <form class="search-bar"  action="../search_result.php">
                    <input type="text" placeholder="Wpisz czego szukasz" name="search">
                </form>
            </div>
        </div>
        <div class="collapse navbar-collapse nav-buttons" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-3 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../shop.php">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../cart.php">Cart</a>
                </li>
                <li class="nav-item">
                    <?php if (isset($_SESSION['account_type']) && ($_SESSION['account_type'] == 'admin')) {?>
                        <a class="nav-link" href="../account/account.php">Admin Konto</a>
                    <?php } else if (isset($_SESSION['user_id'])){ ?>
                        <a class="nav-link" href="../account/account.php">Konto</a>
                    <?php } else { ?>
                        <a class="nav-link" href="../account/login.php">Zaloguj się</a>
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
                <?php if(isset($_GET['order_updated'])){?>
                    <p class="text-center" style="color: green;"><?php echo $_GET['order_updated'];?></p>
                <?php } ?>
                <?php if(isset($_GET['order_fail'])){?>
                    <p class="text-center" style="color: red;"><?php echo $_GET['order_fail'];?></p>
                <?php } ?>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">

                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                    <tr>
                        <th scope="col">Order ID</th>
                        <th scope="col">Order Status</th>
                        <th scope="col">User ID</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">User Phone</th>
                        <th scope="col">User Address</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach($orders as $o){?>
                    <tr>
                        <td><?php echo $o['order_id']?></td>
                        <td><?php echo $o['order_status']?></td>
                        <td><?php echo $o['user_id']?></td>
                        <td><?php echo $o['order_date']?></td>
                        <td><?php echo $o['user_phone']?></td>
                        <td><?php echo $o['user_address']?></td>

                        <td><a class="btn btn-primary" href="actions/edit_order.php?order_id=<?php echo $o['order_id'];?>">Edit</a></td>
                        <td><a class="btn btn-danger" href="actions/delete_order.php?order_id=<?php echo $o['order_id'];?>">Delete</a></td>
                    </tr>
                    <?php }?>
                    </tbody>
                </table>
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