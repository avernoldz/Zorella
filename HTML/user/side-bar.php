<?php
$query = "SELECT * FROM user WHERE userid = '$userid'";
$res = mysqli_query($conn, $query);
$rows = mysqli_fetch_array($res);
?>

<div class="side-bar">
    <div class="wrapper">
        <div class="row">
            <div class="col head">
                <a href="dashboard.php?userid=<?php echo $userid ?>">
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
                        href="dashboard.php?userid=<?php echo $userid ?>">
                        <li class="navi <?php if ($active == "dashboard") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-house fa-fw"></i>
                            <span>Dashboard</span>
                        </li>
                    </a>
                    <a
                        href="quotation.php?userid=<?php echo $userid ?>">
                        <li class="navi <?php if ($active == "Quotation") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-clipboard fa-fw"></i> <span>Quotation</span>
                        </li>
                    </a>
                    <a
                        href="ticketed.php?userid=<?php echo $userid ?>">
                        <li class="navi <?php if ($active == "Ticketed") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-ticket fa-fw"></i>
                            <span>Ticketed</span>
                        </li>
                    </a>
                    <a
                        href="customize.php?userid=<?php echo $userid ?>">
                        <li class="navi <?php if ($active == "Customize") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-briefcase fa-fw"></i>
                            <span>Customize</span>
                        </li>
                    </a>
                    <a
                        href="team-building.php?userid=<?php echo $userid ?>">
                        <li class="navi <?php if ($active == "Educational/Team Building") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-user-group fa-fw"></i> <span>Educational/Team Building</span>
                        </li>
                    </a>

                    <a
                        href="tour-package.php?userid=<?php echo $userid ?>">
                        <li class="navi <?php if ($active == "Tour") {
                                            echo "active";
                                        } ?>">
                            <i class="fa-solid fa-box-archive fa-fw"></i> <span>Tour Package</span>
                        </li>
                    </a>
                    </li></a>

                    <a
                        href="feedback.php?userid=<?php echo $userid ?>">
                        <li class="navi <?php if ($active == "Feedback") {
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


<?php include "settings.php"; ?>