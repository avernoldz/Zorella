<?php
session_start();
session_regenerate_id();
if (!$_SESSION['adminid']) {
    header("Location:../index.php?Login-first");
    exit();
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

    $bookingid = $_GET['bookingid'];
    $bookingtype = $_GET['bookingtype'];
    $bookid = $_GET['bookid'];
    $adminid = $_GET['adminid'];
    $active = $bookingtype;
    $on = "on";
    include "../../inc/Include.php";
    include "header-admin.php";
    include "side-bar-admin.php";

    $quotation = "SELECT 
    user.firstname AS user_firstname, 
    user.lastname AS user_lastname,
    user.userid AS id, 
    leadpersoninfo.*, 
    educational.*, 
    booking.*,
    booking.branch AS branchs,
    payment.*,
    paymentinfo.*
    FROM 
        user
    INNER JOIN 
        educational ON educational.userid = user.userid
    INNER JOIN 
        leadpersoninfo ON leadpersoninfo.booking_id = educational.educationalid
    INNER JOIN 
        booking ON booking.booking_id = educational.educationalid AND booking.booking_type = '$bookingtype' -- Adjust this join condition if necessary
    INNER JOIN 
        payment ON payment.booking_id = booking.booking_id AND payment.booking_type = '$bookingtype'
    LEFT JOIN  paymentinfo ON paymentinfo.payment_id = payment.paymentid 
    WHERE 
        educational.educationalid = '$bookingid';
    ";

    $quotationResults = mysqli_query($conn, $quotation);
    $quotationRows = mysqli_fetch_all($quotationResults, MYSQLI_ASSOC);

    $frow = $quotationRows[0] ?? null;

    $dateFormats = [
        'sdate' => 'M d, Y',
        'edate' => 'M d, Y',
        'created_at' => 'M d, Y : h:i A'
    ];

    foreach ($dateFormats as $key => $format) {
        if (isset($frow[$key]) && $frow[$key] !== '0000-00-00') {
            $date = new DateTime($frow[$key]);
            $frow[$key] = $date->format($format);
        } else {
            // Set to empty string if date is '0000-00-00' or not set
            $frow[$key] = '';
        }
    }

    ?>
    <div class="main">
        <div class="row bkq ">
            <div class="case row w-100 mb-4">
                <div class="col flex">
                    <h1 class="w-700"><i class="fa-solid fa-envelope fa-fw"></i>&nbsp;&nbsp;<?php echo "$frow[user_firstname] $frow[user_lastname]" ?></h1>
                </div>
                <?php if ($frow['status'] == 'Pending'): ?>
                    <div class="col flex justify-content-end accept">
                        <button type="button" data-target="#accept" data-toggle="modal" class="btn btn-success"><i class="fa-solid fa-check fa-fw "></i>Accept</button>
                        <button type="button" data-target="#reject" data-toggle="modal" class="btn btn-secondary reject"><i class="fa-solid fa-xmark fa-fw text-danger"></i> Reject</button>
                    </div>
                <?php elseif ($frow['status'] == 'Rejected'): ?>
                    <div class="col flex justify-content-end accept">
                        <button type="button" class="btn btn-secondary reject"><i class="fa-solid fa-xmark fa-fw text-danger"></i> Rejected</button>
                    </div>
                <?php else: ?>
                    <div class="col flex justify-content-end accept">
                        <?php echo "<a style='font-size: 12px;' class='btn btn-success' href='confirmation-bookings/$frow[confirmation_pdf]' target='_blank'>Download Confirmation Booking</a>" ?>
                    </div>
                <?php endif; ?>
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
                            <p><?php echo $frow['branchs'] ?></p>
                        </div>
                        <div class="info-item">
                            <label>Start Date</label>
                            <p><?php echo $frow['sdate'] ?></p>
                        </div>
                        <div class="info-item">
                            <label>End Date</label>
                            <p><?php echo $frow['edate'] ?></p>
                        </div>
                        <?php if (!empty($frow['hotel'])): ?>
                            <div class="info-item">
                                <label>Hotel</label>
                                <p><?php echo htmlspecialchars($frow['hotel']); ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <label>Pax</label>
                            <p><?php echo $frow['pax'] ?></p>
                        </div>
                        <?php if (!empty($frow['attraction'])): ?>
                            <div class="info-item">
                                <label>Attraction</label>
                                <p><?php echo htmlspecialchars($frow['attraction']); ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <label>Status</label>
                            <?php
                            if ($frow['status'] == 'Pending') {
                                echo "<p class='text-warning'>Pending</p>";
                            } else if ($frow['status'] == 'Payment') {
                                echo "<p class='text-info'>Confirmed Booking, waiting for payment</p>";
                            } else if ($frow['status'] == 'Rejected') {
                                echo "<p class='text-danger'>Rejected</p>";
                            } else {
                                echo "<p class='text-success'>Paid</p>";
                            }
                            ?>
                        </div>
                        <div class="info-item">
                            <label>Date Created</label>
                            <p><?php echo $frow['created_at'] ?></p>
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
                    <div class="col mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h2>Lead Person Information</h2>
                            </div>
                            <div class="card-body">
                                <div class="card-info">
                                    <div class="info-item">
                                        <label>Name</label>
                                        <p><?php echo $frow['firstname'] . " " . $frow['lastname'] ?></p>
                                    </div>
                                    <div class="info-item">
                                        <label>Number</label>
                                        <p><?php echo $frow['number'] ?></p>
                                    </div>
                                    <div class="info-item">
                                        <label>Email</label>
                                        <p><?php echo $frow['email'] ?></p>
                                    </div>
                                    <div class="info-item">
                                        <label>Position</label>
                                        <p><?php echo $frow['position'] ?></p>
                                    </div>
                                    <div class="info-item">
                                        <label>Pickup</label>
                                        <p><?php echo $frow['pickup'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-100"></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="accept" tabindex="-1" role="dialog" aria-labelledby="acceptLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <form action="inc/accept-booking.php" style="width: 100%;" id="accept" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                        <div class="modal-body mb-4 mt-3">
                            <div class="col-12 case mr-4">
                                <div class="row frame-quote w-100">
                                    <div class="col-12">
                                        <p class="text-center"><label>ZORELLA TRAVEL AND TOURS</label> <br>
                                            CALUMPANG LILIW LAGUNA <br>
                                            Laguna, Philippines 4004 <br>
                                            +639237374423 <br>
                                            zorellatravelandtours@gmail.com
                                        </p>
                                        <p class="text-center w-700 mt-3" style="font-size: 14px;"> CONFIRMATION BOOKING</p>
                                        <p class="text-center w-700" style="font-size: 14px;"> (BOOKING ORDER)</p>

                                        <div class="row w-100">
                                            <div class="col flex">
                                                <p> Date: <label id="today_date"><?php echo date('F d, Y') ?></label></p>
                                                <p> BOOKING ORDER NO: <label><?php echo $bookid ?></label></p>
                                            </div>
                                            <div class=" w-100"></div>
                                            <div class="col">
                                                <br>
                                                <p>Good Day!</p>
                                                <br>
                                                <p>We are please to confirm to your booking as shown below</p>
                                            </div>
                                        </div>
                                        <hr class="mb-3 mt-3">

                                        <div class="row w-100">
                                            <div class="col flex " style="align-items: normal;">
                                                <div class=" div">
                                                    <p>Days: <label><?php
                                                                    $date1 = new DateTime($frow['sdate']);
                                                                    $date2 = new DateTime($frow['edate']);

                                                                    $interval = $date1->diff($date2);
                                                                    echo $interval->days . ' Days';
                                                                    ?></label></p>
                                                    <p>Date: <label id="travel_date"><?php echo $frow['sdate'] ?></label></p>
                                                    <p>PAX: <label id="total_pax"><?php echo $frow['pax'] ?></label></p>
                                                </div>
                                                <div>
                                                    <p class="w-700 text-center pl-4 pr-3" style="background:#ffb0f1;">PER PERSON RATE</p>
                                                    <span>PHP </span><input type="number" id="price_pax" name="pax_price" required min="0" style="width: 5rem; border:none; border-bottom: 1px solid #e3e3e3;" placeholder="20,000.00"> <span>/ PAX</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row w-100 mt-2">
                                            <div class="col flex">
                                                <div class="col-9"></div>
                                                <div class="col">
                                                    <p class="w-700 text-center pl-2 pr-2" style="background:#fbd3f4;">TOTAL AMOUNT</p>
                                                    <p class="w-700 text-center pl-2 pr-2" style="background:#ffb0f1;">PHP <label id="total_price"></label></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row-100 mt-3">
                                            <table class="w-100 table table-bordered down">
                                                <tr>
                                                    <td>
                                                        <p>DOWNPAYMENT: <span class="w-700">PHP </span><input type="number" id="downpayment" name="downpayment" required min="0" style="width: 5rem; border:none; border-bottom: 1px solid #e3e3e3;" placeholder="20,000.00"></p>
                                                        <small class="text-danger">to be paid at your earliest convenince*</small>
                                                    </td>
                                                    <td>
                                                        <p>REMAINING BALANCE: <span class="w-700">PHP </span><span class="w-700" id="remaining_balance"></span></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>

                                                    </td>
                                                    <td id="installments">

                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="row-100 mt-3">
                                            <table class="w-100 table table-bordered passengers">
                                                <tr>
                                                    <td colspan="3" class="text-center w-700">LEAD PERSON INFORMATION</td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td class="w-700">FIRSTNAME</td>
                                                    <td class="w-700">SURNAME</td>
                                                    <td class="w-700">POSITION</td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td><?php echo $frow['firstname'] ?></td>
                                                    <td><?php echo $frow['lastname'] ?></td>
                                                    <td><?php echo $frow['position'] ?></td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="mb-4 mt-4">
                                            <textarea id="itinerary-textarea" name="terms" rows="20"></textarea>

                                        </div>
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

                            <input type="hidden" name="booking_id" value="<?php echo $bookingid ?>">
                            <input type="hidden" name="booking_type" value="<?php echo $bookingtype ?>">
                            <input type="hidden" name="paymentid" value="<?php echo $frow['paymentid'] ?>">
                            <input type="hidden" name="remaining_balance" id="remaining_bal" value="">
                            <input type="hidden" name="price_to_pay" id="price_to_pay" value="">
                            <input type="hidden" name="installment_number" id="installment_number" value="">
                            <input type="hidden" name="due_date" id="due_date" value="">
                            <input type="hidden" name="total_price" id="total_amount" value="">
                            <input type="hidden" name="bookid" value="<?php echo $bookid ?>">
                            <input type="hidden" name="adminid" value="<?php echo $adminid ?>">
                            <input type="hidden" name="userid" value="<?php echo $frow['id'] ?>">
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

        <div class="modal fade" id="reject" tabindex="-1" role="dialog" aria-labelledby="acceptLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form action="inc/accept-booking.php" style="width: 100%;" id="reject" class="needs-validation" method="POST" enctype="multipart/form-data" novalidate>
                        <div class="modal-body mb-3 mt-3">
                            <div class="row w-100">
                                <div class="col text-center">
                                    <i class="fa-solid fa-triangle-exclamation fa-fw text-danger mb-2" style="font-size: 32px;"></i>
                                    <h1 style="font-size: 22px;" class="mb-1">Reject Booking</h1>
                                    <p style="font-size: 14px;padding:8px 24px;">Are you sure you want to reject this booking? This action cannot be undone.</p>
                                </div>
                            </div>
                            <input type="hidden" name="bookid" value="<?php echo $bookid ?>">
                            <input type="hidden" name="adminid" value="<?php echo $adminid ?>">
                            <input type="hidden" name="booking_id" value="<?php echo $bookingid ?>">
                            <input type="hidden" name="booking_type" value="<?php echo $bookingtype ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close</button>
                            <button type="submit" name="reject-book" id="submit" class="btn btn-danger">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script>
        var installmentType = "<?php echo $frow['installment_type']; ?>";
    </script>
    <script src="js/app.js"></script>
    <script src="../user//js/validation.js"></script>
    <script>
        tinymce.init({
            selector: '#itinerary-textarea', // Attach TinyMCE to the textarea with id 'itinerary'
            plugins: [
                // Core editing features
                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                // Your account includes a free trial of TinyMCE premium features
                // Try the most popular premium features until Oct 2, 2024:
                'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown',
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [{
                    value: 'First.Name',
                    title: 'First Name'
                },
                {
                    value: 'Email',
                    title: 'Email'
                },
            ],
            ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
            height: 500, // Set height for the editor
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            images_upload_url: 'postAcceptor.php', // Handle image uploads
            branding: false // Remove "Powered by TinyMCE" branding
        });
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(window.location.search);
        const alert = urlParams.get('alert');

        if (alert == 4) {
            toastr["success"]("Request for booking rejected successfully")
        }
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": false,
            "preventDuplicates": false,
            "positionClass": "toast-bottom-center",
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    </script>
</body>

</html>