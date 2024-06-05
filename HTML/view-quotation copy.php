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
    <title>Quotation</title>
    <style>
        body {
            font-family: "Inter", sans-serif !important;
            color: var(--font);
            padding: 48px;
        }

        .row.populate-quote {
            background: var(--sub);
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 8px;
        }

        .col.case .table>tbody>tr>td {
            border: none;
            border-bottom: 1px solid #ddd;
        }

        label {
            margin: 0;
        }

        .main .row.frame-quote .total {
            border: none;
            border-bottom: 1px solid #ddd;
            width: 11%;
        }

        .main .row.frame-quote .total:focus,
        .main .row.frame-quote .clusions:focus {
            outline: none;
        }

        .main .row.frame-quote .clusions {
            padding: 8px;
            border: none;
            border-left: 1px solid #ddd;
        }
    </style>
</head>

<body>

    <?php

$qoute = $_POST['qoute'];
$adminid = $_POST['adminid'];
$active = "Quotations";
include "../Connections/Include.php";

$quotation = "SELECT *, quotation.email as email2, DATE(date) AS date FROM quotation INNER JOIN user ON quotation.userid = user.userid WHERE quotation.quotationid = '$qoute'";
$quotationResults = mysqli_query($conn, $quotation);
$quotationRows = mysqli_fetch_assoc($quotationResults);

$date = date_create("$quotationRows[date]");
$dateFormat = date_format($date, "F d, Y");

?>
    <div class="main">
        <div class="row bkq ">
            <div class="col-12 case mr-4">
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
                            <label><?php echo "$quotationRows[firstname] $quotationRows[lastname]"?></label><br>
                            Quotation Title:
                            <label><?php echo "$quotationRows[title]"?></label><br>
                            Date of Travel:
                            <label><?php echo "$dateFormat"?></label><br>
                            Number of Pax:
                            <label><?php echo "$quotationRows[pax]"?></label><br>
                        </p>
                        <hr class="mb-3 mt-3">
                        <p>Dear Valued Client,</p>
                        <br>
                        <p>Please see below quotation for your perusal. <br>
                            The rate below is subject to change without prior notice.
                        </p>

                        <div style="border:1px solid var(--font);padding:24px;text-align:center;" class="w-700">
                            P
                            <?php echo "$_POST[total]"?>
                            /PAX <br>
                            ALL IN PACKAGE
                        </div>
                        <p class="w-700">HOTEL: <label class="ml-5">3* or Similar Class</label></p>
                        <p class="mt-3 w-700">
                            INCLUSIONS:
                        </p>
                        <textarea name="inclusions" rows="3" class="clusions w-100"
                            placeholder="Inclusions of the trip goes here...">
                                <?php echo "$_POST[inclusions]"?>
                            </textarea>
                        <p class="mt-3 w-700">
                            EXCLUSIONS:
                        </p>
                        <!-- <div contenteditable="true"></div> -->
                        <textarea name="exclusions" rows="3" class="clusions w-100"
                            placeholder="Exclusions of the trip goes here...">
                                <?php echo "$_POST[exclusions]"?>
                            </textarea>
                        <p class="mt-3 w-700">
                            Itinerary:
                        </p>
                        <textarea name="itinerary" rows="3" class="clusions w-100"
                            placeholder="Itinerary of the trip goes here...">
                                <?php echo "$_POST[itinerary]"?>
                            </textarea>
                        <p class="mt-3 w-700">REMARKS
                        </p>
                        <textarea name="remarks" rows="3" class="clusions w-100" placeholder="Remarks goes here...">
                                <?php echo "$_POST[remarks]"?>
                            </textarea>
                        <p style="text-align:right;">Prepared by: <label>Janine Rabajante</label><br>
                            Marketing Executive
                        </p>
                        <br>
                        <p style="text-align:right;">Conforme: <label>Ms. Bernadette Cagampan</label><br>
                            Operations Manager
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>