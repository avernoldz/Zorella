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
    <title>Quotation</title>
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

        .main .bkq .case {
            min-height: 88vh;
            height: 88vh;
            max-height: 88vh;
        }

        .main .row.frame-quote {
            max-height: 100%;
            overflow-y: auto;
        }
    </style>
</head>

<body>

    <?php
    $adminid = $_GET['adminid'];
$active = "Quotations";
include "../Connections/Include.php";
include "header-admin.php";
include "side-bar-admin.php";

$quotation = "SELECT *, quotation.email as email2, DATE(date) AS date FROM quotation INNER JOIN user ON quotation.userid = user.userid ORDER BY quotation.quotationid DESC";
$quotationResults = mysqli_query($conn, $quotation);


?>
    <div class="main">
        <div class="row bkq">
            <div class="col-12 case">
                <h1><i class="fa-solid fa-envelope fa-fw"></i>&nbsp;&nbsp;Quotations</h1>
                <hr>
                <div class="row frame-quote w-100">
                    <?php
                    if(mysqli_num_rows($quotationResults) > 0) {
                        while($quotationRows = mysqli_fetch_array($quotationResults)) {
                            $date = date_create("$quotationRows[date]");
                            $dateFormat = date_format($date, "F d, Y");
                            ?>

                    <a href="view-quotation.php?adminid=<?php echo "$adminid&qoute=$quotationRows[quotationid]"?>"
                        class="populate-quote w-100">
                        <div class="row populate-quote">
                            <div class="col-3">
                                <small><label for="" class="w-700">
                                        <?php echo "$quotationRows[firstname] $quotationRows[lastname]"?><br>
                                    </label>
                                </small>
                            </div>
                            <div class="col-9 ellip">
                                <small>
                                    <span><?php echo "$quotationRows[title] - From $quotationRows[origin] to $quotationRows[destination], $quotationRows[pax] pax for $quotationRows[days], Travel Date: $dateFormat"?></span>
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
</body>

</html>