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
    <title>Ticketed Bookings</title>
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
    $active = "Customize";
    $on = "on";
    include "../../inc/Include.php";
    include "header-admin.php";
    include "side-bar-admin.php";

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
        b.branch = 'Calumpang'
        AND b.status <> 'Pending'
        AND b.booking_type = 'Customize'
    ORDER BY b.bookid DESC";

    $res = mysqli_query($conn, $ticketed);
    ?>
    <div class="main">
        <div class="row bkq">
            <div class="col-12 case">
                <div class="overlay">
                    <div class="spinner-grow text-light" role="status">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
                <h1><i class="fa-solid fa-envelope fa-fw"></i>&nbsp;&nbsp;Customize Bookings</h1>
                <hr>
                <div class="row frame-quote w-100">
                    <?php
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
                    } ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            var urlA = "ajax/load.php";
            var urlA = "ajax/fetch-counts.php";

            $(document).ajaxSend(function() {
                $(".overlay").fadeIn(300);
            });

            $("#branch").change(function() {
                branch = $(this).children(":selected").attr("value");
                adminid = $(this).children(":selected").attr("class");
                customize = 'customize';

                $.ajax({
                    type: 'POST',
                    url: urlA,
                    data: {
                        branch: branch,
                        customize: customize,
                        adminid: adminid
                    },
                    success: function(data) {
                        $('.row.frame-quote').html(data);
                    },
                    error: function(e) {
                        alert("error");
                    }
                }).done(function() {
                    // Third AJAX request for counts
                    $.ajax({
                        type: "POST",
                        url: urlCounts,
                        data: {
                            branch: branch,
                        },
                        dataType: "json",
                        success: function(response) {
                            // Update the counts in the HTML
                            $("#pending_count").text(response.pending_count);
                            $("#ticket_count").text(response.ticket_count);
                            $("#customize_count").text(response.customize_count);
                            $("#educ_count").text(response.educ_count);
                            $("#tour_count").text(response.tour_count);
                        },
                        error: function() {
                            alert("Error fetching counts");
                        },
                    }).done(function() {
                        $(".overlay").fadeOut(300);
                    });
                });
                console.log(branch);
            });
        })
    </script>
</body>

</html>