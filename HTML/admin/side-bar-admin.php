<?php
$sql = "
SELECT
    COUNT(CASE WHEN b.booking_type = 'Ticketed' AND b.status <> 'pending' THEN 1 END) AS ticket_count,
    COUNT(CASE WHEN b.booking_type = 'Customize' AND b.status <> 'pending' THEN 1 END) AS customize_count,
    COUNT(CASE WHEN b.booking_type = 'Educational' AND b.status <> 'pending' THEN 1 END) AS educ_count,
    COUNT(CASE WHEN b.booking_type = 'Tour Package' AND b.status <> 'pending' THEN 1 END) AS tour_count,
    COUNT(CASE WHEN b.status = 'pending' THEN 1 END) AS pending_count
FROM
    booking b
INNER JOIN
    user u ON b.userid = u.userid
LEFT JOIN
    educational eb ON b.booking_type = 'Educational' AND b.booking_id = eb.educationalid
LEFT JOIN
    ticket tb ON b.booking_id = tb.ticketid
    AND (b.booking_type = 'Ticketed' OR b.booking_type = 'Customize')
WHERE
    b.branch = 'Calumpang';
";

// Execute the query
$res2 = mysqli_query($conn, $sql);

// Fetch the results
$row = mysqli_fetch_assoc($res2);

$sql2 = "
    SELECT
        COUNT(CASE WHEN p.payment_type = 'Full Payment' THEN 1 END) AS full_count,
        COUNT(CASE WHEN p.payment_type = 'Installment' THEN 1 END) AS installment_count
    FROM
        payment p;";

// Execute the query
$res2 = $conn->query($sql2);

// Fetch the results
$row2 = $res2->fetch_assoc();

// Access the counts
$count_ticket = $row['ticket_count'];
$count_customize = $row['customize_count'];
$count_educ = $row['educ_count'];
$count_pending = $row['pending_count'];
$count_tour = $row['tour_count'];
$full_count = $row2['full_count'];
$installment_count = $row2['installment_count'];

?>

<div class="side-bar">
    <div class="wrapper">
        <div class="row">
            <div class="col head">
                <a
                    href="dashboard-admin.php?adminid=<?php echo $adminid ?>">
                    <div class="title">

                        <div class="row">
                            <div class="col">
                                <img src="../../Assets/icon.png" alt="lspu-logo" width="50px">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h1>ZORELLA TRAVEL AND TOURS</h1>
                            </div>
                        </div>


                    </div>
                </a>
                <ul class="mt-3">
                    <a
                        href="dashboard-admin.php?adminid=<?php echo $adminid ?>">
                        <li class="navi <?php if ($active == "Dashboard") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-house fa-fw"></i>
                            <span>Dashboard</span>
                        </li>
                    </a>

                    <a href="#" class="show">
                        <li class="navi <?php if ($active == "Bookings") {
                                            echo "active";
                                        }
                                        if ($on == "on") {
                                            echo "active-drop";
                                        }
                                        ?>">
                            <i class="fa-solid fa-book fa-fw"></i>
                            <span>Bookings</span>
                        </li>
                    </a>

                    <ul class="drop one <?php if ($on == "on") {
                                            echo "on";
                                        } ?>">
                        <a href="pending-bookings.php?adminid=<?php echo $adminid ?>">
                            <li class="navi <?php if ($active == "Pending Bookings") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-hourglass-half fa-fw"></i>
                                <span>Pending Bookings </span>
                                <span class=" w-700" id="pending_count"><?php echo $count_pending ?></span>
                            </li>
                        </a>

                        <a href="ticketed.php?adminid=<?php echo $adminid ?>">
                            <li class="navi <?php if ($active == "Ticket") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-ticket fa-fw"></i>
                                <span>Ticketed</span>
                                <span class=" w-700" id="ticket_count"><?php echo $count_ticket ?></span>
                            </li>
                        </a>

                        <a href="customize.php?adminid=<?php echo $adminid ?>">
                            <li class="navi <?php if ($active == "Customize") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-briefcase fa-fw"></i>
                                <span>Customize</span>
                                <span class=" w-700" id="customize_count"><?php echo $count_customize ?></span>
                            </li>
                        </a>

                        <a href="educational.php?adminid=<?php echo $adminid ?>">
                            <li class="navi <?php if ($active == "Educational") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-user-group fa-fw"></i>
                                <span>Educational Tour</span>
                                <span class=" w-700" id="educ_count"><?php echo $count_educ ?></span>
                            </li>
                        </a>

                        <a href="tour.php?adminid=<?php echo $adminid ?>">
                            <li class="navi <?php if ($active == "Tour Package") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-box-archive fa-fw"></i>
                                <span>Tour Package</span>
                                <span class=" w-700"><?php echo $count_tour ?></span>
                            </li>
                        </a>
                    </ul>

                    <a href="#" class="pay">
                        <li class="navi <?php if ($active == "Payments") {
                                            echo "active";
                                        }
                                        if ($on == "active-pay") {
                                            echo "active-drop";
                                        }
                                        ?>">
                            <i class=" fa-solid fa-clipboard fa-fw"></i>
                            <span>Payments</span>
                        </li>
                    </a>

                    <ul class="drop two <?php if ($on == "active-pay") {
                                            echo "on";
                                        } ?>">
                        <a href="full-payment.php?adminid=<?php echo $adminid ?>">
                            <li class="navi <?php if ($active == "Full") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-credit-card fa-fw"></i>
                                <span>Full Payments </span>
                                <span class=" w-700"><?php echo $full_count ?></span>
                            </li>
                        </a>

                        <a href="installments.php?adminid=<?php echo $adminid ?>">
                            <li class="navi <?php if ($active == "Installments") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-credit-card fa-fw"></i>
                                <span>Installments</span>
                                <span class=" w-700"><?php echo $installment_count ?></span>
                            </li>
                        </a>

                        <a href="gcash.php?adminid=<?php echo $adminid ?>">
                            <li class="navi <?php if ($active == "Gcash") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-receipt fa-fw"></i>
                                <span>Gcash Receipt</span>
                                <span></span>
                            </li>
                        </a>
                    </ul>

                    <a href="quotation-admin.php?adminid=<?php echo $adminid ?>">
                        <li class="navi <?php if ($active == "Quotations") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-envelope fa-fw"></i>
                            <span>Quotations</span>
                        </li>
                    </a>

                    <a href="tour-package.php?adminid=<?php echo $adminid ?>">
                        <li class="navi <?php if ($active == "Tour") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-box-archive fa-fw"></i> <span>Tour Package</span>
                        </li>
                    </a>

                    <a href="ratings-admin.php?adminid=<?php echo $adminid ?>">
                        <li class="navi <?php if ($active == "Ratings") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-star fa-fw"></i> <span>Ratings</span>
                        </li>
                    </a>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('.show').click(function() {
            $('.drop.one').slideToggle("fast", "linear");
        });
        $('.pay').click(function() {
            $('.drop.two').slideToggle("fast", "linear");
        });
    })
</script>