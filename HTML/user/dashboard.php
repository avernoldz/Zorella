<?php
session_start();
session_regenerate_id();

$successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : '';

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
    <title>Home</title>
    <style>
        .main .col.card .row {
            align-items: center;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_GET['userid'];
    $active = "dashboard";
    include "../../inc/Include.php";
    include "side-bar.php";
    include "header.php";
    ?>
    <div class="main">
        <div class="row">
            <div class="col head">
                <h1><i class="fa-solid fa-house fa-fw"></i>&nbsp;&nbsp;Dashboard</h1>
            </div>
        </div>
    </div>

    <div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="successBooking"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <!-- <div class="modal-header">
                    <h5 class="modal-title w-700 text-success" id="request"></h5>
                </div> -->
                <form action="inc/tour.php" style="width: 100%;" id="ticket" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                    <div class="modal-body mb-4 mt-4">
                        <div class="row w-100">
                            <div class="col-12">
                                <p class="text-justify">
                                    <span class="w-700">Thank you for your booking!</span>
                                    </br>
                                    </br>
                                    We appreciate your reservation. Our team will review and process your booking promptly. Once your booking is confirmed, you will receive a confirmation email with a link to complete your payment.
                                    </br>
                                    Alternatively, you can also check your "My Booking" page in your account for payment details.</br>
                                    </br>
                                    In the meantime, if you have any questions or need further assistance, please feel free to reach out to us.
                                    </br>
                                    </br>
                                    Thank you for choosing <span class="w-700" style="color: var(--maindark);">Zorella Travel Tours</span>. We look forward to serving you!


                                </p>
                            </div>
                        </div>

                        <input type="hidden" name="adminid" value="<?php echo $adminid ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        <?php if ($successMessage): ?>
            $(document).ready(function() {
                $('#success').modal('show');
            });
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
    </script>
</body>

</html>