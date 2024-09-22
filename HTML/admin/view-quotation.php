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
    <title>Quotation</title>
</head>

<body>

    <?php

    $qoute = $_GET['qoute'];
    $adminid = $_GET['adminid'];
    $active = "Quotations";
    $on = "off";
    include "../../inc/Include.php";
    include "header-admin.php";
    include "side-bar-admin.php";

    $quotation = "SELECT *, quotation.email as email2, DATE(date) AS date FROM quotation INNER JOIN user ON quotation.userid = user.userid WHERE quotation.quotationid = '$qoute'";
    $quotationResults = mysqli_query($conn, $quotation);
    $quotationRows = mysqli_fetch_assoc($quotationResults);

    $date = date_create("$quotationRows[date]");
    $dateFormat = date_format($date, "F d, Y");

    ?>
    <div class="main">
        <div class="row bkq ">
            <div class="col-8 case mr-4">
                <h1><i
                        class="fa-solid fa-envelope fa-fw"></i>&nbsp;&nbsp;<?php echo "$quotationRows[title]" ?>
                </h1>
                <hr>
                <form action="mail.php" method="POST" id="form">
                    <div class="row frame-quote w-100">
                        <div class="col-12">
                            <p class="text-center"><label>ZORELLA TRAVEL AND TOURS</label> <br>
                                CALUMPANG LILIW LAGUNA <br>
                                Laguna, Philippines 4004 <br>
                                +639237374423 <br>
                                zorellatravelandtours@gmail.com
                            </p>
                            <p>
                                Clients Name:
                                <label><?php echo "$quotationRows[firstname] $quotationRows[lastname]" ?></label><br>
                                Quotation Title:
                                <label><?php echo "$quotationRows[title]" ?></label><br>
                                Date of Travel:
                                <label><?php echo "$dateFormat" ?></label><br>
                                Number of Pax:
                                <label><?php echo "$quotationRows[pax]" ?></label><br>
                            </p>
                            <hr class="mb-3 mt-3">
                            <p>Dear Valued Client,</p>
                            <br>
                            <p>Please see below quotation for your perusal. <br>
                                The rate below is subject to change without prior notice.
                            </p>

                            <div style="border:1px solid var(--font);padding:24px;text-align:center;" class="w-700">
                                P
                                <input type="number" name="total" required placeholder="&nbsp;9,999.99" class="total">
                                /PAX <br>
                                ALL IN PACKAGE
                            </div>
                            <p class="w-700">HOTEL: <label class="ml-5">3* or Similar Class</label></p>
                            <p class="mt-3 w-700">
                                INCLUSIONS:
                            </p>
                            <textarea name="inclusions" rows="3" class="clusions w-100"
                                placeholder="Inclusions of the trip goes here..."></textarea>
                            <p class="mt-3 w-700">
                                EXCLUSIONS:
                            </p>
                            <textarea name="exclusions" rows="3" class="clusions w-100"
                                placeholder="Exclusions of the trip goes here..."></textarea>
                            <p class="mt-3 w-700">
                                Itinerary:
                            </p>
                            <textarea name="itinerary" rows="3" class="clusions w-100"
                                placeholder="Itinerary of the trip goes here..."></textarea>
                            <p class="mt-3 w-700">REMARKS
                            </p>
                            <textarea name="remarks" rows="3" class="clusions w-100" placeholder="Remarks goes here..."
                                id="caption"></textarea>
                            <p style="text-align:right;">Prepared by: <label>Janine Rabajante</label><br>
                                Marketing Executive
                            </p>
                            <br>
                            <p style="text-align:right;">Conforme: <label>Ms. Bernadette Cagampan</label><br>
                                Operations Manager
                            </p>
                        </div>
                    </div>
                    <div class="row w-100">
                        <div class="col-12">
                            <button class="btn btn-primary mt-3 pl-5 pr-5" style="float:right;"
                                name="submit">Send</button>
                        </div>

                        <input type="hidden" name="email"
                            value="<?php echo "$quotationRows[email2]" ?>">
                        <input type="hidden" name="id"
                            value="<?php echo "$qoute" ?>">
                        <input type="hidden" name="adminid"
                            value="<?php echo "$adminid" ?>">
                        <input type="hidden" name="name"
                            value="<?php echo "$quotationRows[firstname] $quotationRows[lastname]" ?>">
                    </div>
                </form>
            </div>
            <div class="col case" style="height: fit-content;">
                <div class="row w-100">
                    <div class="col-12">
                        <table class="table table-borderless">
                            <tr>
                                <td>
                                    Origin
                                </td>
                                <td class="w-700">
                                    <?php echo "$quotationRows[origin]" ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Destination
                                </td>
                                <td class="w-700">
                                    <?php echo "$quotationRows[destination]" ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Travel Date
                                </td>
                                <td class="w-700">
                                    <?php echo "$dateFormat" ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    PAX
                                </td>
                                <td class="w-700">
                                    <?php echo "$quotationRows[pax]" ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Duration
                                </td>
                                <td class="w-700">
                                    <?php echo "$quotationRows[days]" ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Email
                                </td>
                                <td class="w-700">
                                    <?php echo "$quotationRows[email2]" ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/app.js"></script>
    <script>

    </script>
</body>

</html>