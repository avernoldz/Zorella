<?php
session_start();
session_regenerate_id();

if (!$_SESSION['userid']) {
    header("Location:index.php?login-first");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../../inc/cdn.php" ?>
    <?php include "../../CSS/links.php" ?>
    <title>Tour Package</title>
    <style>
        .main .col.card .row {
            align-items: center;
        }

        .wd-100 {
            width: 100%;
        }

        .itinerary {
            font-size: 14px;
        }

        .main .row .itinerary h1 {
            font-size: 24px;
        }

        .main .row img {
            border-radius: 12px;
        }

        .main .book-btn {
            background: var(--maindark);
            color: white;
            border: 1px solid var(--maindark);
            float: right;
            font-family: Analouge;
            padding: 8px 20px;
            font-size: 16px;
        }

        .main .book-btn:hover {
            background: transparent;
            border: 1px solid var(--maindark);
            color: var(--maindark);
        }
    </style>
</head>

<body>

    <?php
    $userid = $_GET['userid'];
    $packageid = $_GET['packageid'];
    $active = "Tour";
    include "../../inc/Include.php";
    include "side-bar.php";
    include "header.php";

    $query = "SELECT * FROM tourpackage WHERE tourid = '$packageid'";
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($res);
    $dates = json_decode($row['tsdates']);
    $dates2 = json_decode($row['tedates']);

    ?>
    <div class="main">
        <input type="hidden" value="<?php echo "$userid"; ?>"
            name="userid">
        <div class="row wrap" id="tour">
            <div class="col body">
                <div class="row">
                    <div class="col-12 body" style="background-color: var(--main-300);color: white;">
                        <h4> Tour Packages</h4>
                    </div>
                </div>
                <div class="row body">
                    <div class="row mt-2 mb-3 wd-100">
                        <div class="col pl-4 pr-4  itinerary">
                            <h1 class="w-700 mb-2"><?php echo $row['title'] ?></h1>
                            <p class="mb-2"><?php echo $row['description'] ?></p>
                            <h6 class="price w-700" style="color:var(--maindark);font-size: 20px;">&#x20B1; <?php echo number_format($row['price'], 0) ?> / pax</h6>
                            <hr>
                            <h2 class="w-700 mb-2" style="font-size:20px;">ITINERARY</h2>
                            <?php echo $row['itinerary'] ?>

                        </div>
                        <div class="col-3 mb-3">
                            <button type="button" data-target="#book" data-toggle="modal" class="btn btn-primary book-btn">Book Now <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <form action="inc/tour-package.php" style="width: 100%;" class="needs-validation" method="POST" novalidate>
                    <div class="modal fade" id="book" tabindex="-1" role="dialog" aria-labelledby="requestTitle"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="request">Book Tour</h5>
                                </div>
                                <div class="modal-body mb-4 mt-3">
                                    <div class="row w-100">
                                        <div class="col-8">
                                            <label for="branch">Branch</label><label for="" class="required">*</label>
                                            <select name="branch" class="form-control" id="branch" required>
                                                <option selected>Calumpang</option>
                                                <option>Calamba</option>
                                            </select>
                                        </div>
                                        <div class="w-100"></div>
                                        <div class="col-6 mt-3">
                                            <label for="pax">Pax</label><label for="" class="required">*</label>
                                            <input type="number" id="pax" placeholder="1" name="pax"
                                                class="form-control" required>
                                            <div class="invalid-feedback">
                                                Please input for how many pax
                                            </div>
                                        </div>
                                        <div class="col-6 mt-3">
                                            <label for="pax">Date</label><label for="" class="required">*</label>
                                            <select name="date" class="form-control">
                                                <option disabled selected>Select Date</option>
                                                <?php foreach ($dates as $index => $date):
                                                    // Format both dates
                                                    $formattedDate1 = date('M d, Y', strtotime($date));
                                                    $formattedDate2 = date('M d, Y', strtotime($dates2[$index])); // Use the same index for the second array
                                                ?>
                                                    <option value="<?php echo htmlspecialchars($date); ?>">
                                                        <?php echo htmlspecialchars($formattedDate1) . ' - ' . htmlspecialchars($formattedDate2); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select date
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden"
                                        value="<?php echo "$row[title]"; ?>"
                                        name="title">
                                    <input type="hidden"
                                        value="<?php echo "$userid"; ?>"
                                        name="userid">
                                    <input type="hidden"
                                        value="<?php echo "$packageid"; ?>"
                                        name="tourid">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">Close</button>
                                    <button id="next" class="btn btn-primary btn-next" type="button" name="ticketd">Submit</button>
                                    <button style="display:none" type="submit" name="tour-package" id="submit">Next</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="js/validation.js"></script>
</body>

</html>