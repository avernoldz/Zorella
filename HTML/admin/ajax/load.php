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
            $string = random_strings(4);
        ?>
            <tr>
                <td><?php echo "$row[paymentinfoid]" ?></td>
                <td><?php echo "$row[firstname] $row[lastname]" ?></td>
                <td><?php echo "$row[email]" ?></td>
                <td><?php echo "$row[booking_type]" ?></td>
                <td>&#x20B1; <?php echo "$row[total_price]" ?></td>
                <td><?php
                    if ($row['remaining_balance'] != '0') {
                        echo "&#x20B1; $row[remaining_balance]";
                    } else {
                        echo 'Paid';
                    }

                    ?></td>
                <td class="text-center">
                    <i class="fa-solid fa-pen fa-fw text-secondary" style="cursor: pointer;" data-target="#<?php echo $string; ?>" data-toggle="modal"></i>

                    <div class="modal fade" style="font-size: 14px;" id="<?php echo $string; ?>" tabindex="-1" role="dialog" aria-labelledby="acceptLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" style="font-size: 16px; font-weight:bold;">Payment</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="inc/gcash.php" style="width: 100%;" id="ticket" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                                    <div class="modal-body mb-4 mt-3">
                                        <div class="row w-100">
                                            <div class="col-12 text-left">
                                                <label for="">Amount Paid</label><label for="" class="required">*</label>
                                                <input type="text" id="paid_amount"
                                                    placeholder="PHP 0,000.00" name="paid_amount"
                                                    class="form-control" required>
                                            </div>
                                        </div>

                                        <input type="hidden" name="paymentinfoid" value="<?php echo $row['paymentinfoid'] ?>">
                                        <input type="hidden" name="adminid" value="<?php echo $adminid ?>">
                                        <input type="hidden" name="type" value="Full">
                                        <input type="hidden" name="balance" value="<?php echo $row['remaining_balance'] ?>">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal" style="font-size:14px;">Close</button>
                                        <button style="display:none" type="submit" name="update" id="submit" style="font-size:14px;">Next</button>
                                        <button type="button" class="btn btn-primary nextSubmit" style="font-size:14px;">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php
        }
    } else {
        echo '<td colspan="7" class="text-center"> No available payments</td>';
    }
}

if (isset($_POST["installment"])) {
    $installment = $_POST["installment"];
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
            $string = random_strings(4);
            $string2 = random_strings(5);
            $select = "SELECT * FROM installmenthistory WHERE paymentinfoid = '$row[paymentinfoid]' ORDER BY paid_date DESC";
            $ress = mysqli_query($conn, $select);
            $dataArray = mysqli_fetch_all($ress, MYSQLI_ASSOC);
        ?>
            <tr>
                <td><?php echo "$row[paymentinfoid]" ?></td>
                <td><?php echo "$row[firstname] $row[lastname]" ?></td>
                <td><?php echo "$row[email]" ?></td>
                <td><?php echo "$row[booking_type]" ?></td>
                <td>&#x20B1; <?php echo "$row[total_price]" ?></td>
                <td>&#x20B1; <?php echo "$row[remaining_balance]" ?></td>
                <td class="text-center">
                    <i class="fa-solid fa-pen fa-fw text-secondary" style="cursor: pointer;" data-target="#<?php echo $string2; ?>" data-toggle="modal"></i>
                    <i class="fa-solid fa-eye fa-fw text-secondary" style="cursor: pointer;" data-target="#<?php echo $string; ?>" data-toggle="modal"></i>

                    <div class="modal fade" style="font-size: 14px;" id="<?php echo $string; ?>" tabindex="-1" role="dialog" aria-labelledby="acceptLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" style="font-size: 16px; font-weight:bold;">Payment History</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class=" table table-striped table-hover" style="font-size: 12px;">
                                        <thead class="thead-light">
                                            <th class="p-1" style="width: 70%;">Date</th>
                                            <th class="p-1">Amount</th>
                                        </thead>
                                        <tbody>
                                            <?php if (count($dataArray) > 0) {
                                                foreach (array_reverse($dataArray) as $dataItem): ?>
                                                    <tr>
                                                        <td class="text-left p-1"><?php echo $formattedDate = (new DateTime($dataItem['paid_date']))->format('F j, Y : h:i A'); ?> </td>
                                                        <td class="text-left p-1">PHP <?php echo number_format($dataItem['amount'], 2) ?></td>
                                                    </tr>
                                            <?php
                                                endforeach;
                                            } else {
                                                echo "<tr><td colspan='2' class='p-1'>No available payments</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal" style="font-size: 14px;">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" style="font-size: 14px;" id="<?php echo $string2; ?>" tabindex="-1" role="dialog" aria-labelledby="acceptLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" style="font-size: 16px; font-weight:bold;">Payment</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="inc/gcash.php" style="width: 100%;" id="ticket" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                                    <div class="modal-body mb-4 mt-3">
                                        <div class="row w-100">
                                            <div class="col-12 text-left">
                                                <label for="">Amount Paid</label><label for="" class="required">*</label>
                                                <input type="text" id="paid_amount"
                                                    placeholder="PHP 0,000.00" name="paid_amount"
                                                    class="form-control" required>
                                            </div>
                                        </div>

                                        <input type="hidden" name="paymentinfoid" value="<?php echo $row['paymentinfoid'] ?>">
                                        <input type="hidden" name="adminid" value="<?php echo $adminid ?>">
                                        <input type="hidden" name="type" value="Install">
                                        <input type="hidden" name="balance" value="<?php echo $row['remaining_balance'] ?>">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal" style="font-size:14px;">Close</button>
                                        <button style="display:none" type="submit" name="update" id="submit" style="font-size:14px;">Next</button>
                                        <button type="button" class="btn btn-primary nextSubmit" style="font-size:14px;">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php
        }
    } else {
        echo '<td colspan="7" class="text-center"> No available payments</td>';
    }
}


if (isset($_POST["archive"])) {
    $id = $_POST["id"];
    $adminid = $_POST["adminid"];

    $query = "UPDATE tourpackage SET isArchive = TRUE WHERE tourid = '$id'";
    if (!mysqli_query($conn, $query)) {
        echo mysqli_error($conn);
    }

    $query = "SELECT * FROM tourpackage WHERE isArchive = FALSE";
    $res = mysqli_query($conn, $query);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res)) {
            $bsdate = date_create($row['bsdate']);
            $bedate = date_create($row['bedate']);

            // Format each date
            $formattedBsdate = date_format($bsdate, 'M j, Y');
            $formattedBedate = date_format($bedate, 'M j, Y');
        ?>
            <tr>
                <td><?php echo "$row[tourid]" ?></td>
                <td>
                    <img src="uploads/<?php echo "$row[img]" ?>" alt="tour-package" width="50px" height="50px" style="object-fit: cover;">
                </td>
                <td><?php echo "$row[title]" ?></td>
                <td>&#x20B1; <?php echo number_format($row['price'], 0) ?></td>
                <td><?php echo "$formattedBsdate - $formattedBedate" ?></td>
                <td>
                    <p><?php echo "$row[description]" ?></p>
                </td>

                <td class="text-center">
                    <!-- <i class="fa-solid fa-pen fa-fw text-primary"></i> -->
                    <i class="fa-solid fa-box-archive fa-fw text-primary archive" style="cursor: pointer;" data-id="<?php echo $row['tourid'] ?>"></i>
                </td>
            </tr>
<?php
        }
    }
}
