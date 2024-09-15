<?php
session_start();
session_regenerate_id();

if (!$_SESSION['adminid']) {
    header("Location:../index.php?Login-first");
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
    <title>Bookings</title>
</head>

<body>

    <?php

    $ticketid = $_GET['bookingid'];
    $bookingtype = $_GET['bookingtype'];
    $adminid = $_GET['adminid'];
    $bookid = $_GET['bookid'];
    $active = "Pending Bookings";
    $on = "on";
    include "../../inc/Include.php";
    include "header-admin.php";
    include "side-bar-admin.php";

    $quotation = "SELECT  
                    user.firstname AS user_firstname, 
                    user.lastname AS user_lastname, 
                    personalinfo.*, 
                    ticket.*,
                    payment.*
                    FROM user 
                  INNER JOIN ticket ON ticket.userid = user.userid 
                  INNER JOIN personalinfo ON personalinfo.ticketid = ticket.ticketid 
                  INNER JOIN  payment ON payment.booking_id = ticket.ticketid 
                  WHERE ticket.ticketid = '$ticketid'";
    $quotationResults = mysqli_query($conn, $quotation);
    $quotationRows = mysqli_fetch_all($quotationResults, MYSQLI_ASSOC);

    $firstRow = null;
    $total = null;

    if (!empty($quotationRows)) {
        // Get the first row
        $firstRow = $quotationRows[0];

        $passengerTypes = ['adult', 'child', 'infant', 'senior'];

        // Initialize total passengers
        $totalPassengers = 0;

        // Loop through each passenger type
        foreach ($passengerTypes as $type) {
            // Add the value to the total passengers, defaulting to 0 if not set
            $total += isset($firstRow[$type]) ? (int)$firstRow[$type] : 0;
        }

        $dateFormats = [
            'departure' => 'M d, Y',
            'arrival' => 'M d, Y',
            'dateCreated' => 'M d, Y : h:i A'
        ];

        foreach ($dateFormats as $key => $format) {
            if (isset($firstRow[$key]) && $firstRow[$key] !== '0000-00-00') {
                $date = new DateTime($firstRow[$key]);
                $firstRow[$key] = $date->format($format);
            } else {
                // Set to empty string if date is '0000-00-00' or not set
                $firstRow[$key] = '';
            }
        }
    }

    $frow = $quotationRows[0] ?? null;

    ?>
    <div class="main">
        <div class="row bkq ">
            <div class="case row w-100 mb-4">
                <div class="col flex">
                    <h1 class="w-700"><i class="fa-solid fa-envelope fa-fw"></i>&nbsp;&nbsp;<?php echo "$frow[user_firstname] $frow[user_lastname]" ?></h1>
                </div>
                <div class="col flex justify-content-end accept">
                    <button type="button" data-target="#accept" data-toggle="modal" class="btn btn-success"><i class="fa-solid fa-check fa-fw "></i>Accept</button>
                    <button type="button" class="btn btn-secondary"><i class="fa-solid fa-xmark fa-fw text-danger"></i> Reject</button>
                </div>
            </div>
            <div class="card col-7 mr-4 booking">
                <div class="card-header flex">
                    <h2>Booking Information</h2>
                    <h2><?php echo $bookingtype ?></h2>
                </div>
                <div class="card-body">
                    <div class="card-info">
                        <div class="info-item">
                            <label>Branch</label>
                            <p><?php echo $firstRow['branch'] ?></p>
                        </div>
                        <div class="info-item">
                            <label>Flight</label>
                            <p><?php echo $firstRow['flighttype'] ?></p>
                        </div>
                        <div class="info-item">
                            <label>Ticket</label>
                            <p><?php echo $firstRow['tickettype'] ?></p>
                        </div>
                        <div class="info-item">
                            <label>Origin</label>
                            <p><?php echo $firstRow['origin'] ?></p>
                        </div>
                        <div class="info-item">
                            <label>Destination</label>
                            <p><?php echo $firstRow['destination'] ?></p>
                        </div>
                        <div class="info-item">
                            <label>Class</label>
                            <p><?php echo $firstRow['classtype'] ?></p>
                        </div>
                        <div class="info-item">
                            <label>Departure</label>
                            <p><?php echo $firstRow['departure'] ?></p>
                        </div>
                        <?php if (!empty($firstRow['arrival'])): ?>
                            <div class="info-item">
                                <label>Arrival</label>
                                <p><?php echo htmlspecialchars($firstRow['arrival']); ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <label>Direct</label>
                            <p><?php echo $firstRow['directflight'] ?></p>
                        </div>
                        <div class="info-item">
                            <label>Passengers</label>
                            <p><?php echo $total ?></p>
                        </div>
                        <div class="info-item">
                            <label>Adult</label>
                            <p><?php echo $firstRow['adult'] ?></p>
                        </div>
                        <?php if (!empty($firstRow['child'])): ?>
                            <div class="info-item">
                                <label>Child</label>
                                <p><?php echo htmlspecialchars($firstRow['child']); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($firstRow['infant'])): ?>
                            <div class="info-item">
                                <label>Infant</label>
                                <p><?php echo htmlspecialchars($firstRow['infant']); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($firstRow['senior'])): ?>
                            <div class="info-item">
                                <label>Senior</label>
                                <p><?php echo htmlspecialchars($firstRow['senior']); ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <label>Airline</label>
                            <p><?php echo $firstRow['airline'] ?></p>
                        </div>
                        <div class="info-item">
                            <label>Status</label>
                            <p><?php echo $firstRow['status'] ?></p>
                        </div>
                        <div class="info-item">
                            <label>Date Created</label>
                            <p><?php echo $firstRow['dateCreated'] ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col" style="height: fit-content;">
                <div class="row mb-3 w-100">
                    <div class="card">
                        <div class="card-header">
                            <h2>Payment Information</h2>
                        </div>
                        <div class="card-body">
                            <div class="card-info">
                                <div class="info-item">
                                    <label>Payment Type</label>
                                    <p><?php echo $frow['payment_type'] ?></p>
                                </div>
                                <?php if (!empty($frow['installment_type'])): ?>
                                    <div class="info-item">
                                        <label>Installment Type</label>
                                        <p><?php echo htmlspecialchars($frow['installment_type']); ?></p>
                                    </div>
                                <?php endif; ?>
                                <div class="info-item">
                                    <label>Payment Method</label>
                                    <p><?php echo $frow['payment_method'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row w-100">
                    <?php
                    $i = 1;
                    foreach ($quotationRows as $rows) {
                    ?>
                        <div class="col mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <h2>Applicant Information</h2>
                                </div>
                                <div class="card-body">
                                    <div class="card-info">
                                        <div class="info-item">
                                            <label>Name</label>
                                            <p><?php echo $rows['firstname'] . " " . $rows['lastname'] ?></p>
                                        </div>
                                        <div class="info-item">
                                            <label>Number</label>
                                            <p><?php echo $rows['number'] ?></p>
                                        </div>
                                        <div class="info-item">
                                            <label>Email</label>
                                            <p><?php echo $rows['email'] ?></p>
                                        </div>
                                        <div class="info-item">
                                            <label>Birthdate</label>
                                            <p><?php echo $rows['bdate'] ?></p>
                                        </div>
                                        <div class="info-item">
                                            <label>Age</label>
                                            <p><?php echo $rows['age'] ?></p>
                                        </div>
                                        <div class="info-item">
                                            <label>Sex</label>
                                            <p><?php echo $rows['sex'] ?></p>
                                        </div>
                                        <div class="info-item">
                                            <label>Civil Status</label>
                                            <p><?php echo $rows['civil'] ?></p>
                                        </div>
                                        <div class="info-item">
                                            <label>Address</label>
                                            <p><?php echo $rows['address'] ?></p>
                                        </div>
                                    </div>
                                    <div class="card-images">
                                        <div class="image-item">
                                            <label>Passport Picture</label>
                                            <img src="../user/uploads/<?php echo $rows['passport'] ?>" alt="Passport Picture">
                                        </div>
                                        <div class="image-item">
                                            <label>Valid ID Picture</label>
                                            <img src="../user/uploads/<?php echo $rows['validid'] ?>?>" alt="Valid ID Picture">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-100"></div>
                    <?php
                        $i++;
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="modal fade" id="accept" tabindex="-1" role="dialog" aria-labelledby="acceptLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title w-700 ml-3" id="request">Accept Booking</h5>
                    </div>
                    <form action="inc/accept-booking.php" style="width: 100%;" id="accept" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                        <div class="modal-body mb-4 mt-3">
                            <div class="row w-100">
                                <div class="col-8">
                                    <label for="">Title</label><label for="" class="required">*</label>
                                    <input type="text" id="title"
                                        placeholder="Osaka Japan" name="title"
                                        class="form-control" required>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-6 mt-4">
                                    <label for="">Price</label><label for="" class="required">*</label>
                                    <input type="number" id="price"
                                        placeholder="&#x20B1; 9,999.00" name="price" step="0.01" min="0"
                                        class="form-control" required>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-12 mt-4">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" maxlength="250" rows="3" required></textarea>
                                    <div class="char-count float-right">
                                        <span id="charCount">250</span> characters remaining
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="bookid" value="<?php echo $bookid ?>">
                            <input type="hidden" name="adminid" value="<?php echo $adminid ?>">
                            <input type="hidden" name="userid" value="<?php echo $frow['userid'] ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close</button>
                            <button style="display:none" type="submit" name="accept-book" id="submit">Next</button>
                            <button type="button" id="next" class="btn btn-success">Accept</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script src="../user/js/validation.js"></script>
</body>

</html>