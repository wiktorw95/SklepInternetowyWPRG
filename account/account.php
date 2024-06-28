<?php

$expire = 30 * 60; // 30 minut
session_set_cookie_params($expire);
session_start();

include '../actions/connection.php';

if(!isset($_SESSION['logged_in'])){
    header('Location: login.php');
    exit;
}

if(isset($_GET['logout'])){
    if(isset($_SESSION['logged_in'])){
        unset($_SESSION['logged_in']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_id']);
        $_SESSION['account_type'] = '';
        header('Location: login.php');
    }
}



if(isset($_POST['change_password'])){
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmpassword'];
    $user_email = $_SESSION['user_email'];

    if ($password !== $confirm_password) {
        header('location: account.php?error=password not match');
    } //if password is less than 6 characters
    else if (strlen($password) < 6) {
        header('location: account.php?error=password must be at least 6 characters');
        //no errors
    } else{
        $stmt = $conn1->prepare("UPDATE users SET user_password=? WHERE user_email=?");
        $stmt->bind_param("ss", $password, $user_email);

        if($stmt->execute()){
            header('location: account.php?message=password changed');
        }else{
            header('location: account.php?error=can not change password');
        }

    }
}

if(isset($_POST['users'])){
    header('location: admin/users.php');
} else if(isset($_POST['orders'])){
    header('location: admin/orders.php');
} else if(isset($_POST['products'])){
    header('location: admin/products.php');
}

//get orders
if(isset($_SESSION['logged_in'])){
    $user_id = $_SESSION['user_id'];
    $stmt = $conn1->prepare("SELECT * FROM orders WHERE user_id=?");

    $stmt->bind_param("i", $user_id);

    $stmt->execute();

    $orders = $stmt->get_result();


}
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
                <form class="search-bar"  method="GET" action="../search_result.php">
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
                        <a class="nav-link" href="account.php">Admin Konto</a>
                    <?php } else if (isset($_SESSION['user_id'])){ ?>
                        <a class="nav-link" href="account.php">Konto</a>
                    <?php } else { ?>
                        <a class="nav-link" href="login.php">Zaloguj się</a>
                    <?php } ?>
                </li>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!--Account-->
<section id="account" class="my-5 py-5">
    <div class="row container mx-auto">
        <div class="text-center mt-3 pt-5 col-lg-6 col-md-12 col sm-12">
            <p class="text-center" style="color: green"><?php if(isset($_GET['register_success'])){echo $_GET['register_success'];}?></p>
            <p class="text-center" style="color: green"><?php if(isset($_GET['login_success'])){echo $_GET['login_success'];}?></p>
            <h3 class="font-weight-bold">Account info</h3>
            <hr class="mx-auto">
            <div class="account-info">
                <h3>Name: <span><?php if(isset($_SESSION['user_name'])){ echo $_SESSION['user_name'];}?></span></h3>
                <h3>Email: <span><?php if(isset($_SESSION['user_email'])){ echo $_SESSION['user_email'];}?></span></h3>
                <h3><a href="account.php?logout=1" id="logout-btn">Logout</a></h3>
            </div>
        </div>

        <?php if ($_SESSION['account_type'] == 'admin') {?>
        <div class="text-center mt-3 pt-5 col-lg-6 col-md-12 col sm-12">
            <br><h3 class="font-weight-bold">Admin Panel</h3>
            <hr class="mx-auto">
            <a href="../admin/users.php"><button class="buy-btn">Users</button></a>
            <a href="../admin/orders.php"><button class="buy-btn">Orders</button></a>
            <a href="../admin/products.php"><button class="buy-btn">Products</button></a>
            <hr class="mx-auto">
        </div>
        <?php } ?>

        <div class=" container col-lg-6 col-md-12 col-sm-12">
            <form id="account-form" method="POST" action="account.php">
                <p class="text-center" style="color: red"><?php if(isset($_GET['error'])){echo $_GET['error'];}?></p>
                <p class="text-center" style="color: green"><?php if(isset($_GET['message'])){echo $_GET['message'];}?></p>
                <h3>Change Password</h3>
                <hr class="mx-auto">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" id="account-password" name="password" placeholder="Password" required/>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" class="form-control" id="account-confirm-password" name="confirmpassword" placeholder="Confirm Password" required/>
                </div>
                <div class="form-group">
                    <input type="submit" value="Change Password" name="change_password" class="btn" id="change-pass-btn">
                </div>
            </form>
        </div>
    </div>
</section>

<!--Orders-->
<?php if ($_SESSION['account_type'] != 'admin') {?>
<section id="orders" class="orders container my-5 py-3">
    <div class="container mt-2">
        <h2 class="font-weight-bold text-center">Your Orders</h2>
        <hr class="mx-auto">
    </div>
    <table class="container mt-5 pt-5">
        <tr>
            <th>Order ID</th>
            <th>Order Cost</th>
            <th>Order Status</th>
            <th>Order Date</th>
            <th>Order details</th>
        </tr>

        <?php foreach($orders as $o){?>
        <tr>
            <td>
                <span><?php echo $o['order_id'];?></span>
            </td>
            <td>
                <span><?php echo $o['order_cost'];?></span>
            </td>
            <td>
                <span><?php echo $o['order_status'];?></span>
            </td>
            <td>
                <span><?php echo $o['order_date'];?></span>
            </td>
            <td>
                <form method="GET" action="../order_details.php">
                    <input type="hidden" value="<?php echo $o['order_status'];?>" name="order_status"/>
                    <input type="hidden" value="<?php echo $o['order_id'];?>" name="order_id"/>
                    <input class="btn order-details-btn" name="order_details_btn" type="submit" value="details"/>
                </form>
            </td>
        </tr>

        <?php } ?>
    </table>

</section>
<?php } ?>




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