<?php
$expire = 30 * 60; // 30 minut
session_set_cookie_params($expire);
session_start();

include_once '../actions/connection.php';

$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(isset($_GET['logout'])){
    if(isset($_SESSION['logged_in'])){
        unset($_SESSION['logged_in']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_id']);
        unset($_SESSION['account_type']);
        header('Location: ../login.php');
    }
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
                                        <?php if (isset($_SESSION['account_type']) && ($_SESSION['account_type'] == 'admin')) {

                                            ?>
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
<body>
<section id="users" class="users container my-5 py-5 pt-5">
    <form method="get" action="users.php">
    </form>
    <br><br><br>
    <div class="container mt-2">
        <h1 class="font-weight-bold text-center">Users</h1>
        <hr class="mx-auto">
    </div>
        <table class="container mt-5 pt-5">
            <tr>
                <th>ID</th>
                <th>username</th>
                <th>Email</th>
                <th>Password</th>
                <th>Account_Type</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php foreach ($users as $user){ ?>
                <tr>
                    <td><?= $user['user_id'] ?></td>
                    <td><?= $user['user_name'] ?></td>
                    <td><?= $user['user_email'] ?></td>
                    <td><?= $user['user_password'] ?></td>
                    <td><?= $user['account_type'] ?></td>
                    <td><a class="btn btn-primary" href="actions/edit_user.php?user_id=<?php echo $user['user_id']?>">Edit</a></td>
                    <td>
                        <form action="actions/delete_user.php" method="post">
                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                            <input style="background-color: red; color: #fff;" class="btn order-details-btn" type="submit" value="Delete">
                        </form>
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
