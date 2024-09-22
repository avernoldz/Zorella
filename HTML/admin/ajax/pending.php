<?php
include '../../../inc/Include.php';

if (isset($_POST["branch"])) {
    $branch = $_POST["branch"];
    $adminid = $_POST["adminid"];

    $pending = "SELECT
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
    eb.hotel,
    tc.*
    FROM
        booking b
    INNER JOIN
        user u ON b.userid = u.userid
    LEFT JOIN
        educational eb ON b.booking_type = 'Educational' AND b.booking_id = eb.educationalid
    LEFT JOIN
        tourbooking tc ON b.booking_type = 'Tour Package' AND b.booking_id = tc.tour_bookid
    LEFT JOIN
        ticket tb ON b.booking_id = tb.ticketid
        AND (b.booking_type = 'Ticketed' OR
            b.booking_type = 'Customize')
    WHERE
        b.status = 'Pending' AND b.branch = '$branch' ORDER BY b.bookid DESC";

    $res = mysqli_query($conn, $pending);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res)) {
            $dateFormat = date_format(date_create($row['departure']), "F d, Y");
            $dateFormat1 = date_format(date_create($row['sdate']), "F d, Y");
            $dateFormat2 = date_format(date_create($row['created_at']), "M d, Y");

            $total = $row['adult'] + $row['child'] + $row['infant'] + $row['senior'];
?>

            <?php if ($row['booking_type'] == 'Customize' || $row['booking_type'] == 'Ticketed'): ?>
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
            <?php elseif ($row['booking_type'] == 'Educational'): ?>
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
            <?php else: ?>
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
            <?php endif; ?>
<?php
        }
    } else {
        echo "<div class='text-center col'> No booking available</div>";
    }
}
