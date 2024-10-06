<?php
session_start();
session_regenerate_id();

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
    <title>Terms and Conditions</title>
    <style>
        .main .col.card .row {
            align-items: center;
        }

        .wd-100 {
            width: 100%;
        }

        #payment {
            display: none;
        }

        .payment-card .container input {
            display: none;
        }

        .payment-card.type .container .category {
            margin-top: 10px;
            padding-top: 20px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-gap: 5px;
        }

        .payment-card .category label {
            width: 100%;
            height: 65px;
            /* height: 145px; */
            padding: 20px 20px 20px 35px;
            box-shadow: 0px 0px 0px 1px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            /* justify-content: center; */
            align-items: center;
            cursor: pointer;
            border-radius: 2px;
            /* position: relative; */
        }

        .payment-card label:nth-child(2),
        .payment-card label:nth-child(3) {
            margin: 15px 0;
        }

        #visa:checked~.category .visaMethod,
        #full:checked~.col .fullMethod,
        #install:checked~.col .installMethod,
        #quarterly:checked~.additional-options .quarterlyPayment,
        #monthly:checked~.additional-options .monthlyPayment,
        #mastercard:checked~.category .mastercardMethod,
        #paypal:checked~.category .paypalMethod,
        #AMEX:checked~.category .amexMethod {
            box-shadow: 0px 0px 0px 1px #6064b6;
        }

        #visa:checked~.category .visaMethod .check,
        #full:checked~.col .fullMethod .check,
        #install:checked~.col .installMethod .check,
        #quarterly:checked~.additional-options .quarterlyPayment .check,
        #monthly:checked~.additional-options .monthlyPayment .check,
        #mastercard:checked~.category .mastercardMethod .check,
        #paypal:checked~.category .paypalMethod .check,
        #AMEX:checked~.category .amexMethod .check {
            display: block;
        }

        .payment-card label .imgName {
            display: flex;
            justify-content: space-between;
            justify-content: center;
            align-items: center;
            /* flex-wrap: wrap; */
            /* flex-direction: column; */
            /* gap: 10px; */
        }

        .payment-card .imgName span {
            margin-left: 50px;
            /* font-family: Arial, Helvetica, sans-serif; */
            /* position: absolute; */
            /* top: 72%; */
            /* transform: translateY(-72%); */
        }

        .payment-card.type .category label {
            /* height: 145px; */
            padding: 20px;
        }

        .payment-card.type .imgName span {
            margin-left: 10px
        }

        .payment-card .imgName .imgContainer {
            width: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            /* position: absolute; */
            /* top: 35%; */
            /* transform: translateY(-35%); */
        }

        .payment-card img {
            width: 50px;
            height: auto;
        }

        .payment-card .visa img {
            width: 80px;
            /* margin-left: 5px; */
        }

        .payment-card .mastercard img {
            width: 90px;
        }

        .payment-card .paypal img {
            width: 60px;
        }

        .payment-card .AMEX img {
            width: 50px;
        }

        .payment-card .check {
            display: none;
            position: absolute;
            /* top: -4px; */
            right: 50px;
        }

        .payment-card.type .check {
            top: -4px;
            right: -4px;
        }

        .payment-card .check i {
            font-size: 18px;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_GET['userid'];
    $active = "Ticketed";
    include "../../inc/Include.php";
    include "side-bar.php";
    include "header.php";
    $booking_id = $_SESSION['booking_id'];
    $booking_type = $_SESSION['booking_type'];

    $table_map = [
        'Ticketed' => 'ticket',
        'Customize' => 'ticket',
        'Educational' => 'educational',
    ];

    $table_name = $table_map[$booking_type] ?? 'tourbooking';

    function getPrimaryKey($table_name, $conn)
    {
        $query = "SHOW KEYS FROM $table_name WHERE Key_name = 'PRIMARY'";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc()['Column_name'] ?? null; // No argument in fetch_assoc
    }

    function getEducationalDetails($table_name, $booking_id, $conn)
    {
        // Get the primary key for the table
        $primary_key = getPrimaryKey($table_name, $conn);

        if (!$primary_key) {
            throw new Exception("No primary key found for table $table_name.");
        }

        // Prepare the SQL query using the primary key
        $query = "SELECT b.branch, e.* 
                  FROM $table_name e 
                  INNER JOIN booking b 
                  ON b.booking_id = e.$primary_key 
                  WHERE e.$primary_key = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc(); // Fetch the single row directly
    }

    $row = getEducationalDetails($table_name, $booking_id, $conn);
    ?>

    <form action="inc/payment.php" style="width: 100%;" class="needs-validation" method="POST" novalidate>
        <?php

        ?>
        <div class="main">
            <input type="hidden" value="<?php echo "$userid"; ?>"
                name="userid">
            <input type="hidden" value="round-trip" name="trip">

            <div class="row wrap" id="terms">
                <div class="col body">
                    <div class="row">
                        <div class="col-12 body" style="background-color: var(--main-300);color: white;">
                            <h4>Terms and Conditions</h4>
                        </div>
                    </div>

                    <div class="row body">
                        <div class="col-12 px-5 py-3 text-justify">
                            <p class="w-700">Terms and Conditions</p>
                            <p>It is crucial that you comprehend your legal responsibilities and rights about the
                                Platform before making any bookings or buying Tickets on the Platform. Because they
                                regulate how you use the Platform and its features and content, we so urge you to carefully
                                read these Terms and Conditions. Your use of the Platform is contingent upon your unrestricted
                                and complete acceptance of these Terms and Conditions.</p>
                            <br>
                            <p class="w-700">Updates to these Terms and Conditions</p>
                            <p>As of the date indicated in the Terms and Conditions header, all revisions to these terms and conditions will be legally binding. When you use the Platform after the revised terms and conditions are published, you agree to be bound by the revised terms and conditions.
                                These Terms & Conditions are subject to change at any moment, in our sole discretion. The most current version of these terms and conditions, along with the date of the most recent update, will be posted on the platform.
                            </p>
                            <br>

                            <p class="w-700">To make a booking </p>
                            <p>If you would like an offline application form, please request one or follow the instructions on our website. Each person traveling must complete the applicable application form. Next, you need to send us the completed booking form and the payments mentioned in paragraph 2 below.
                                Your booking will be considered solid and a contract between us will enter into effect as soon as we receive your completed application form and your deposit, provided that we have already confirmed the availability of your chosen arrangements and you book within any applicable time limit. After that, we will email you a receipt for every money received as well as our invoice. If availability has not been verified, your reservation will be considered confirmed, and a contract will be formed when we send you our invoice. Any electronic acknowledgement of your booking made through our website without previous availability confirmation is not a confirmation of it.
                                As soon as you receive your invoice, please carefully review it. If you believe that any information on the invoice or any other document is inaccurate or missing, please get in touch with us right away.
                            </p>
                            <br>

                            <p class="w-700">Our Role and Limitation</p>
                            <p>On behalf of airlines, flights, and properties/accommodations, we function as the official representative. Please be aware that although we act as their official representative, we do not manage these services directly. Our involvement is restricted to acting as a go-between for you and the outside service providers (such hotels and flights). The caliber and efficiency of the services rendered by the airlines, flights, and properties/accommodations are beyond our control. You understand and agree that, to the degree specified in these Terms and Conditions, we are solely accountable for our booking service. Because of this, we create a virtual email address on your behalf and give the third-party service providers your payment information in order to make reservations on your behalf. We do not offer property/accommodation rentals or air transportation services, thus we cannot be held liable if your luggage is misplaced, destroyed, taken, or otherwise rendered inaccessible to you.</p>
                            <br>

                            <p class="w-700">Special requests</p>
                            <p>If you have any specific preferences, please let us know when you make your reservation. We shall make every effort to fulfill any such request or make arrangements
                                for our vendors to do so. A special request's inclusion on your invoice or other documentation, or its acknowledgment that it has been noted or forwarded to the supplier, does not guarantee that it will be fulfilled. Special requests are contingent on availability until otherwise verified.
                                If it is feasible, you should get written proof that a unique request that is significant to you will be fulfilled. This is for your own safety.
                            </p><br>

                            <p class="w-700">Our website</p>
                            <p>The information contained in our website and in our other advertising material is believed correct to the best of our knowledge at the time of publication. However, errors may occasionally occur and information may subsequently change.
                                You must therefore ensure you check all details of your chosen trip (including the price) with us at the time of booking.
                            </p><br>

                        </div>
                        <div class="col-12 mt-2 px-5">
                            <input type="checkbox" name="conditions" value="yes" id="conditions">
                            <label for="" class="w-700">I agree to the terms and conditions.</label>
                        </div>

                        <div class="col-12 mt-5 px-5" style="display:flex; justify-content: flex-end;">
                            <button id="next-terms" class="btn btn-primary pl-5 pr-5 btn-next" type="button">Next</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row wrap" id="payment">
                <div class="col-8 body">
                    <div class="row">
                        <div class="col-12 body" style="background-color: var(--main-300);color: white;">
                            <h4>Booking Information</h4>
                        </div>
                    </div>

                    <?php if ($booking_type == 'Ticketed' || $booking_type == 'Customize'): ?>
                        <div class="row body">
                            <div class="col-4">
                                <label for="branch">Branch</label>
                                <input type="text" class="form-control" id="branch" value="<?php echo $row['branch'] ?>" readonly>
                            </div>

                            <div class="col-4">
                                <label for="flighttype">Flight Type</label>
                                <input type="text" class="form-control" id="flighttype" value="<?php echo $row['flighttype'] ?>" readonly>
                            </div>

                            <div class="col-4">
                                <label for="tickettype">Ticket Type</label>
                                <input type="text" class="form-control" id="tickettype" value="<?php echo $row['tickettype'] ?>" readonly>
                            </div>

                            <div class="row mt-4 wd-100">
                                <div class="col-4">
                                    <label for="origin">Origin</label>
                                    <input type="text" class="form-control" id="origin" value="<?php echo $row['origin'] ?>" readonly>
                                </div>
                                <div class="col-4">
                                    <label for="destination">Destination</label>
                                    <input type="text" class="form-control" id="destination" value="<?php echo $row['destination'] ?>" readonly>
                                </div>
                                <div class="col-4">
                                    <label for="classtype">Class Type</label>
                                    <input type="text" class="form-control" id="classtype" value="<?php echo $row['classtype'] ?>" readonly>
                                </div>
                            </div>

                            <div class="row mt-4 wd-100">
                                <div class="col-4">
                                    <label for="departure">Departure</label>
                                    <input type="text" class="form-control" id="departure" value="<?php echo $row['departure'] ?>" readonly>
                                </div>
                                <?php if (!$row['arrival'] == 0000 - 00 - 00): ?>
                                    <div class="col-4 arrival-container">
                                        <label for="arrival">Arrival</label>
                                        <input type="text" class="form-control" id="arrival" value="<?php echo $row['arrival'] ?>" readonly>
                                    </div>
                                <?php endif; ?>
                                <div class="col-4">
                                    <label for="directflight">Direct Flight</label>
                                    <input type="text" class="form-control" id="directflight" value="<?php echo $row['directflight'] ?>" readonly>
                                </div>
                            </div>

                            <div class="row mt-4 wd-100">
                                <div class="col-2">
                                    <label for="adult">Adult</label>
                                    <input type="text" class="form-control" id="adult" value="<?php echo $row['adult'] ?>" readonly>

                                </div>

                                <?php if (!empty($row['child'])): ?>
                                    <div class="col-2">
                                        <label for="child">Child</label>
                                        <input type="text" class="form-control" id="child" value="<?php echo $row['child'] ?>" readonly>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($row['infant'])): ?>
                                    <div class="col-2">
                                        <label for="infant">Infant</label>
                                        <input type="text" class="form-control" id="infant" value="<?php echo $row['infant'] ?>" readonly>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($row['senior'])): ?>
                                    <div class="col-2">
                                        <label for="senior">Senior</label>
                                        <input type="text" class="form-control" id="senior" value="<?php echo $row['senior'] ?>" readonly>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-4 mt-4 mb-3">
                                <label for="airline">Airline</label>
                                <input type="text" class="form-control" id="airline" value="<?php echo $row['airline'] ?>" readonly>
                            </div>
                        </div>
                    <?php elseif ($booking_type == 'Educational'): ?>
                        <div class="row body">
                            <div class="col-6 mb-4">
                                <label for="branch">Branch</label>
                                <input type="text" class="form-control" id="branch" value="<?php echo $row['branch'] ?>" readonly>
                            </div>
                            <div class="w-100"></div>
                            <div class="col-4">
                                <label for="flighttype">Start Date</label>
                                <input type="text" class="form-control" value="<?php echo $row['sdate'] ?>" readonly>
                            </div>

                            <div class="col-4">
                                <label for="tickettype">End Date</label>
                                <input type="text" class="form-control" value="<?php echo $row['edate'] ?>" readonly>
                            </div>

                            <div class="row mt-4 wd-100">
                                <?php if (!empty($row['hotel'])): ?>
                                    <div class="col-4">
                                        <label for="destination">Hotel</label>
                                        <input type="text" class="form-control" value="<?php echo $row['hotel'] ?>" readonly>

                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($row['attraction'])): ?>
                                    <div class="col-4">
                                        <label for="classtype">Attraction</label>
                                        <input type="text" class="form-control" i value="<?php echo $row['attraction'] ?>" readonly>

                                    </div>
                                <?php endif; ?>
                                <div class="col-4">
                                    <label for="destination">Pax</label>
                                    <input type="text" class="form-control" value="<?php echo $row['pax'] ?>" readonly>
                                </div>


                            </div>
                        </div>
                    <?php elseif ($booking_type == 'Tour Package'): ?>
                        <div class="row body">
                            <div class="col-8 mb-4">
                                <label for="branch">Tour Title</label>
                                <input type="text" class="form-control" id="branch" value="<?php echo $row['tour_title'] ?>" readonly>
                            </div>
                            <div class="w-100"></div>
                            <div class="col-6 mb-4">
                                <label for="branch">Branch</label>
                                <input type="text" class="form-control" id="branch" value="<?php echo $row['branch'] ?>" readonly>
                            </div>
                            <div class="col-4">
                                <label for="flighttype">Pax</label>
                                <input type="text" class="form-control" value="<?php echo $row['pax'] ?>" readonly>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col body">
                    <div class="row">
                        <div class="col-12 body" style="background-color: var(--main-300);color: white;">
                            <h4>Payment</h4>
                        </div>
                    </div>

                    <div class="row body">
                        <div class="row wd-100">
                            <div class="col-12">
                                <p class="w-700">Select a <span style="color: var(--maindark);">Payment</span> Type</p>
                            </div>
                        </div>

                        <div class="row w-100">
                            <div class="col-12 payment-card type">
                                <div class="row w-100">
                                    <div class="row w-100 category">
                                        <input type="radio" name="payment_type" id="full" value="Full Payment" required>
                                        <input type="radio" name="payment_type" id="install" value="Installment" required>
                                        <div class="col">
                                            <label for="full" class="fullMethod">
                                                <div class="imgName">
                                                    <i class="fa-solid fa-money-bill fa-fw"></i>
                                                    <span class="name">Full Payment</span>
                                                </div>
                                                <span class="check"><i class="fa-solid fa-circle-check" style="color: var(--maindark);"></i></span>
                                            </label>
                                        </div>
                                        <div class="col ml-3">
                                            <label for="install" class="installMethod">
                                                <div class="imgName">
                                                    <i class="fa-solid fa-clock fa-fw"></i>
                                                    <span class="name">Installment</span>
                                                </div>
                                                <span class="check"><i class="fa-solid fa-circle-check" style="color: var(--maindark);"></i></span>
                                            </label>
                                        </div>

                                        <input type="radio" name="installment_type" id="monthly" value="Bimonthly">
                                        <input type="radio" name="installment_type" id="quarterly" value="Quarterly">
                                        <!-- New div for additional payment options -->
                                        <div class="additional-options row w-100 mt-2" style="display: none;">
                                            <div class="col">
                                                <label class="quarterlyPayment" for="quarterly">
                                                    <div class="imgName">
                                                        <i class="fa-solid fa-calendar-quarter fa-fw"></i>
                                                        <span class="name">Quarterly Payment</span>
                                                        <span class="check"><i class="fa-solid fa-circle-check" style="color: var(--maindark);"></i></span>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="col ml-3">
                                                <label class="monthlyPayment" for="monthly">
                                                    <div class="imgName">
                                                        <i class="fa-solid fa-calendar-month fa-fw"></i>
                                                        <span class="name">Bimonthly Payment</span>
                                                    </div>
                                                    <span class="check"><i class="fa-solid fa-circle-check" style="color: var(--maindark);"></i></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback">
                                            Select payment method type
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <p class="w-700">Select a <span style="color: var(--maindark);">Preferred Payment</span> Method</p>
                        </div>
                        <div class="row wd-100">
                            <div class="col-12 payment-card">
                                <input type="radio" name="payment_method" id="visa" value="GCASH" required>
                                <input type="radio" name="payment_method" id="mastercard" value="Paymaya" required>
                                <input type="radio" name="payment_method" id="paypal" value="Credit/Debit" required>
                                <div class="category">
                                    <label for="visa" class="visaMethod">
                                        <div class="imgName">
                                            <div class="imgContainer visa">
                                                <img src="https://logolook.net/wp-content/uploads/2024/02/GCash-Logo.png" alt="gcash-payment">
                                            </div>
                                            <span class="name">GCASH</span>
                                        </div>
                                        <span class="check"><i class="fa-solid fa-circle-check" style="color: var(--maindark);"></i></span>
                                    </label>

                                    <label for="mastercard" class="mastercardMethod">
                                        <div class="imgName">
                                            <div class="imgContainer mastercard">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/9/9a/PayMaya_Logo.png" alt="paymaya-payment">
                                            </div>
                                            <span class="name">Paymaya</span>
                                        </div>
                                        <span class="check"><i class="fa-solid fa-circle-check" style="color: var(--maindark);"></i></span>
                                    </label>

                                    <label for="paypal" class="paypalMethod">
                                        <div class="imgName">
                                            <div class="imgContainer paypal">
                                                <img src="https://i.ibb.co/vdbBkgT/mastercard.jpg" alt="mastercard-payment">
                                            </div>
                                            <span class="name">Credit/Debit Card</span>
                                        </div>
                                        <span class="check"><i class="fa-solid fa-circle-check" style="color: var(--maindark);"></i></span>
                                    </label>
                                </div>
                                <div class="invalid-feedback">
                                    Select payment method first
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3" style="display:flex; justify-content: flex-end;">
                            <button style="display:none" type="submit" name="payment" id="submit">Next</button>
                            <button id="next" type="button" class="btn btn-primary pl-5 pr-5 btn-next" style="background:var(--maindark);border:1px solid var(--maindark);">Book</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="js/validation.js"></script>
    <script>
        $(document).ready(function($) {

            $('.additional-options').hide();

            // When a radio button is selected
            $('#install').change(function() {
                if ($(this).is(':checked')) {
                    // Show additional options if "Installment" is selected
                    $('.additional-options').slideDown();
                }
            });

            // When the #full radio button is selected or changed
            $('#full').change(function() {
                if ($(this).is(':checked')) {
                    // Hide additional options if "Full Payment" is selected
                    $('.additional-options').slideUp();

                    $('#monthly, #quarterly').prop('checked', false);
                }
            });

            $('#next-terms').click(function() {
                $('#terms').hide();
                $('#payment').css("display", "flex");
            })

            //Disable submit until checkbox is checked
            $(function() {
                $('#conditions').on('change', function() {
                    $('#next-terms').prop("disabled", !this
                        .checked); //true: disabled, false: enabled
                }).trigger('change'); //page load trigger event
            });
        });
    </script>
</body>

</html>