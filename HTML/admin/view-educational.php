<?php
session_start();
session_regenerate_id();

if (!$_SESSION['adminid']) {
    header("Location:../index.php?Login-first");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../../inc/cdn.php" ?>
    <?php include "../../CSS/links.php" ?>
    <link rel="stylesheet" href="css/main.css">
    <title>Bookings</title>
</head>

<body>

    <?php

    $bookingid = $_GET['bookingid'];
    $bookingtype = $_GET['bookingtype'];
    $bookid = $_GET['bookid'];
    $adminid = $_GET['adminid'];
    $active = "Pending Bookings";
    $on = "on";
    include "../../inc/Include.php";
    include "header-admin.php";
    include "side-bar-admin.php";

    $quotation = "SELECT 
    user.firstname AS user_firstname, 
    user.lastname AS user_lastname,
    user.userid, 
    leadpersoninfo.*, 
    educational.*, 
    booking.branch,
    payment.*
    FROM 
        user
    INNER JOIN 
        educational ON educational.userid = user.userid
    INNER JOIN 
        leadpersoninfo ON leadpersoninfo.booking_id = educational.educationalid
    INNER JOIN 
        booking ON booking.booking_id = educational.educationalid  -- Adjust this join condition if necessary
    INNER JOIN 
        payment ON payment.booking_id = booking.booking_id 
    WHERE 
        educational.educationalid = '$bookingid';
    ";

    $quotationResults = mysqli_query($conn, $quotation);
    $quotationRows = mysqli_fetch_all($quotationResults, MYSQLI_ASSOC);

    $frow = $quotationRows[0] ?? null;

    $dateFormats = [
        'created_at' => 'M d, Y : h:i A'
    ];
    // Access the 'created_at' value correctly
    $date = new DateTime($frow['created_at']);

    // Use the correct format string directly from the $dateFormats array
    $frow['created_at'] = $date->format($dateFormats['created_at']);

    ?>
    <div class="main">
        <div class="row bkq ">
            <div class="case row w-100 mb-4">
                <div class="col flex">
                    <h1 class="w-700"><i class="fa-solid fa-envelope fa-fw"></i>&nbsp;&nbsp;<?php echo "$frow[user_firstname] $frow[user_lastname]" ?></h1>
                </div>
                <div class="col flex justify-content-end accept">
                    <button type="button" data-target="#accept" data-toggle="modal" class="btn btn-success"><i class="fa-solid fa-check fa-fw "></i>Accept</button>
                    <button type="button" class="btn btn-secondary"><i class="fa-solid fa-xmark fa-fw text-danger"></i> Reject</button>
                </div>
            </div>
            <div class="card col-7 mr-4 booking">
                <div class="card-header flex">
                    <h2>Booking Information</h2>
                    <h2><?php echo $bookingtype ?></h2>
                </div>
                <div class="card-body">
                    <div class="card-info">
                        <div class="info-item">
                            <label>Branch</label>
                            <p><?php echo $frow['branch'] ?></p>
                        </div>
                        <div class="info-item">
                            <label>Start Date</label>
                            <p><?php echo $frow['sdate'] ?></p>
                        </div>
                        <div class="info-item">
                            <label>End Date</label>
                            <p><?php echo $frow['edate'] ?></p>
                        </div>
                        <?php if (!empty($frow['hotel'])): ?>
                            <div class="info-item">
                                <label>Hotel</label>
                                <p><?php echo htmlspecialchars($frow['hotel']); ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <label>Pax</label>
                            <p><?php echo $frow['pax'] ?></p>
                        </div>
                        <?php if (!empty($frow['attraction'])): ?>
                            <div class="info-item">
                                <label>Attraction</label>
                                <p><?php echo htmlspecialchars($frow['attraction']); ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <label>Date Created</label>
                            <p><?php echo $frow['created_at'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col" style="height: fit-content;">
                <div class="row mb-3 w-100">
                    <div class="card">
                        <div class="card-header">
                            <h2>Payment Information</h2>
                        </div>
                        <div class="card-body">
                            <div class="card-info">
                                <div class="info-item">
                                    <label>Payment Type</label>
                                    <p><?php echo $frow['payment_type'] ?></p>
                                </div>
                                <?php if (!empty($frow['installment_type'])): ?>
                                    <div class="info-item">
                                        <label>Installment Type</label>
                                        <p><?php echo htmlspecialchars($frow['installment_type']); ?></p>
                                    </div>
                                <?php endif; ?>
                                <div class="info-item">
                                    <label>Payment Method</label>
                                    <p><?php echo $frow['payment_method'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row w-100">
                    <div class="col mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h2>Lead Person Information</h2>
                            </div>
                            <div class="card-body">
                                <div class="card-info">
                                    <div class="info-item">
                                        <label>Name</label>
                                        <p><?php echo $frow['firstname'] . " " . $frow['lastname'] ?></p>
                                    </div>
                                    <div class="info-item">
                                        <label>Number</label>
                                        <p><?php echo $frow['number'] ?></p>
                                    </div>
                                    <div class="info-item">
                                        <label>Email</label>
                                        <p><?php echo $frow['email'] ?></p>
                                    </div>
                                    <div class="info-item">
                                        <label>Position</label>
                                        <p><?php echo $frow['position'] ?></p>
                                    </div>
                                    <div class="info-item">
                                        <label>Pickup</label>
                                        <p><?php echo $frow['pickup'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-100"></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="accept" tabindex="-1" role="dialog" aria-labelledby="acceptLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title w-700 ml-3" id="request">Accept Booking</h5>
                    </div>
                    <form action="inc/accept-booking.php" style="width: 100%;" id="accept" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                        <div class="modal-body mb-4 mt-3">
                            <div class="row w-100">
                                <div class="col-8">
                                    <label for="">Title</label><label for="" class="required">*</label>
                                    <input type="text" id="title"
                                        placeholder="Osaka Japan" name="title"
                                        class="form-control" required>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-6 mt-4">
                                    <label for="">Price</label><label for="" class="required">*</label>
                                    <input type="number" id="price"
                                        placeholder="&#x20B1; 9,999.00" name="price" step="0.01" min="0"
                                        class="form-control" required>
                                </div>

                                <div class="w-100"></div>
                                <div class="col-12 mt-4">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" maxlength="250" rows="3" required></textarea>
                                    <div class="char-count float-right">
                                        <span id="charCount">250</span> characters remaining
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="bookid" value="<?php echo $bookid ?>">
                            <input type="hidden" name="adminid" value="<?php echo $adminid ?>">
                            <input type="hidden" name="userid" value="<?php echo $frow['userid'] ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close</button>
                            <button style="display:none" type="submit" name="accept-book" id="submit">Next</button>
                            <button type="button" id="next" class="btn btn-success">Accept</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script src="../user//js/validation.js"></script>
</body>

</html>