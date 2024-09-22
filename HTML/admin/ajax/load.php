<?php
include '../../../inc/Include.php';

if (isset($_POST["customize"])) {
    $customize = $_POST["customize"];
    $branch = $_POST["branch"];
    $adminid = $_POST["adminid"];

    $ticketed = "SELECT
    u.userid,
    u.firstname,
    u.lastname,
    b.bookid,
    b.booking_id,
    b.booking_type,
    b.status,
    b.created_at,
    tb.*,
    eb.sdate,
    eb.pax,
    eb.hotel
    FROM
        booking b
    INNER JOIN
        user u ON b.userid = u.userid
    LEFT JOIN
        educational eb ON b.booking_type = 'Educational' AND b.booking_id = eb.educationalid
    LEFT JOIN
        ticket tb ON b.booking_id = tb.ticketid
        AND (b.booking_type = 'Ticketed' OR
            b.booking_type = 'Customize')
    WHERE
        b.branch = '$branch'
        AND b.status <> 'Pending'
        AND b.booking_type = 'Customize'
    ORDER BY b.bookid DESC";

    $res = mysqli_query($conn, $ticketed);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res)) {
            $dateFormat = date_format(date_create($row['departure']), "F d, Y");
            $dateFormat2 = date_format(date_create($row['created_at']), "M d, Y");

            $total = $row['adult'] + $row['child'] + $row['infant'] + $row['senior'];
?>
            <a href="view-bookings.php?adminid=<?php echo "$adminid&bookingid=$row[booking_id]&bookingtype=$row[booking_type]&bookid=$row[bookid]" ?>"
                class="populate-quote w-100">
                <div class="row populate-quote">
                    <div class="col-3">
                        <label for="" class="w-700">
                            <?php echo "$row[firstname] $row[lastname]" ?><br>
                        </label>

                    </div>
                    <div class="col-8">
                        <p><?php echo "From " . htmlspecialchars($row['origin']) . " to " . htmlspecialchars($row['destination']) . ", " . htmlspecialchars($total) . " pax, " . htmlspecialchars($dateFormat); ?></p>

                    </div>
                    <div class="col-1">
                        <p class="w-700"><?php echo "$dateFormat2" ?></p>
                    </div>
                </div>
            </a>
        <?php
        }
    } else {
        echo "<div class='text-center col'> No bookings available</div>";
    }
}

if (isset($_POST["ticketed"])) {
    $customize = $_POST["ticketed"];
    $branch = $_POST["branch"];
    $adminid = $_POST["adminid"];

    $ticketed = "SELECT
    u.userid,
    u.firstname,
    u.lastname,
    b.bookid,
    b.booking_id,
    b.booking_type,
    b.status,
    b.created_at,
    tb.*
    FROM
        booking b
    INNER JOIN
        user u ON b.userid = u.userid
    LEFT JOIN
        ticket tb ON b.booking_id = tb.ticketid
        AND (b.booking_type = 'Ticketed' OR
            b.booking_type = 'Customize')
    WHERE
        b.branch = '$branch'
        AND b.status <> 'Pending'
        AND b.booking_type = 'Ticketed'
    ORDER BY b.bookid DESC";

    $res = mysqli_query($conn, $ticketed);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res)) {
            $dateFormat = date_format(date_create($row['departure']), "F d, Y");
            $dateFormat2 = date_format(date_create($row['created_at']), "M d, Y");

            $total = $row['adult'] + $row['child'] + $row['infant'] + $row['senior'];
        ?>
            <a href="view-bookings.php?adminid=<?php echo "$adminid&bookingid=$row[booking_id]&bookingtype=$row[booking_type]&bookid=$row[bookid]" ?>"
                class="populate-quote w-100">
                <div class="row populate-quote">
                    <div class="col-3">
                        <label for="" class="w-700">
                            <?php echo "$row[firstname] $row[lastname]" ?><br>
                        </label>

                    </div>
                    <div class="col-8">
                        <p><?php echo "From " . htmlspecialchars($row['origin']) . " to " . htmlspecialchars($row['destination']) . ", " . htmlspecialchars($total) . " pax, " . htmlspecialchars($dateFormat); ?></p>

                    </div>
                    <div class="col-1">
                        <p class="w-700"><?php echo "$dateFormat2" ?></p>
                    </div>
                </div>
            </a>
        <?php
        }
    } else {
        echo "<div class='text-center col'> No bookings available</div>";
    }
}


