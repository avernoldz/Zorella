<?php
session_start();
session_regenerate_id();

$successMessage = isset($_SESSION['paid']) ? $_SESSION['paid'] : '';

if (!$_SESSION['userid']) {
    header("Location:../index.php?login-first");
}

function random_strings($length_of_string)
{

    // String of all alphanumeric character
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    // Shuffle the $str_result and returns substring
    // of specified length
    return substr(
        str_shuffle($str_result),
        0,
        $length_of_string
    );
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

        .wd-100 {
            width: 100%;
        }

        .row.populate-quote {
            background-color: var(--sub);
            padding: 8px;
            border-radius: 4px;
            margin: 6px 24px;
            cursor: pointer;
        }

        .modal table.table-borderless tbody td {
            border: none;
            border-bottom: 1px solid #ddd;
        }

        label {
            margin: 0;
        }

        .row.populate-quote p {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 100%;
        }

        p {
            margin: 0;
        }

        .modal p {
            font-size: 14px;
        }

        /* .row.populate-quote.w-100.Payment {
            background: var(--main-500);
            color: white;
        } */
    </style>
</head>

<body>

    <?php
    $userid = $_GET['userid'];
    $active = "";
    include "../../inc/Include.php";
    include "inc/select.php";
    include "side-bar.php";
    include "header.php";

    // $query = "SELECT
    // u.userid,
    // u.firstname,
    // u.lastname,
    // b.bookid,
    // b.booking_id,
    // b.booking_type,
    // b.status AS stat,
    // b.branch AS branchs,
    // b.created_at AS date_created,
    // tb.*,
    // eb.*,
    // eb.pax AS eb_pax,
    // eb.created_at AS date_eb,
    // tc.*,
    // tp.*,
    // pt.confirmation_pdf,
    // pt.payment_id,
    // p.paymentid,
    // pt.paymentinfoid
    // FROM
    //     booking b
    // INNER JOIN
    //     user u ON b.userid = u.userid
    // LEFT JOIN
    //     educational eb ON b.booking_type = 'Educational' AND b.booking_id = eb.educationalid
    // LEFT JOIN
    //     tourbooking tc ON b.booking_type = 'Tour Package' AND b.booking_id = tc.tour_bookid
    // LEFT JOIN
    //     tourpackage tp ON tc.tourid = tp.tourid
    // LEFT JOIN
    //     payment p ON p.booking_id = b.booking_id
    // LEFT JOIN
    //     paymentinfo pt ON pt.payment_id = p.paymentid
    // LEFT JOIN
    //     ticket tb ON b.booking_id = tb.ticketid
    //     AND (b.booking_type = 'Ticketed' OR b.booking_type = 'Customize')
    // WHERE
    //     u.userid = '$userid'
    // ORDER BY
    //     b.bookid DESC";

    // $res = mysqli_query($conn, $query);

    ?>
    <div class="main">
        <form action="" style="width: 100%;" class="needs-validation" method="GET" novalidate>

            <input type="hidden" value="<?php echo "$userid"; ?>"
                name="userid">

            <div class="row wrap" id="ticketed">
                <div class="col body">
                    <div class="row">
                        <div class="col-12 body" style="background-color: var(--main-300);color: white;">
                            <h4>My Bookings</h4>
                        </div>
                    </div>

                    <div class="row body">
                        <div class="row w-100">

                            <?php
                            if (!empty($allResults)) {

                                usort($allResults, function ($a, $b) {
                                    return strtotime($b['date_created']) - strtotime($a['date_created']);
                                });

                                foreach ($allResults as $row) {
                                    $dateFormats = [
                                        'departure' => 'M d, Y',
                                        'arrival' => 'M d, Y',
                                        'sdate' => 'M d, Y',
                                        'edate' => 'M d, Y',
                                        'dateCreated' => 'M d, Y : h:i A',
                                        'date_created' => 'M d, y',
                                        'tour_date' => 'M d, Y',
                                        'date_eb' => 'M d, Y : h:i A'
                                    ];

                                    foreach ($dateFormats as $key => $format) {
                                        if (isset($row[$key]) && $row[$key] !== '0000-00-00') {
                                            $date = new DateTime($row[$key]);
                                            $row[$key] = $date->format($format);
                                        } else {
                                            // Set to empty string if date is '0000-00-00' or not set
                                            $row[$key] = '';
                                        }
                                    }
                                    $rand = random_strings(5);

                            ?>

                                    <?php if ($row['booking_type'] == 'Customize' || $row['booking_type'] == 'Ticketed'): ?>
                                        <?php $total = $row['adult'] + $row['child'] + $row['infant'] + $row['senior']; ?>
                                        <div class="row populate-quote w-100" data-toggle="modal" data-target="<?php echo "#$rand" ?>">
                                            <div class="col-3">
                                                <label for="" class="w-700">
                                                    <?php echo "$row[ticket] Ticket" ?><br>
                                                </label>
                                            </div>
                                            <div class="col-8">
                                                <p><?php echo "From " . htmlspecialchars($row['origin']) . " to " . htmlspecialchars($row['destination']) . ", " . htmlspecialchars($total) . " pax, " . htmlspecialchars($row['departure']); ?></p>
                                            </div>
                                            <div class="col-1">
                                                <p class="w-700"><?php echo htmlspecialchars($row['date_created']) ?></p>
                                            </div>
                                        </div>

                                        <div class="modal" id="<?php echo $rand ?>" tabindex="-1" aria-labelledby="<?php echo $rand ?>Label" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title w-700 ml-3" id="<?php echo $rand ?>Label">Booking Information</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <td>Branch</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['branchs']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Flight</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['flighttype']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Ticket</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['tickettype']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Origin</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['origin']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Destination</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['destination']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Class</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['classtype']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Departure</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['departure']) ?></td>
                                                            </tr>
                                                            <?php if (!empty($row['arrival'])): ?>
                                                                <tr>
                                                                    <td>Arrival</td>
                                                                    <td class="w-700"><?php echo htmlspecialchars($row['arrival']) ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            <tr>
                                                                <td>Direct</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['directflight']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Passengers</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($total) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Adult</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['adult']) ?></td>
                                                            </tr>
                                                            <?php if (!empty($row['child'])): ?>
                                                                <tr>
                                                                    <td>Child</td>
                                                                    <td class="w-700"><?php echo htmlspecialchars($row['child']) ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            <?php if (!empty($row['infant'])): ?>
                                                                <tr>
                                                                    <td>Infant</td>
                                                                    <td class="w-700"><?php echo htmlspecialchars($row['infant']) ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            <?php if (!empty($row['senior'])): ?>
                                                                <tr>
                                                                    <td>Senior</td>
                                                                    <td class="w-700"><?php echo htmlspecialchars($row['senior']) ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            <tr>
                                                                <td>Airline</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['airline']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Date Created</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['dateCreated']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Status</td>
                                                                <td class="w-700">
                                                                    <?php
                                                                    if ($row['stat'] == 'Pending') {
                                                                        echo "<span class='text-warning'>Pending</span>";
                                                                    } else if ($row['stat'] == 'Payment') {
                                                                        echo "<span class='text-info'>Confirmed Booking, waiting for payment</span>";
                                                                    } else {
                                                                        echo "<span class='text-success'>Paid</span>";
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <?php if ($row['stat'] == 'Payment'): ?>
                                                                <tr>
                                                                    <td style="vertical-align:middle;">Confirmation</td>
                                                                    <td class="w-700"><?php echo "<a style='font-size: 12px;' class='btn btn-sm btn-success' href='../admin/confirmation-bookings/$row[confirmation_pdf]' target='_blank'>Download Confirmation Booking</a>" ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            <?php if ($row['stat'] == 'Payment'): ?>
                                                                <tr>
                                                                    <td>Payment</td>
                                                                    <td class="w-700"><?php echo "<a style='font-size: 12px;' class='btn btn-sm btn-danger' href='../payment/stripe-checkout.php?payment_id=$row[paymentinfoid]&userid=$row[userid]&bookid=$row[bookid]&booking_type=$row[booking_type]&booking_id=$row[booking_id]'>Pay Here</a>" ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php elseif ($row['booking_type'] == 'Educational'): ?>
                                        <div class="row populate-quote w-100" data-toggle="modal" data-target="<?php echo "#$rand" ?>">
                                            <div class="col-3">
                                                <label for="" class="w-700">
                                                    <?php echo "Educational/Team Building" ?><br>
                                                </label>
                                            </div>
                                            <div class="col-8">
                                                <p><?php echo htmlspecialchars($row['hotel']); ?> <?php echo htmlspecialchars($row['eb_pax']); ?> pax, <?php echo htmlspecialchars($row['sdate']); ?></p>
                                            </div>
                                            <div class="col-1">
                                                <p class="w-700"><?php echo htmlspecialchars($row['date_created']) ?></p>
                                            </div>
                                        </div>

                                        <div class="modal" id="<?php echo $rand ?>" tabindex="-1" aria-labelledby="<?php echo $rand ?>Label" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title w-700 ml-3" id="<?php echo $rand ?>Label">Booking Information</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <td>Branch</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['branchs']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Start Date</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['sdate']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>End Date</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['edate']) ?></td>
                                                            </tr>
                                                            <?php if (!empty($row['hotel'])): ?>
                                                                <tr>
                                                                    <td>Hotel</td>
                                                                    <td class="w-700"><?php echo htmlspecialchars($row['hotel']) ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            <tr>
                                                                <td>Pax</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['eb_pax']) ?></td>
                                                            </tr>
                                                            <?php if (!empty($row['attraction'])): ?>
                                                                <tr>
                                                                    <td>Attraction</td>
                                                                    <td class="w-700"><?php echo htmlspecialchars($row['attraction']) ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            <tr>
                                                                <td>Date Created</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['date_eb']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Status</td>
                                                                <td class="w-700">
                                                                    <?php
                                                                    if ($row['stat'] == 'Pending') {
                                                                        echo "<span class='text-warning'>Pending</span>";
                                                                    } else if ($row['stat'] == 'Payment') {
                                                                        echo "<span class='text-info'>Confirmed Booking, waiting for payment</span>";
                                                                    } else {
                                                                        echo "<span class='text-success'>Paid</span>";
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <?php if ($row['stat'] == 'Payment'): ?>
                                                                <tr>
                                                                    <td style="vertical-align:middle;">Confirmation</td>
                                                                    <td class="w-700"><?php echo "<a style='font-size: 12px;' class='btn btn-sm btn-success' href='../admin/confirmation-bookings/$row[confirmation_pdf]' target='_blank'>Download Confirmation Booking</a>" ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            <?php if ($row['stat'] == 'Payment'): ?>
                                                                <tr>
                                                                    <td>Payment</td>
                                                                    <td class="w-700"><?php echo "<a style='font-size: 12px;' class='btn btn-sm btn-danger' href='../payment/stripe-checkout.php?payment_id=$row[paymentinfoid]&userid=$row[userid]&bookid=$row[bookid]&booking_type=$row[booking_type]&booking_id=$row[booking_id]'>Pay Here</a>" ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="row populate-quote w-100 <?php if ($row['stat'] == 'Payment') {
                                                                                    echo $row['stat'];
                                                                                } ?>" data-toggle="modal" data-target="<?php echo "#$rand" ?>">
                                            <div class="col-3">
                                                <label for="" class="w-700">
                                                    <?php echo "Tour Package" ?><br>
                                                </label>
                                            </div>
                                            <div class="col-8">
                                                <p><?php echo htmlspecialchars($row['tour_title']); ?> <?php echo htmlspecialchars($row['pax']); ?> pax, <?php echo htmlspecialchars($row['tour_date']); ?></p>
                                            </div>
                                            <div class="col-1">
                                                <p class="w-700"><?php echo htmlspecialchars($row['date_created']) ?></p>
                                            </div>
                                        </div>

                                        <div class="modal" id="<?php echo $rand ?>" tabindex="-1" aria-labelledby="<?php echo $rand ?>Label" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title w-700 ml-3" id="<?php echo $rand ?>Label">Booking Information</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <td>Branch</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['branchs']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Tour Title</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['tour_title']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Pax</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['pax']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Date</td>
                                                                <td class="w-700"><?php echo htmlspecialchars($row['tour_date']) ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Status</td>
                                                                <td class="w-700">
                                                                    <?php
                                                                    if ($row['stat'] == 'Pending') {
                                                                        echo "<span class='text-warning'>Pending</span>";
                                                                    } else if ($row['stat'] == 'Payment') {
                                                                        echo "<span class='text-info'>Confirmed Booking, waiting for payment</span>";
                                                                    } else {
                                                                        echo "<span class='text-success'>Paid</span>";
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <?php if ($row['stat'] == 'Payment'): ?>
                                                                <tr>
                                                                    <td style="vertical-align:middle;">Confirmation</td>
                                                                    <td class="w-700"><?php echo "<a style='font-size: 12px;' class='btn btn-sm btn-success' href='../admin/confirmation-bookings/$row[confirmation_pdf]' target='_blank'>Download Confirmation Booking</a>" ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                            <?php if ($row['stat'] == 'Payment'): ?>
                                                                <tr>
                                                                    <td>Payment</td>
                                                                    <td class="w-700"><?php echo "<a style='font-size: 12px;' class='btn btn-sm btn-danger' href='../payment/stripe-checkout.php?payment_id=$row[paymentinfoid]&userid=$row[userid]&bookid=$row[bookid]&booking_type=$row[booking_type]&booking_id=$row[booking_id]'>Pay Here</a>" ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endif; ?>
                            <?php
                                }
                            } else {
                                echo "<p class='text-center w-100'>No bookings available. Book now!</p>";
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="successBooking"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <!-- <div class="modal-header">
                   <h5 class="modal-title w-700 text-success" id="request"></h5> -->
                    <form action="inc/tour.php" style="width: 100%;" id="ticket" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                        <div class="modal-body mb-4 mt-4">
                            <div class="row w-100">
                                <div class="col-12">
                                    <p class="text-justify">
                                        <span class="w-700">Thank you for your payment!</span>
                                        </br>
                                        </br>
                                        We appreciate your prompt payment. Your transaction has been successfully processed, and your booking is now confirmed.
                                        </br>
                                        </br>
                                        If you need to review your booking or access additional information, you can visit the "My Booking" page in your account.</br>
                                        </br>
                                        Should you have any questions or require further assistance, please don't hesitate to contact us. We're here to help!
                                        </br>
                                        </br>
                                        Thank you for choosing <span class="w-700" style="color: var(--maindark);">Zorella Travel Tours</span>. We look forward to serving you!
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>


    <?php

    if (isset($_GET['submit'])) {
        $title = $_GET['title'];
        $origin = $_GET['origin'];
        $branch = $_GET['branch'];
        $destination = $_GET['destination'];
        $date = $_GET['date'];
        $days = $_GET['days'];
        $pax = $_GET['pax'];
        $email = $_GET['email'];
        $status = 'Pending';
        $userid = $_GET['userid'];

        $query = "INSERT INTO quotation(title, origin, branch, destination, date, days, pax, email, status, userid) 
        VALUES ('$title','$origin', '$branch', '$destination', '$date', '$days', '$pax', '$email', '$status', '$userid')";

        if (mysqli_query($conn, $query)) {
            echo "<script>window.location.href='quotation.php?userid=$userid&alert=1';</script>";
        } else {
            echo mysqli_error($conn);
        }
    }
    ?>

    <script src="js/app.js"></script>
    <script src="js/validation.js"></script>
    <script>
        <?php if ($successMessage): ?>
            $(document).ready(function() {
                $('#success').modal('show');
            });
            <?php unset($_SESSION['paid']); ?>
        <?php endif; ?>

        $(document).ready(function($) {
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                    var forms = document.getElementsByClassName('needs-validation');
                    // Loop over them and prevent submission
                    var validation = Array.prototype.filter.call(forms, function(
                        form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
        });

        const queryString = window.location.search;
        const urlParams = new URLSearchParams(window.location.search);
        const alert = urlParams.get('alert');

        if (alert == 1) {
            toastr["success"]("Request for quotation sent successfully")

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
        }
    </script>
</body>

</html>