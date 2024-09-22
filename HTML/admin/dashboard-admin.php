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
    <title>Dashboard</title>
    <style>
        a {
            text-decoration: none;
            color: var(--font);
        }

        a:hover {
            color: white;
        }

        .row.new {
            background: var(--success);
        }

        .main .bkq .case {
            min-height: 50vh;
            height: 50vh;
            max-height: 50vh;
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

        p {
            margin: 0;
        }
    </style>
</head>

<body>

    <?php
    $adminid = $_GET['adminid'];
    $active = "Dashboard";
    $on = "off";
    include "../../inc/Include.php";
    include "header-admin.php";
    include "side-bar-admin.php";
    include "function/function.php";

    $ratings = "SELECT * FROM ratings INNER JOIN user ON ratings.userid = user.userid ORDER BY ratings.ratingsid DESC";
    $ratingsResults = mysqli_query($conn, $ratings);

    $quotation = "SELECT *, quotation.email as email2 FROM quotation INNER JOIN user ON quotation.userid = user.userid WHERE quotation.branch = 'Calumpang' ORDER BY quotation.quotationid DESC";
    $quotationResults = mysqli_query($conn, $quotation);

    $branch = 'Calumpang';  // Example branch
    $status = 'Pending'; // Example status

    $res = getBookings($conn, $branch, $status);

    ?>

    <div class="overlay">
        <div class="spinner-grow text-light" role="status">
            <span class="visually-hidden"></span>
        </div>
    </div>
    <div class="main">
        <div class="row">
            <div class="col head">
                <h1><i class="fa-solid fa-house fa-fw"></i>&nbsp;&nbsp;Dashboard</h1>
            </div>
        </div>
        <div class="row bkq">
            <div class="col case" id="result">
                <h1><i class="fa-solid fa-book fa-fw"></i>&nbsp;&nbsp;Bookings</h1>
                <hr>
                <div class="row frame-quote bookings w-100">
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
                        echo "<div class='text-center col'> No booking available</div>";
                    } ?>
                </div>
            </div>
            <div class="col-6 case ml-4">
                <h1><i class="fa-solid fa-envelope fa-fw"></i>&nbsp;&nbsp;Quotations</h1>
                <hr>
                <div class="row frame-quote quotation w-100">
                    <?php
                    if (mysqli_num_rows($quotationResults) > 0) {
                        while ($quotationRows = mysqli_fetch_array($quotationResults)) {
                            $date = date_create("$quotationRows[date]");
                            $dateFormat = date_format($date, "F d, Y");
                    ?>

                            <a href="view-quotation.php?adminid=<?php echo "$adminid&qoute=$quotationRows[quotationid]" ?>"
                                class="populate-quote w-100">
                                <div class="row populate-quote">
                                    <div class="col-3">
                                        <label for="" class="w-700">
                                            <?php echo "$quotationRows[firstname] $quotationRows[lastname]" ?><br>
                                        </label>
                                    </div>
                                    <div class="col-9">
                                        <p><?php echo "$quotationRows[title] - From $quotationRows[origin] to $quotationRows[destination], $quotationRows[pax] pax for $quotationRows[days], $dateFormat" ?></p>
                                    </div>
                                </div>
                            </a>
                    <?php
                        }
                    } else {
                        echo "<div class='text-center col'> No quotation available</div>";
                    } ?>
                </div>
            </div>
        </div>

        <div class="row ">
            <div class="col-7 case rv">
                <h1><i class="fa-solid fa-star fa-fw"></i>&nbsp;&nbsp;Ratings</h1>
                <hr>
                <div class="row frame-quote ratings">
                    <?php
                    if (mysqli_num_rows($ratingsResults) > 0) {
                        while ($ratingsRows = mysqli_fetch_array($ratingsResults)) {
                    ?>
                            <div class="row populate w-100"
                                id="<?php echo "$ratingsRows[ratingsid]"; ?>">
                                <div class="col-12">
                                    <small style="display:flex; justify-content: space-between;">
                                        <span>From:
                                            <label for="">
                                                <?php echo "$ratingsRows[firstname] $ratingsRows[lastname]" ?><br>
                                            </label>
                                        </span>
                                        <!-- <span for="" class="w-500">Date Created:
                                    <label
                                        for=""><?php echo "$ratingsRows[dateCreated]" ?></label></span>
                                -->
                                    </small>
                                    <div class="row">
                                        <div class="col-9">
                                            <p><?php echo "$ratingsRows[feedback]" ?>
                                            </p>
                                        </div>
                                        <div class="col-3">
                                            <?php
                                            $rate = intval($ratingsRows['ratings']);
                                            $blackRate = 5 - $rate;

                                            for ($rate; $rate > 0; $rate--) {
                                                echo "<i class='fa-solid fa-star fa-fw' style='color:var(--danger)'></i>";
                                            }

                                            for ($blackRate; $blackRate > 0; $blackRate--) {
                                                echo "<i class='fa-solid fa-star fa-fw' style='color:var(--font)'></i>";
                                            }
                                            ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<div class='text-center col'> No ratings available</div>";
                    }
                    ?>
                </div>
            </div>

            <div class="col case ml-4 rv">
                <h1><i class="fa-solid fa-clipboard fa-fw"></i>&nbsp;&nbsp;Payments</h1>
                <hr>
            </div>
        </div>
    </div>

    <!-- <script src="js/app.js"></script> -->
    <script>
        $(document).ready(function() {

            var urlA = "ajax/quotation.php";
            var urlB = "ajax/pending.php";
            var urlCounts = "ajax/fetch-counts.php";

            $(document).ajaxSend(function() {
                $(".overlay").fadeIn(300);
            });

            $("#branch").change(function() {
                branch = $(this).children(":selected").attr("value");
                adminid = $(this).children(":selected").attr("class");

                // First AJAX request for quotations
                $.ajax({
                    type: 'POST',
                    url: urlA,
                    data: {
                        branch: branch,
                        adminid: adminid
                    },
                    success: function(data) {
                        $('.row.frame-quote.quotation').html(data); // Update the HTML for quotations
                    },
                    error: function(e) {
                        alert("Error in fetching quotation data");
                    }
                }).done(function() {
                    // Second AJAX request for pending bookings
                    $.ajax({
                        type: 'POST',
                        url: urlB,
                        data: {
                            branch: branch,
                            adminid: adminid
                        },
                        success: function(data) {
                            $('.row.frame-quote.bookings').html(data); // Update the HTML for pending bookings
                        },
                        error: function(e) {
                            alert("Error in fetching pending bookings data");
                        }
                    }).done(function() {
                        // Third AJAX request for counts
                        $.ajax({
                            type: 'POST',
                            url: urlCounts,
                            data: {
                                branch: branch
                            },
                            dataType: 'json',
                            success: function(response) {
                                // Update the counts in the HTML
                                $('#pending_count').text(response.pending_count);
                                $('#ticket_count').text(response.ticket_count);
                                $('#customize_count').text(response.customize_count);
                                $('#educ_count').text(response.educ_count);
                            },
                            error: function() {
                                alert("Error fetching counts");
                            }
                        }).done(function() {
                            $(".overlay").fadeOut(300);
                        });
                    });
                });

                console.log(branch);
            });
        })
    </script>
</body>

</html>