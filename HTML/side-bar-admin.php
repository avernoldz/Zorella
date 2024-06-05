<div class="side-bar">
    <div class="wrapper">
        <div class="row">
            <div class="col head">
                <a
                    href="dashboard-admin.php?adminid=<?php echo $adminid?>">
                    <div class="title">

                        <div class="row">
                            <div class="col">
                                <img src="../Assets/icon.png" alt="lspu-logo" width="50px">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h1>ZORELLA TRAVEL AND TOURS</h1>
                            </div>
                        </div>


                    </div>
                </a>
                <ul class="mt-5">
                    <a
                        href="dashboard-admin.php?adminid=<?php echo $adminid?>">
                        <li class="navi <?php if($active == "Dashboard") {
                            echo "active";
                        }?>">
                            <i class="fa-solid fa-house fa-fw"></i>
                            <span>Dashboard</span>
                        </li>
                    </a>

                    <a href="#" class="show">
                        <li class="navi <?php if($active == "Bookings") {
                            echo "active";
                        }?>">
                            <i class="fa-solid fa-book fa-fw"></i>
                            <span>Bookings</span>
                        </li>
                    </a>

                    <ul class="drop one">
                        <a href="#">
                            <li class="navi <?php if($active == "All") {
                                echo "active";
                            }?>">
                                <i class="fa-solid fa-ticket fa-fw"></i>
                                <span>All Transactions </span>
                                <span class="number w-700">1</span>
                            </li>
                        </a>

                        <a href="#">
                            <li class="navi <?php if($active == "Ticket") {
                                echo "active";
                            }?>">
                                <i class="fa-solid fa-clipboard fa-fw"></i>
                                <span>Ticketed Transactions</span>
                                <span class="number w-700">10</span>
                            </li>
                        </a>

                        <a href="#">
                            <li class="navi <?php if($active == "Cancelled") {
                                echo "active";
                            }?>">
                                <i class="fa-solid fa-trash fa-fw"></i>
                                <span>Cancelled Transactions</span>
                                <span class="number w-700">3</span>
                            </li>
                        </a>

                        <a href="#">
                            <li class="navi <?php if($active == "Refunded") {
                                echo "active";
                            }?>">
                                <i class="fa-solid fa-share fa-fw"></i>
                                <span>Refunded Transactions</span>
                                <span class="number w-700">0</span>
                            </li>
                        </a>
                    </ul>

                    <a href="#" class="pay">
                        <li class="navi <?php if($active == "Payments") {
                            echo "active";
                        }?>">
                            <i class="fa-solid fa-clipboard fa-fw"></i>
                            <span>Payments</span>
                        </li>
                    </a>

                    <ul class="drop two">
                        <a href="#">
                            <li class="navi <?php if($active == "Full") {
                                echo "active";
                            }?>">
                                <i class="fa-solid fa-credit-card fa-fw"></i>
                                <span>Full Payments </span>
                                <span class="number w-700">1</span>
                            </li>
                        </a>

                        <a href="#">
                            <li class="navi <?php if($active == "Installments") {
                                echo "active";
                            }?>">
                                <i class="fa-solid fa-credit-card fa-fw"></i>
                                <span>Installments</span>
                                <span class="number w-700">10</span>
                            </li>
                        </a>
                    </ul>


                    <a
                        href="quotation-admin.php?adminid=<?php echo $adminid?>">
                        <li class="navi <?php if($active == "Quotations") {
                            echo "active";
                        }?>">
                            <i class="fa-solid fa-envelope fa-fw"></i>
                            <span>Quotations</span>
                        </li>
                    </a>
                    <!-- <a href="#">
                        <li class="navi <?php if($active == "VISA") {
                            echo "active";
                        }?>">
                    <i class="fa-solid fa-passport fa-fw"></i> <span>VISA</span>
                    </li>
                    </a> -->

                    <a href="#">
                        <li class="navi <?php if($active == "Ratings") {
                            echo "active";
                        }?>">
                            <i class="fa-solid fa-star fa-fw"></i> <span>Ratings</span>
                        </li>
                    </a>
                    </li></a>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('.show').click(function() {
            $('.drop.one').slideToggle("slow", "swing");
        });
        $('.pay').click(function() {
            $('.drop.two').slideToggle("slow", "swing");
        });
    })
</script>