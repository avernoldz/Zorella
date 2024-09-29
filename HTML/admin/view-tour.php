<?php
session_start();
session_regenerate_id();

if (!$_SESSION['adminid']) {
    header("Location:../index.php?Login-first");
    exit();
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
    <style>
        input:focus {
            outline: none;
        }

        table.passengers tbody td {
            padding: 0;
            border: 1px solid #8b8b8b;
        }

        table.down tbody tr td {
            border: 1px solid #8b8b8b;
        }
    </style>
</head>

<body>

    <?php

    $ticketid = $_GET['bookingid'];
    $bookingtype = $_GET['bookingtype'];
    $adminid = $_GET['adminid'];
    $bookid = $_GET['bookid'];
    $active = $bookingtype;
    $on = "on";
    include "../../inc/Include.php";
    include "header-admin.php";
    include "side-bar-admin.php";

    $quotation = "SELECT  
                    user.firstname AS user_firstname, 
                    user.lastname AS user_lastname, 
                    user.userid AS id,
                    personalinfo.*, 
                    tourbooking.*,
                    payment.*,
                    booking.*,
                    tourpackage.*,
                    paymentinfo.*
                    FROM user 
                  INNER JOIN tourbooking ON tourbooking.userid = user.userid 
                  RIGHT JOIN personalinfo ON personalinfo.ticketid = tourbooking.tour_bookid AND personalinfo.booking_type = '$bookingtype'
                  RIGHT JOIN  payment ON payment.booking_id = tourbooking.tour_bookid AND payment.booking_type = '$bookingtype'
                  LEFT JOIN  paymentinfo ON paymentinfo.payment_id = payment.paymentid 
                  RIGHT JOIN  booking ON booking.booking_id = tourbooking.tour_bookid AND booking.booking_type = '$bookingtype'
                  RIGHT JOIN  tourpackage ON tourpackage.tourid = tourbooking.tourid 
                  WHERE tourbooking.tour_bookid = '$ticketid' AND booking.booking_type = '$bookingtype'";
    $quotationResults = mysqli_query($conn, $quotation);
    $quotationRows = mysqli_fetch_all($quotationResults, MYSQLI_ASSOC);

    $firstRow = null;
    $total = null;

    if (!empty($quotationRows)) {
        // Get the first row
        $firstRow = $quotationRows[0];

        $passengerTypes = ['adult', 'child', 'infant', 'senior'];

        // Initialize total passengers
        $totalPassengers = 0;

        // Loop through each passenger type
        foreach ($passengerTypes as $type) {
            // Add the value to the total passengers, defaulting to 0 if not set
            $total += isset($firstRow[$type]) ? (int)$firstRow[$type] : 0;
        }

        $dateFormats = [
            'departure' => 'M d, Y',
            'arrival' => 'M d, Y',
            'tour_date' => 'F d, Y',
            'created_at' => 'M d, Y : h:i A'
        ];

        foreach ($dateFormats as $key => $format) {
            if (isset($firstRow[$key]) && $firstRow[$key] !== '0000-00-00') {
                $date = new DateTime($firstRow[$key]);
                $firstRow[$key] = $date->format($format);
            } else {
                // Set to empty string if date is '0000-00-00' or not set
                $firstRow[$key] = '';
            }
        }
    }

    $frow = $quotationRows[0] ?? null;

    ?>
    <div class="main">
        <div class="row bkq ">
            <div class="case row w-100 mb-4">
                <div class="col flex">
                    <h1 class="w-700"><i class="fa-solid fa-envelope fa-fw"></i>&nbsp;&nbsp;<?php echo "$frow[user_firstname] $frow[user_lastname]" ?></h1>
                </div>
                <?php if ($firstRow['status'] == 'Pending'): ?>
                    <div class="col flex justify-content-end accept">
                        <button type="button" data-target="#accept" data-toggle="modal" class="btn btn-success"><i class="fa-solid fa-check fa-fw "></i>Accept</button>
                        <button type="button" data-target="#reject" data-toggle="modal" class="btn btn-secondary reject"><i class="fa-solid fa-xmark fa-fw text-danger"></i> Reject</button>
                    </div>
                <?php elseif ($firstRow['status'] == 'Rejected'): ?>
                    <div class="col flex justify-content-end accept">
                        <button type="button" class="btn btn-secondary reject"><i class="fa-solid fa-xmark fa-fw text-danger"></i> Rejected</button>
                    </div>
                <?php else: ?>
                    <div class="col flex justify-content-end accept">
                        <?php echo "<a style='font-size: 12px;' class='btn btn-success' href='confirmation-bookings/$frow[confirmation_pdf]' target='_blank'>Download Confirmation Booking</a>" ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card col-7 mr-4 booking">
                <div class="card-header flex">
                    <h2>Booking Information</h2>
                    <h2><?php echo $bookingtype ?></h2>
                </div>
                <div class="card-body">
                    <div class="card-info">
                        <div class="info-item">
                            <label>Title</label>
                            <p><?php echo $firstRow['tour_title'] ?></p>
                        </div>
                        <div class="info-item">
                            <label>Branch</label>
                            <p><?php echo $firstRow['branch'] ?></p>
                        </div>
                        <div class="info-item">
                            <label>Date</label>
                            <p><?php echo $firstRow['tour_date'] ?></p>
                        </div>
                        <div class="info-item">
                            <label>Pax</label>
                            <p><?php echo $firstRow['pax'] ?></p>
                        </div>
                        <div class="info-item">
                            <label>Status</label>
                            <?php
                            if ($firstRow['status'] == 'Pending') {
                                echo "<p class='text-warning'>Pending</p>";
                            } else if ($firstRow['status'] == 'Payment') {
                                echo "<p class='text-info'>Confirmed Booking, waiting for payment</p>";
                            } else if ($firstRow['status'] == 'Rejected') {
                                echo "<p class='text-danger'>Rejected</p>";
                            } else {
                                echo "<p class='text-success'>Paid</p>";
                            }
                            ?>
                        </div>
                        <div class="info-item">
                            <label>Date Created</label>
                            <p><?php echo $firstRow['created_at'] ?></p>
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
                    <?php
                    $i = 1;
                    foreach ($quotationRows as $rows) {
                    ?>
                        <div class="col mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Applicant Information</h2>
                                </div>
                                <div class="card-body">
                                    <div class="card-info">
                                        <div class="info-item">
                                            <label>Name</label>
                                            <p><?php echo $rows['firstname'] . " " . $rows['lastname'] ?></p>
                                        </div>
                                        <div class="info-item">
                                            <label>Number</label>
                                            <p><?php echo $rows['number'] ?></p>
                                        </div>
                                        <div class="info-item">
                                            <label>Email</label>
                                            <p><?php echo $rows['email'] ?></p>
                                        </div>
                                        <div class="info-item">
                                            <label>Birthdate</label>
                                            <p><?php echo $rows['bdate'] ?></p>
                                        </div>
                                        <div class="info-item">
                                            <label>Age</label>
                                            <p><?php echo $rows['age'] ?></p>
                                        </div>
                                        <div class="info-item">
                                            <label>Sex</label>
                                            <p><?php echo $rows['sex'] ?></p>
                                        </div>
                                        <div class="info-item">
                                            <label>Civil Status</label>
                                            <p><?php echo $rows['civil'] ?></p>
                                        </div>
                                        <div class="info-item">
                                            <label>Address</label>
                                            <p><?php echo $rows['address'] ?></p>
                                        </div>
                                    </div>
                                    <div class="card-images">
                                        <div class="image-item">
                                            <label>Passport Picture</label>
                                            <img src="../user/uploads/<?php echo $rows['passport'] ?>" alt="Passport Picture">
                                        </div>
                                        <div class="image-item">
                                            <label>Valid ID Picture</label>
                                            <img src="../user/uploads/<?php echo $rows['validid'] ?>?>" alt="Valid ID Picture">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-100"></div>
                    <?php
                        $i++;
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="modal fade" id="accept" tabindex="-1" role="dialog" aria-labelledby="acceptLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <form action="inc/accept-booking.php" style="width: 100%;" id="accept" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                        <div class="modal-body mb-4 mt-3">
                            <div class="col-12 case mr-4">
                                <div class="row frame-quote w-100">
                                    <div class="col-12">
                                        <p class="text-center"><label>ZORELLA TRAVEL AND TOURS</label> <br>
                                            CALUMPANG LILIW LAGUNA <br>
                                            Laguna, Philippines 4004 <br>
                                            +639237374423 <br>
                                            zorellatravelandtours@gmail.com
                                        </p>
                                        <p class="text-center w-700 mt-3" style="font-size: 14px;"> CONFIRMATION BOOKING</p>
                                        <p class="text-center w-700" style="font-size: 14px;"> (BOOKING ORDER)</p>

                                        <div class="row w-100">
                                            <div class="col flex">
                                                <p> Date: <label id="today_date"><?php echo date('F d, Y') ?></label></p>
                                                <p> BOOKING ORDER NO: <label><?php echo $bookid ?></label></p>
                                            </div>
                                            <div class=" w-100"></div>
                                            <div class="col">
                                                <br>
                                                <p>Good Day!</p>
                                                <br>
                                                <p>We are please to confirm to your booking as shown below</p>
                                            </div>
                                        </div>
                                        <hr class="mb-3 mt-3">

                                        <div class="row w-100">
                                            <div class="col flex " style="align-items: normal;">
                                                <div class=" div">
                                                    <p>Date: <label id="travel_date"><?php echo $firstRow['tour_date'] ?></label></p>
                                                    <p>PAX: <label id="total_pax"><?php echo $firstRow['pax'] ?></label></p>
                                                </div>
                                                <div>
                                                    <p class="w-700 text-center pl-4 pr-3" style="background:#ffb0f1;">PER PERSON RATE</p>
                                                    <span>PHP </span><input type="number" id="price_pax" name="pax_price" required min="0" style="width: 5rem; border:none; border-bottom: 1px solid #e3e3e3;" placeholder="20,000.00"> <span>/ PAX</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row w-100 mt-2">
                                            <div class="col flex">
                                                <div class="col-9"></div>
                                                <div class="col">
                                                    <p class="w-700 text-center pl-2 pr-2" style="background:#fbd3f4;">TOTAL AMOUNT</p>
                                                    <p class="w-700 text-center pl-2 pr-2" style="background:#ffb0f1;">PHP <label id="total_price"></label></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row-100 mt-3">
                                            <table class="w-100 table table-bordered down">
                                                <tr>
                                                    <td>
                                                        <p>DOWNPAYMENT: <span class="w-700">PHP </span><input type="number" id="downpayment" name="downpayment" required min="0" style="width: 5rem; border:none; border-bottom: 1px solid #e3e3e3;" placeholder="20,000.00"></p>
                                                        <small class="text-danger">to be paid at your earliest convenince*</small>
                                                    </td>
                                                    <td>
                                                        <p>REMAINING BALANCE: <span class="w-700">PHP </span><span class="w-700" id="remaining_balance"></span></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>

                                                    </td>
                                                    <td id="installments">

                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="row-100 mt-3">
                                            <table class="w-100 table table-bordered passengers">
                                                <tr>
                                                    <td colspan="3" class="text-center w-700">PASSENGERS</td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td class="w-700">FIRSTNAME</td>
                                                    <td class="w-700">SURNAME</td>
                                                    <td class="w-700">BIRTHDAY</td>
                                                </tr>
                                                <?php foreach ($quotationRows as $rows): ?>
                                                    <tr class="text-center">
                                                        <td><?php echo $rows['firstname'] ?></td>
                                                        <td><?php echo $rows['lastname'] ?></td>
                                                        <td><?php echo $rows['bdate'] ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        </div>

                                        <div class="mb-4 mt-4">
                                            <textarea id="itinerary-textarea" name="terms" rows="20"></textarea>
                                        </div>
                                        <p style="text-align:right;">Prepared by: <label>Janine Rabajante</label><br>
                                            Marketing Executive
                                        </p>
                                        <br>
                                        <p style="text-align:right;">Conforme: <label>Ms. Bernadette Cagampan</label><br>
                                            Operations Manager
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="booking_id" value="<?php echo $ticketid ?>">
                            <input type="hidden" name="booking_type" value="<?php echo $bookingtype ?>">
                            <input type="hidden" name="paymentid" value="<?php echo $frow['paymentid'] ?>">
                            <input type="hidden" name="remaining_balance" id="remaining_bal" value="">
                            <input type="hidden" name="price_to_pay" id="price_to_pay" value="">
                            <input type="hidden" name="installment_number" id="installment_number" value="">
                            <input type="hidden" name="due_date" id="due_date" value="">
                            <input type="hidden" name="total_price" id="total_amount" value="">
                            <input type="hidden" name="bookid" value="<?php echo $bookid ?>">
                            <input type="hidden" name="adminid" value="<?php echo $adminid ?>">
                            <input type="hidden" name="userid" value="<?php echo $frow['id'] ?>">
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

        <div class="modal fade" id="reject" tabindex="-1" role="dialog" aria-labelledby="acceptLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form action="inc/accept-booking.php" style="width: 100%;" id="reject" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                        <div class="modal-body mb-3 mt-3">
                            <div class="row w-100">
                                <div class="col text-center">
                                    <i class="fa-solid fa-triangle-exclamation fa-fw text-danger mb-2" style="font-size: 32px;"></i>
                                    <h1 style="font-size: 22px;" class="mb-1">Reject Booking</h1>
                                    <p style="font-size: 14px;padding:8px 24px;">Are you sure you want to reject this booking? This action cannot be undone.</p>
                                </div>
                            </div>
                            <input type="hidden" name="bookid" value="<?php echo $bookid ?>">
                            <input type="hidden" name="adminid" value="<?php echo $adminid ?>">
                            <input type="hidden" name="booking_id" value="<?php echo $ticketid ?>">
                            <input type="hidden" name="booking_type" value="<?php echo $bookingtype ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close</button>
                            <button type="submit" name="reject-book" id="submit" class="btn btn-danger">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script>
        var installmentType = "<?php echo $frow['installment_type']; ?>";
    </script>
    <script src="js/app.js"></script>
    <script src="../user/js/validation.js"></script>
    <script>
        tinymce.init({
            selector: '#itinerary-textarea',
            plugins: 'lists link image code',
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright | link image | code',
            height: 500,
        });
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(window.location.search);
        const alert = urlParams.get('alert');

        if (alert == 4) {
            toastr["success"]("Request for booking rejected successfully")
        }
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": false,
            "preventDuplicates": false,
            "positionClass": "toast-bottom-center",
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    </script>
</body>

</html>