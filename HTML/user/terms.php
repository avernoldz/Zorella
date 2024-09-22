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
                        <div class="col-12" style="display:flex; justify-content: center;text-align:center;">
                            <label>Terms and Conditions</label>
                        </div>

                        <div class="col-12">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce vitae sagittis mi.
                                Maecenas venenatis quam porttitor ultricies bibendum. Nam condimentum nisi eget mi
                                facilisis, a viverra elit sodales. Integer lobortis venenatis nunc, id fermentum nulla
                                vulputate nec. Pellentesque sapien metus, luctus eu rutrum id, lacinia at neque. Sed
                                malesuada purus nec ligula mattis, in tristique nunc pulvinar. Donec vestibulum elit sit
                                amet cursus tincidunt. Maecenas molestie ex sit amet neque semper scelerisque.</p>
                            <p>Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos
                                himenaeos. Mauris sed consectetur nunc, et sodales orci. Praesent ultrices ex in ex
                                malesuada tempus. Integer a sodales nisi. Nulla et mi et dolor sodales vehicula ac vitae
                                magna. Praesent eget efficitur magna. Quisque efficitur est arcu, in blandit velit
                                lobortis at. Nulla id tempor lacus. Sed semper dui est, quis sagittis lorem consequat
                                gravida. Cras efficitur, est blandit imperdiet euismod, arcu lectus congue odio, eu
                                tincidunt tortor nisl a massa. Praesent sapien diam, congue vitae massa vel, interdum
                                rhoncus elit. Nulla massa purus, tristique quis luctus in, maximus sit amet justo.
                                Pellentesque ultrices mattis erat, quis eleifend nibh sollicitudin varius.</p>
                            <p>Sed turpis elit, ornare vitae sapien sit amet, tempus gravida ipsum. Vestibulum ante
                                ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Etiam lobortis
                                risus dui, nec gravida tortor mattis eget. Proin dignissim eros ac sem posuere, in
                                rhoncus augue dignissim. Maecenas convallis eros in nulla tempor cursus. Ut nec erat
                                risus. Praesent ut tristique ex. Ut commodo at purus at volutpat. Morbi egestas viverra
                                massa, elementum porta tortor congue vel. Cras tristique magna non magna pulvinar
                                lobortis sed quis nulla. Cras purus eros, mattis non scelerisque vel, sollicitudin vitae
                                enim. Maecenas quis magna id est varius euismod vitae ac dui. In in lobortis elit. Fusce
                                varius lorem sem, eget placerat dui commodo in. Etiam eu magna libero. Ut et ligula non
                                erat posuere porttitor eget eget elit.</p>
                            <p>Aliquam scelerisque sapien sed dui tempus laoreet. Duis aliquam mollis nulla, id
                                dignissim eros efficitur ac. Quisque malesuada mi non diam maximus malesuada.
                                Suspendisse laoreet nibh a sem rhoncus, quis hendrerit quam condimentum. Etiam finibus,
                                risus nec rhoncus aliquet, mi purus sollicitudin leo, ullamcorper eleifend augue est
                                quis erat. Vestibulum eu magna massa. Sed fringilla tortor ornare velit interdum, at
                                tristique lacus egestas. Pellentesque aliquet, tellus a sagittis venenatis, enim sem
                                sollicitudin nulla, ut lacinia diam nulla fringilla massa. Sed sed accumsan ex. Nam
                                vitae justo vestibulum, vehicula purus vel, accumsan nunc. Vestibulum maximus dapibus mi
                                vel pellentesque. Donec eget magna aliquam, iaculis justo nec, gravida metus. Aenean ac
                                lobortis tortor.</p>
                            <p>Proin a nunc in massa molestie ornare. Morbi quis magna sapien. Morbi mauris nulla,
                                malesuada sed tortor placerat, ultricies mattis diam. Praesent in libero in odio sodales
                                sollicitudin. Duis non enim consequat, scelerisque ex vel, congue nisl. Aenean sit amet
                                elit eros. Nulla vulputate ligula vehicula, tincidunt eros vel, tristique ipsum. Cras
                                lectus arcu, congue nec neque vitae, dictum euismod risus. Pellentesque mollis neque a
                                orci auctor pellentesque.</p>
                            <p>Nullam vitae est orci. Fusce ornare ante id porta faucibus. Vivamus condimentum libero ut
                                leo pulvinar, eget euismod mauris vestibulum. Mauris odio libero, suscipit vitae
                                imperdiet eget, molestie id ante. Sed molestie orci non egestas rhoncus. Duis suscipit
                                tincidunt dolor. Morbi in blandit enim, a pretium erat. In libero risus, consequat
                                maximus vestibulum ac, consequat quis risus. Donec venenatis interdum leo nec finibus.
                                Aenean auctor luctus lacus, ac viverra felis consectetur bibendum. Maecenas tempus
                                iaculis aliquam. Sed lobortis rutrum nunc, sed varius dolor facilisis in. Aliquam rutrum
                                dolor elit, vel suscipit lacus condimentum sed. Nulla vel lectus fermentum, pretium
                                tellus vel, pulvinar leo. Duis fringilla tellus in augue suscipit auctor.</p>
                            <p>Maecenas lacinia est vitae ligula facilisis, sed mollis elit vestibulum. Donec vehicula
                                sodales velit eget ultricies. Suspendisse faucibus, nisl hendrerit ultricies rhoncus,
                                ante nisl luctus nibh, ut consectetur erat nibh convallis dolor. Lorem ipsum dolor sit
                                amet, consectetur adipiscing elit. Nulla ornare auctor magna, ac sagittis nibh ultricies
                                et. Integer risus dui, pretium non efficitur ut, condimentum nec turpis. Fusce convallis
                                dignissim scelerisque. Mauris eu lacinia tellus. Nulla facilisi. Sed sit amet lectus
                                lectus. Aliquam hendrerit ultricies sapien nec scelerisque. Nullam vestibulum, erat
                                vitae ullamcorper consequat, metus mauris efficitur nulla, tristique porta sem massa
                                sollicitudin arcu. Phasellus neque augue, pellentesque in auctor ut, scelerisque sed
                                dui.</p>
                            <p>Proin ullamcorper nisl ac nulla elementum placerat. Sed massa mauris, fringilla ac
                                rhoncus sed, rhoncus non quam. Vivamus tincidunt egestas blandit. Etiam at neque
                                bibendum justo molestie egestas vel sit amet nisl. Curabitur sem urna, volutpat a
                                consequat accumsan, semper quis tellus. Suspendisse hendrerit ligula massa, sit amet
                                aliquam ligula pharetra eget. Praesent vel justo eget lectus mattis rhoncus. Class
                                aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.
                                Morbi at vestibulum magna. Etiam vitae mauris a felis vestibulum feugiat vel feugiat
                                metus.</p>
                            <p>Aliquam facilisis sapien nec diam eleifend sagittis. Duis commodo ullamcorper sapien, sit
                                amet ultrices nibh placerat vel. Vestibulum fringilla mollis quam, ut malesuada velit
                                dictum id. Mauris congue tempus elit, ac pretium tellus iaculis eu. Vestibulum eget ante
                                viverra urna scelerisque mattis. Nullam venenatis augue mauris, sit amet feugiat lorem
                                pharetra non. Nunc efficitur commodo mattis.</p>
                            <p>Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos
                                himenaeos. Nulla nisl eros, convallis vel gravida vitae, blandit sed neque. Vestibulum
                                molestie sapien et vehicula fermentum. Vivamus maximus maximus mauris ac aliquam. Nam
                                mattis auctor quam eget aliquet. Mauris tempor id ante vel aliquam. Sed leo tortor,
                                interdum id sodales a, malesuada ac ligula.</p>
                        </div>
                        <div class="col-12 mt-5">
                            <input type="checkbox" name="conditions" value="yes" id="conditions">
                            <label for="" class="w-700">I agree to the terms and conditions.</label>
                        </div>

                        <div class="col-12 mt-5" style="display:flex; justify-content: flex-end;">
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
                            <p class="w-700">Select a <span style="color: var(--maindark);">Payment</span> Method</p>
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