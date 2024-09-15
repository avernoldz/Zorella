<?php
$count = "SELECT
    COUNT(b.booking_id) AS booking_count
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
    b.status = 'pending'";

$rescount = mysqli_query($conn, $count);

$countrow = mysqli_fetch_array($rescount)

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
                                <span class="number w-700"><?php echo $countrow['booking_count'] ?></span>
                            </li>
                        </a>

                        <a href="#">
                            <li class="navi <?php if ($active == "Ticket") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-ticket fa-fw"></i>
                                <span>Ticketed</span>
                                <span class="number w-700">10</span>
                            </li>
                        </a>

                        <a href="#">
                            <li class="navi <?php if ($active == "Customize") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-briefcase fa-fw"></i>
                                <span>Customize</span>
                                <span class="number w-700">3</span>
                            </li>
                        </a>

                        <a href="#">
                            <li class="navi <?php if ($active == "Educational Tour") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-user-group fa-fw"></i>
                                <span>Educational Tour</span>
                                <span class="number w-700">0</span>
                            </li>
                        </a>

                        <a href="#">
                            <li class="navi <?php if ($active == "Tour Package") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-box-archive fa-fw"></i>
                                <span>Tour Package</span>
                                <span class="number w-700">0</span>
                            </li>
                        </a>
                    </ul>

                    <a href="#" class="pay">
                        <li class="navi <?php if ($active == "Payments") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-clipboard fa-fw"></i>
                            <span>Payments</span>
                        </li>
                    </a>

                    <ul class="drop two">
                        <a href="#">
                            <li class="navi <?php if ($active == "Full") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-credit-card fa-fw"></i>
                                <span>Full Payments </span>
                                <span class="number w-700">1</span>
                            </li>
                        </a>

                        <a href="#">
                            <li class="navi <?php if ($active == "Installments") {
                                                echo "active";
                                            } ?>">
                                <i class="fa-solid fa-credit-card fa-fw"></i>
                                <span>Installments</span>
                                <span class="number w-700">10</span>
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
                        <li class="navi <?php if ($active == "Tour Package") {
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