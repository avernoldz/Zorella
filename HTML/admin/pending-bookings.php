<?php
session_start();
session_regenerate_id();

if (!$_SESSION['adminid']) {
    header("Location:index.php?Login-first");
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
    <title>Pending Bookings</title>
    <style>
        .main .bkq .case {
            min-height: 88vh;
            height: 88vh;
            max-height: 88vh;
            overflow: hidden;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .main .row.frame-quote {
            overflow-y: auto;
            max-height: 100%;
        }

        .row.populate-quote p {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 100%;
        }

        p,
        label {
            margin: 0;
        }
    </style>
</head>

<body>

    <?php
    $adminid = $_GET['adminid'];
    $active = "Pending Bookings";
    $on = "on";
    include "../../inc/Include.php";
    include "header-admin.php";
    include "side-bar-admin.php";

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
    eb.pax AS ebpax,
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
        b.status = 'pending' && b.branch = 'Calumpang' ORDER BY b.bookid DESC";

    $res = mysqli_query($conn, $pending);
    ?>
    <div class="main">
        <div class="row bkq">
            <div class="col-12 case">
                <div class="overlay">
                    <div class="spinner-grow text-light" role="status">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
                <h1><i class="fa-solid fa-envelope fa-fw"></i>&nbsp;&nbsp;Pending Bookings</h1>
                <hr>
                <div class="row frame-quote w-100">
                    <?php
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
                                            <p><?php echo htmlspecialchars($row['hotel']); ?> <?php echo htmlspecialchars($row['ebpax']); ?> pax, <?php echo htmlspecialchars($dateFormat1); ?></p>
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
                        echo "<div class='text-center col'> No data found</div>";
                    } ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var urlA = "ajax/pending.php";

            $(document).ajaxSend(function() {
                $(".overlay").fadeIn(300);
            });

            $("#branch").change(function() {
                branch = $(this).children(":selected").attr("value");
                adminid = $(this).children(":selected").attr("class");

                $.ajax({
                    type: 'POST',
                    url: urlA,
                    data: {
                        branch: branch,
                        adminid: adminid
                    },
                    success: function(data) {
                        $('.row.frame-quote').html(data);
                    },
                    error: function(e) {
                        alert("error");
                    }
                }).done(function() {
                    setTimeout(function() {
                        $(".overlay").fadeOut(300);
                    }, 500);
                });
                console.log(branch);
            });
        })
    </script>
    <script src="js/app.js"></script>
</body>

</html>