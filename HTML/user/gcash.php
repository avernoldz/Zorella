<?php
session_start();
session_regenerate_id();

if (!$_SESSION['userid']) {
    header("Location:index.php?login-first");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../../inc/cdn.php" ?>
    <?php include "../../CSS/links.php" ?>
    <title>Gcash</title>
    <style>
        .col-6,
        .col,
        .row {
            padding: 0;
        }
    </style>
</head>

<body>
    <?php
    $userid = $_GET['userid'];
    $payment = $_GET['payment'];
    $infoid = $_GET['infoid'];

    ?>
    <div class="bg">
        <div class="row" style="height: 100vh; vertical-align:middle;">
            <div class="col-6 text-center flex justify-content-center" style="background: #005ce3;vertical-align:middle;">
                <img src="../../Assets/gcash.jpg" alt="gcash-pay" width="50%">
            </div>
            <div class="col-6 p-5 flex justify-content-center flex-column flex-wrap" style="background: white;">
                <h1 style="font-size: 20px;" class="mb-5">Pay with Gcash</h1>
                <p style="color: #979797;">Amount to Pay</p>
                <p style="font-size: 36px;" class=" mb-5">&#8369; <?php echo number_format($payment, 2) ?></p>
                <form action="../admin/inc/gcash.php" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                    <div class="custom-file mb-3">
                        <input type="file" class="custom-file-input" id="validatedCustomFile" name="img" required>
                        <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                        <div class="img invalid-feedback">Upload a valid file</div>
                    </div>
                    <input type="hidden" name="userid" value="<?php echo $userid ?>">
                    <input type="hidden" name="payment" value="<?php echo $payment ?>">
                    <input type="hidden" name="infoid" value="<?php echo $infoid ?>">
                    <button style="display:none" type="submit" name="gcash" id="submit">Next</button>
                    <button id="next" type="button" class="btn btn-primary w-100 mt-3">Upload</button>
                </form>
            </div>
        </div>
    </div>
    <script src="js/validation.js"></script>
</body>

</html>