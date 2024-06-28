<?php

$expire = 30 * 60; // 30 minut
session_set_cookie_params($expire);
session_start();

include '../actions/connection.php';


if(isset($_POST['register'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    //if password dont match
    if ($password !== $confirmPassword) {
        header('location: register.php?error=uno');
    } //if password is less than 6 characters
    else if (strlen($password) < 6) {
        header('location: register.php?error=password must be at least 6 characters');


        //if there is no error
    } else {

        //check whether there is a user with this email or not
        $stmt1 = $conn1->prepare("SELECT count(*) FROM users WHERE user_email=?");
        $stmt1->bind_param("s", $email);
        $stmt1->execute();
        $stmt1->bind_result($num_rows);
        $stmt1->store_result();
        $stmt1->fetch();

        //if there is a user already registered with this email
        if ($num_rows != 0) {
            header('location: register.php?error=user with this email already exists');

            //if no user registered with this email before
        } else {

            //create a user
            $stmt = $conn1->prepare("INSERT INTO users (user_name, user_email, user_password) 
                    VALUES (?, ?, ?)");

            $stmt->bind_param("sss", $name, $email, $password);

            //if account was created successfully
            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_name'] = $name;
                $_SESSION['account_type'] = 'user';
                $_SESSION['logged_in'] = true;
                header('location: account.php?register_success=You registered successfully');

                //account couldnt be created
            } else {
                header('location: register.php?error=something went wrong');
            }
        }
    }

//if user has already registered, then take user to account page
} elseif (isset($_SESSION['logged_in'])) {
    header('location: account.php');
    exit;
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

<!--Register-->
<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="form-weight-bold">Register</h2>
        <hr class="mx-auto">
    </div>
    <div class="mx-auto container">
        <form id="register-form" method="POST" action="register.php">
            <p style="color: red"><?php if(isset($_GET['error'])){echo $_GET['error'];}?></p>
            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" id="register-name" name="name" placeholder="Name" required/>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" id="register-email" name="email" placeholder="email" required/>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" id="register-password" name="password" placeholder="password" required/>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" class="form-control" id="register-confirm-password" name="confirmPassword" placeholder="Confirm password" required/>
            </div>
            <div class="form-group">
                <input type="submit" class="btn" id="register-btn" name="register" value="Login"/>
            </div>
            <div class="form-group">
                <a id="login-url" class="btn" href="login.php">Do you have account? Login</a>
            </div>
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