if (isset($_POST["educational"])) {
    $customize = $_POST["educational"];
    $branch = $_POST["branch"];
    $adminid = $_POST["adminid"];

    $ticketed = "SELECT
    u.userid,
    u.firstname,
    u.lastname,
    b.bookid,
    b.booking_id,
    b.booking_type,
    b.status,
    b.created_at,
    eb.sdate,
    eb.pax,
    eb.hotel
    FROM
        booking b
    INNER JOIN
        user u ON b.userid = u.userid
    LEFT JOIN
        educational eb ON b.booking_type = 'Educational' AND b.booking_id = eb.educationalid
    WHERE
        b.branch = '$branch'
        AND b.status <> 'Pending'
        AND b.booking_type = 'Educational'
    ORDER BY b.bookid DESC";

    $res = mysqli_query($conn, $ticketed);;
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res)) {
            $dateFormat1 = date_format(date_create($row['sdate']), "F d, Y");
            $dateFormat2 = date_format(date_create($row['created_at']), "M d, Y");

        ?>

            <a href="view-educational.php?adminid=<?php echo "$adminid&bookingid=$row[booking_id]&bookingtype=$row[booking_type]&bookid=$row[bookid]" ?>"
                class="populate-quote w-100">
                <div class="row populate-quote">
                    <div class="col-3">
                        <label for="" class="w-700">
                            <?php echo "$row[firstname] $row[lastname]" ?><br>
                        </label>

                    </div>
                    <div class="col-8">
                        <p><?php echo htmlspecialchars($row['hotel']); ?> <?php echo htmlspecialchars($row['pax']); ?> pax, <?php echo htmlspecialchars($dateFormat1); ?></p>
                    </div>
                    <div class="col-1">
                        <p class="w-700"><?php echo "$dateFormat2" ?></p>
                    </div>
                </div>
            </a>
        <?php
        }
    } else {
        echo "<div class='text-center col'> No bookings available</div>";
    }
}

if (isset($_POST["tour"])) {
    $customize = $_POST["tour"];
    $branch = $_POST["branch"];
    $adminid = $_POST["adminid"];

    $ticketed = "SELECT
    u.userid,
    u.firstname,
    u.lastname,
    b.bookid,
    b.booking_id,
    b.booking_type,
    b.status,
    b.created_at,
    tb.*,
    tc.*
    FROM
        booking b
    INNER JOIN
        user u ON b.userid = u.userid
    LEFT JOIN
        tourbooking tb ON b.booking_id = tb.tour_bookid AND b.booking_type = 'Tour Package' 
    LEFT JOIN
        tourpackage tc ON tc.tourid = tb.tourid
    WHERE
        b.branch = '$branch'
        AND b.status <> 'Pending'
        AND b.booking_type = 'Tour Package'
    ORDER BY b.bookid DESC";

    $res = mysqli_query($conn, $ticketed);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res)) {
            $dateFormat1 = date_format(date_create($row['tour_date']), "F d, Y");
            $dateFormat2 = date_format(date_create($row['created_at']), "M d, Y");
        ?>

            <a href="view-tour.php?adminid=<?php echo "$adminid&bookingid=$row[booking_id]&bookingtype=$row[booking_type]&bookid=$row[bookid]" ?>"
                class="populate-quote w-100">
                <div class="row populate-quote">
                    <div class="col-3">
                        <label for="" class="w-700">
                            <?php echo "$row[firstname] $row[lastname]" ?><br>
                        </label>
                    </div>
                    <div class="col-8">
                        <p><?php echo htmlspecialchars($row['tour_title']); ?> <?php echo htmlspecialchars($row['pax']); ?> pax, <?php echo htmlspecialchars($dateFormat1); ?></p>
                    </div>
                    <div class="col-1">
                        <p class="w-700"><?php echo "$dateFormat2" ?></p>
                    </div>
                </div>
            </a>
        <?php
        }
    } else {
        echo "<div class='text-center col'> No bookings available</div>";
    }
}


if (isset($_POST["fullpayment"])) {
    $fullpayment = $_POST["fullpayment"];
    $payment = $_POST["payment"];
    $branch = $_POST["branch"];
    $adminid = $_POST["adminid"];

    $ticketed = "SELECT user.firstname, user.lastname, user.email, payment.*, paymentinfo.*, booking.*
    FROM paymentinfo
    INNER JOIN payment ON paymentinfo.payment_id = payment.paymentid
    INNER JOIN booking ON booking.booking_id = payment.booking_id AND booking.booking_type = payment.booking_type
    INNER JOIN user ON user.userid = paymentinfo.userid
    WHERE payment.payment_type = '$payment' AND booking.branch = '$branch'";

    $res = mysqli_query($conn, $ticketed);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res)) {
        ?>
            <tr>
                <td><?php echo "$row[paymentinfoid]" ?></td>
                <td><?php echo "$row[firstname] $row[lastname]" ?></td>
                <td><?php echo "$row[email]" ?></td>
                <td><?php echo "$row[booking_type]" ?></td>
                <td>&#x20B1; <?php echo "$row[total_price]" ?></td>
                <td>&#x20B1; <?php echo "$row[remaining_balance]" ?></td>
                <td class="text-center"><i class="fa-solid fa-pen fa-fw text-primary"></i></td>
            </tr>
<?php
        }
    } else {
        echo "<tr class='text-center col'> <td  colspan='7'>No payment available</td></tr>";
    }
}
