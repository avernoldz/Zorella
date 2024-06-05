<?php
session_start();
session_regenerate_id();

if(!$_SESSION['adminid']) {
    header("Location:index.php?Login-first");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../Connections/cdn.php"?>
    <?php include "../CSS/links.php"?>
    <title>Dashboard</title>
    <style>
        /* width */
        ::-webkit-scrollbar {
            width: 5px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .main .col.card .row {
            align-items: center;
        }

        .row.populate {
            background: var(--sub);
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 8px;
            max-height: 70px;
            min-height: 70px;
        }

        .row.populate .col-9 p,
        .col-8.ellip {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 100%;
        }

        .row.populate:nth-child(even),
        a.populate-quote:nth-child(even) .row.populate-quote {
            background-color: #ffb3a5;
        }

        .row.populate:hover {
            opacity: 0.95;
            cursor: pointer;
        }

        .row.populate-quote {
            background: var(--sub);
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 8px;
        }

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
    </style>
</head>

<body>

    <?php
    $adminid = $_GET['adminid'];
$active = "Dashboard";
include "../Connections/Include.php";
include "header-admin.php";
include "side-bar-admin.php";

$ratings = "SELECT * FROM ratings INNER JOIN user ON ratings.userid = user.userid ORDER BY ratings.ratingsid DESC";
$ratingsResults = mysqli_query($conn, $ratings);


$quotation = "SELECT *, quotation.email as email2 FROM quotation INNER JOIN user ON quotation.userid = user.userid ORDER BY quotation.quotationid DESC";
$quotationResults = mysqli_query($conn, $quotation);

?>
    <div class="main">
        <div class="row">
            <div class="col head">
                <h1><i class="fa-solid fa-house fa-fw"></i>&nbsp;&nbsp;Dashboard</h1>
            </div>
        </div>
        <div class="row bkq">
            <div class="col-6 case">
                <h1><i class="fa-solid fa-book fa-fw"></i>&nbsp;&nbsp;Bookings</h1>
                <hr>
            </div>
            <div class="col case ml-4">
                <h1><i class="fa-solid fa-envelope fa-fw"></i>&nbsp;&nbsp;Quotations</h1>
                <hr>
                <div class="row frame-quote w-100">
                    <?php
                    if(mysqli_num_rows($quotationResults) > 0) {
                        while($quotationRows = mysqli_fetch_array($quotationResults)) {
                            ?>

                    <a href="quotation-admin.php?adminid=<?php echo $adminid?>"
                        class="populate-quote w-100">
                        <div class="row populate-quote">
                            <div class="col-4">
                                <small><label for="" class="w-700">
                                        <?php echo "$quotationRows[firstname] $quotationRows[lastname]"?><br>
                                    </label>
                                </small>
                            </div>
                            <div class="col-8 ellip">
                                <small>
                                    <span><?php echo "$quotationRows[title] - From $quotationRows[origin] to $quotationRows[destination], $quotationRows[pax] pax for $quotationRows[days]"?></span>
                                </small>
                            </div>
                        </div>
                    </a>
                    <?php
                        }
                    }?>
                </div>
            </div>
        </div>

        <div class="row ">
            <div class="col-7 case rv">
                <h1><i class="fa-solid fa-star fa-fw"></i>&nbsp;&nbsp;Ratings</h1>
                <hr>
                <div class="row frame ratings">
                    <?php
                    if(mysqli_num_rows($ratingsResults) > 0) {
                        while($ratingsRows = mysqli_fetch_array($ratingsResults)) {
                            ?>
                    <div class="row populate w-100"
                        id="<?php echo "$ratingsRows[ratingsid]";?>">
                        <div class="col-12">
                            <small style="display:flex; justify-content: space-between;">
                                <span>From:
                                    <label for="">
                                        <?php echo "$ratingsRows[firstname] $ratingsRows[lastname]"?><br>
                                    </label>
                                </span>
                                <!-- <span for="" class="w-500">Date Created:
                                    <label
                                        for=""><?php echo "$ratingsRows[dateCreated]"?></label></span>
                                -->
                            </small>
                            <div class="row">
                                <div class="col-9">
                                    <p><?php echo "$ratingsRows[feedback]"?>
                                    </p>
                                </div>
                                <div class="col-3">
                                    <?php
                                $rate = intval($ratingsRows['ratings']);
                            $blackRate = 5 - $rate;

                            for($rate; $rate > 0; $rate--) {
                                echo "<i class='fa-solid fa-star fa-fw' style='color:var(--danger)'></i>";
                            }

                            for($blackRate; $blackRate > 0; $blackRate--) {
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

            <div class="col case ml-4 rv">
                <h1><i class="fa-solid fa-clipboard fa-fw"></i>&nbsp;&nbsp;Payments</h1>
                <hr>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            var old = parseInt($('.row.ratings > div').attr("id"));
            setInterval(() => {

                var current = old + 1;
                $('.row.ratings').load(' .row.ratings');
                $('.row.frame-quote').load(" .row.frame-quote");

                // if ($('.row.frame.ratings').find('#8').length) {
                //     old = current;
                //     $('#8').addClass("new");
                // }

                // console.log(current);
            }, 5000);

        })
    </script>
</body>

</html>