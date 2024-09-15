<?php
session_start();
session_regenerate_id();

$successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : '';

if (!$_SESSION['userid']) {
    header("Location:../index.php?login-first");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../../inc/cdn.php" ?>
    <?php include "../../CSS/links.php" ?>
    <title>Home</title>
    <style>
        .main .col.card .row {
            align-items: center;
        }

        p {
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_GET['userid'];
    $bookid = $_GET['bookid'];
    $active = "dashboard";
    include "../../inc/Include.php";
    include "side-bar.php";
    include "header.php";

    ?>
    <div class="main">
        <div class="row">
            <div class="col head">
                <h1 class="w-700">Payment</h1>
            </div>
        </div>

        <form method="POST" action="../payment/stripe-checkout.php">
            <div class="row">
                <div class="col-5 case">
                    <h1 class="w-700">Booking Summary</h1>
                    <hr>
                    <div class="row">
                        <div class="col flex">
                            <p>Price</p>
                            <p class="w-700">PHP 10,000.00</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col flex">
                            <h1 class="w-700">Total</h1>
                            <h1 class="w-700 text-danger">PHP 10,000.00</h1>
                        </div>
                    </div>
                    <input type="hidden" name="userid" value="<?php echo $userid ?>">
                    <input type="hidden" name="bookid" value="<?php echo $bookid ?>">
                    <button type="submit" class="btn btn-success mt-3 w-100">Pay for My Booking</button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>