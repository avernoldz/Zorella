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
    <title>Ratings</title>
    </style>
</head>

<body>

    <?php
    $adminid = $_GET['adminid'];
    $active = "Ratings";
    $on = "off";
    include "../../inc/Include.php";
    include "header-admin.php";
    include "side-bar-admin.php";

    $ratings = "SELECT * FROM ratings INNER JOIN user ON ratings.userid = user.userid ORDER BY ratings.ratingsid DESC";
    $ratingsResults = mysqli_query($conn, $ratings);


    ?>
    <div class="main">
        <div class="row bkq">
            <div class="col-12 case">
                <h1><i class="fa-solid fa-envelope fa-fw"></i>&nbsp;&nbsp;Ratings</h1>
                <hr>
                <div class="row frame-quotee w-100">
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
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            var old = parseInt($('.row.ratings > div').attr("id"));
            setInterval(() => {

                var current = old + 1;
                $('.row.frame-quote').load(" .row.frame-quote");
            }, 5000);

        })
    </script>
    <script src="js/app.js"></script>
</body>

</html>