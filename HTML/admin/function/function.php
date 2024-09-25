<?php

function getBookings($conn, $branch, $status)
{
    $query = "
        SELECT
    u.userid,
    u.firstname,
    u.lastname,
    b.bookid,
    b.booking_id,
    b.booking_type,
    b.status,
    b.created_at,
    tb.*,
    eb.sdate,
    eb.pax AS ebpax,
    eb.hotel,
    tc.*
    FROM
        booking b
    INNER JOIN
        user u ON b.userid = u.userid
    LEFT JOIN
        educational eb ON b.booking_type = 'Educational' AND b.booking_id = eb.educationalid
    LEFT JOIN
        tourbooking tc ON b.booking_type = 'Tour Package' AND b.booking_id = tc.tour_bookid
    LEFT JOIN
        ticket tb ON b.booking_id = tb.ticketid
        AND (b.booking_type = 'Ticketed' OR
            b.booking_type = 'Customize')
    WHERE
        b.status = ? && b.branch = ? ORDER BY b.bookid DESC";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $status, $branch);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

function getTicketedBookings($conn, $branch, $status)
{
    $query = "
        SELECT
            u.userid,
            u.firstname,
            u.lastname,
            b.bookid,
            b.booking_id,
            b.booking_type,
            b.status,
            b.created_at,
            tb.*,
            eb.sdate,
            eb.pax,
            eb.hotel
        FROM
            booking b
        INNER JOIN
            user u ON b.userid = u.userid
        LEFT JOIN
            educational eb ON b.booking_type = 'Educational' AND b.booking_id = eb.educationalid
        LEFT JOIN
            ticket tb ON b.booking_id = tb.ticketid
            AND (b.booking_type = 'Ticketed' OR
                b.booking_type = 'Customize')
        WHERE
            b.branch = ? 
            AND b.status <> ? 
            AND b.booking_type = 'Ticketed'
        ORDER BY b.bookid DESC
    ";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $branch, $status);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

function processBooking($conn, $mail)
{

    $bookingData = escapeInputData($conn, $_POST);
    $bookingData['price_to_pay'] = !empty($bookingData['price_to_pay']) ? $bookingData['price_to_pay'] : ($bookingData['downpayment'] ?? 0);
    $bookingData['installment_number'] = !empty($bookingData['installment_number']) ? $bookingData['installment_number'] : 0;
    $bookingData['due_date'] = !empty($bookingData['due_date']) ? $bookingData['due_date'] : 'NOT APPLICABLE';

    // Update booking status
    $updateStatusQuery = "UPDATE booking SET status = 'Payment' WHERE bookid = '{$bookingData['bookid']}'";

    // Retrieve tour details
    if ($bookingData['booking_type'] == 'Ticketed' || $bookingData['booking_type'] == 'Customize') {
        $tourRows = getTicket($conn, $bookingData['booking_id'], $bookingData['booking_type']);
    } elseif ($bookingData['booking_type'] == 'Educational') {
        $tourRows = getEducation($conn, $bookingData['booking_id'], $bookingData['booking_type']);
    } else {
        $tourRows = getTourDetails($conn, $bookingData['booking_id'], $bookingData['booking_type']);
    }


    $firstRow = !empty($tourRows) ? $tourRows[0] : null;
    if (!$firstRow) {
        die('No tour details found.');
    }

    $totalPassengers = calculateTotalPassengers($firstRow);
    formatDates($firstRow);

    // Insert payment info
    $body = generateConfirmationPDF($firstRow, $tourRows, $bookingData, $totalPassengers);
    $pdfFilePath = savePDF($body, $firstRow, 'confirmation-bookings');
    $confirmation_pdf = basename($pdfFilePath);
    $insertPaymentQuery = createInsertPaymentQuery($bookingData, $confirmation_pdf);

    if (mysqli_query($conn, $insertPaymentQuery)) {
        sendEmail($mail, $firstRow['send_email'], $pdfFilePath, $firstRow['tour_title']); // Corrected email variable

        // Update booking status in the database
        mysqli_query($conn, $updateStatusQuery);
        header("Location: ../pending-bookings.php?adminid={$bookingData['adminid']}");
        exit();
    } else {
        echo mysqli_error($conn);
    }
}

function escapeInputData($conn, $data)
{
    foreach ($data as $key => $value) {
        $data[$key] = mysqli_real_escape_string($conn, $value);
    }
    return $data;
}

function getTourDetails($conn, $ticketId, $bookingType)
{
    $query = "SELECT user.firstname AS user_firstname, user.lastname AS user_lastname, user.email AS send_email, 
                     personalinfo.*, tourbooking.*, payment.*, booking.*, tourpackage.*
              FROM user 
              INNER JOIN tourbooking ON tourbooking.userid = user.userid 
              RIGHT JOIN personalinfo ON personalinfo.ticketid = tourbooking.tour_bookid AND personalinfo.booking_type = '$bookingType'
              RIGHT JOIN payment ON payment.booking_id = tourbooking.tour_bookid AND payment.booking_type = '$bookingType'
              RIGHT JOIN booking ON booking.booking_id = tourbooking.tour_bookid AND booking.booking_type = '$bookingType'
              RIGHT JOIN tourpackage ON tourpackage.tourid = tourbooking.tourid 
              WHERE tourbooking.tour_bookid = '$ticketId' AND booking.booking_type = '$bookingType'";

    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getTicket($conn, $ticketId, $bookingType)
{
    $query = "SELECT user.firstname AS user_firstname, user.lastname AS user_lastname, user.email AS send_email, 
                     personalinfo.*, ticket.*, payment.*, booking.*
              FROM user 
              INNER JOIN ticket ON ticket.userid = user.userid 
              RIGHT JOIN personalinfo ON personalinfo.ticketid = ticket.ticketid AND personalinfo.booking_type = '$bookingType'
              RIGHT JOIN payment ON payment.booking_id = ticket.ticketid AND payment.booking_type = '$bookingType'
              RIGHT JOIN booking ON booking.booking_id = ticket.ticketid AND booking.booking_type = '$bookingType'
              WHERE ticket.ticketid = '$ticketId' AND booking.booking_type = '$bookingType'";

    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getEducation($conn, $ticketId, $bookingType)
{
    $query = "SELECT 
    user.firstname AS user_firstname, user.lastname AS user_lastname, user.email AS send_email,
    leadpersoninfo.*, 
    educational.*, 
    booking.branch AS branchs,
    payment.*
    FROM 
        user
    INNER JOIN 
        educational ON educational.userid = user.userid
    INNER JOIN 
        leadpersoninfo ON leadpersoninfo.booking_id = educational.educationalid
    INNER JOIN 
        booking ON booking.booking_id = educational.educationalid AND booking.booking_type = '$bookingType' -- Adjust this join condition if necessary
    INNER JOIN 
        payment ON payment.booking_id = booking.booking_id AND payment.booking_type = '$bookingType'
    WHERE 
        educational.educationalid = '$ticketId'";

    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}



function calculateTotalPassengers($firstRow)
{
    if (!$firstRow) return 0;

    $passengerTypes = ['adult', 'child', 'infant', 'senior'];
    $total = 0;

    foreach ($passengerTypes as $type) {
        $total += isset($firstRow[$type]) ? (int)$firstRow[$type] : 0;
    }
    return $total;
}

function formatDates(&$firstRow)
{
    $dateFormats = [
        'departure' => 'M d, Y',
        'arrival' => 'M d, Y',
        'sdate' => 'F d, Y',
        'edate' => 'F d, Y',
        'tour_date' => 'F d, Y',
        'created_at' => 'M d, Y : h:i A'
    ];

    foreach ($dateFormats as $key => $format) {
        if (isset($firstRow[$key]) && $firstRow[$key] !== '0000-00-00') {
            $date = new DateTime($firstRow[$key]);
            $firstRow[$key] = $date->format($format);
        } else {
            $firstRow[$key] = '';
        }
    }
}

function createInsertPaymentQuery($bookingData, $confirmation_pdf)
{
    return "INSERT INTO `paymentinfo`(`confirmation_pdf`, `payment_id`, `total_price`, `downpayment`, `price_to_pay`, 
            `installment_number`, `remaining_balance`, `due_date`, `pax_price`, `terms`, `userid`, `adminid`)
            VALUES ('$confirmation_pdf', '{$bookingData['paymentid']}', '{$bookingData['total_price']}', '{$bookingData['downpayment']}', 
            '{$bookingData['price_to_pay']}', '{$bookingData['installment_number']}', '{$bookingData['remaining_balance']}', 
            '{$bookingData['due_date']}', '{$bookingData['pax_price']}', '{$bookingData['terms']}', 
            '{$bookingData['userid']}', '{$bookingData['adminid']}')";
}

function generateConfirmationPDF($firstRow, $tourRows, $bookingData, $totalPassengers)
{
    // $bookingData['formatted_due_date'] = (new DateTime($bookingData['due_date']))->format('F m, Y');

    $installmentDueDates = $bookingData['due_date'];
    $dueDatesArray = explode(". ", $installmentDueDates);

    $date1 = new DateTime($firstRow['departure'] ?? $firstRow['sdate'] ?? 'now');
    $date2 = new DateTime($firstRow['arrival'] ?? $firstRow['edate'] ?? 'now');

    if (!empty($firstRow['sdate'])) {
        $date1 = DateTime::createFromFormat('F j, Y', $firstRow['sdate']);
    }
    if (!empty($firstRow['edate'])) {
        $date2 = DateTime::createFromFormat('F j, Y', $firstRow['edate']);
    }

    if ($date2 == new DateTime('now')) {
        $days = 'Indefinite';
    } else {
        $interval = $date1->diff($date2);
        $days = $interval->days . ' Days';
    }

    $htmlContent = $bookingData['terms'];
    // Convert HTML to plain text and replace line breaks
    $textContent = trim(preg_replace('/\r\n|\r|\n/', '<br>', $htmlContent));
    ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <title>Confirmation Booking</title>
        <style>
            body {
                font-family: "Roboto", sans-serif !important;
                color: #333;
                font-size: 14px;
            }
        </style>
    </head>

    <body>
        <div class="row w-100">
            <div style="text-align:center;">
                <p><strong>ZORELLA TRAVEL AND TOURS</strong><br>
                    CALUMPANG LILIW LAGUNA<br>
                    Laguna, Philippines 4004<br>
                    +639237374423<br>
                    zorellatravelandtours@gmail.com
                </p>
                <p style="font-size: 14px; font-weight: bold;">CONFIRMATION BOOKING<br>(BOOKING ORDER)</p>
            </div>
            <div class="col-12">
                <p style="float:right;">BOOKING ORDER NO: <strong><?php echo $bookingData['bookid'] ?></strong></p>
                <p>Date: <strong><?php echo date('F d, Y') ?></strong></p>
            </div>
            <div class="col">
                <p>Good Day!</p>
                <p>We are pleased to confirm your booking as shown below:</p>
            </div>
            <hr style="color:#e3e3e3; margin-bottom: 2px;">
            <div class="col">
                <p style="float:right;">
                    <strong style="background:#ffb0f1; padding: 2px 8px;">PER PERSON RATE</strong><br>
                    <label style="margin-left: 12px;">PHP <?php echo number_format($bookingData['pax_price'], 2) ?> / PAX</label>
                </p>
                <p>
                    Days: <strong><?php
                                    echo ($bookingData['booking_type'] == 'Ticketed' || $bookingData['booking_type'] == 'Customize' || $bookingData['booking_type'] == 'Educational')
                                        ? $days
                                        : '';
                                    ?></strong><br>

                    Date: <strong><?php
                                    echo ($bookingData['booking_type'] == 'Ticketed' || $bookingData['booking_type'] == 'Customize')
                                        ? $firstRow['departure']
                                        : ($bookingData['booking_type'] == 'Educational'
                                            ? $firstRow['sdate']
                                            : $firstRow['tour_date']);
                                    ?></strong><br>

                    PAX: <strong><?php
                                    echo ($bookingData['booking_type'] == 'Ticketed' || $bookingData['booking_type'] == 'Customize')
                                        ? $totalPassengers
                                        : $firstRow['pax'];
                                    ?></strong><br>
                </p>
            </div>
            <div class="col">
                <p style="text-align: right;">
                    <strong style="background:#fbd3f4; padding: 2px 22px;">TOTAL AMOUNT <?php echo $date2->format('Y-m-d'); ?></strong><br>
                    <label style="background:#ffb0f1; padding: 0px 21px;">PHP <?php echo number_format($bookingData['total_price'], 2) ?></label>
                </p>
            </div>
            <div class="col">
                <table style="width:100%; border-collapse: collapse;">
                    <tr>
                        <td style="border: 1px solid #000000; padding: 6px;">
                            DOWNPAYMENT: <strong>PHP <?php echo number_format($bookingData['downpayment'], 2) ?></strong><br>
                            <small style="color: red;">to be paid at your earliest convenience</small>
                        </td>
                        <td style="border: 1px solid #000000; padding: 6px;">
                            REMAINING BALANCE: <strong>PHP <?php echo number_format($bookingData['remaining_balance'], 2) ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000000; padding: 6px;"></td>
                        <td style="border: 1px solid #000000; padding: 6px;">
                            <?php
                            if ($bookingData['due_date'] !== 'NOT APPLICABLE'): // or use is_null($bookingData['due_date'])
                                foreach ($dueDatesArray as $date) :
                                    $formattedDate = trim($date);
                                    echo "{$formattedDate}: <strong>PHP " . number_format($bookingData['price_to_pay'], 2) . "</strong><br>";
                                endforeach;
                            endif;
                            ?>

                        </td>
                    </tr>
                </table>
            </div>
            <div class="col" style="margin-top: 14px;">
                <table style="width:100%; border-collapse: collapse;">
                    <tr>
                        <td colspan="3" style="text-align:center; font-weight:bold; border: 1px solid #000000;">PASSENGERS</td>
                    </tr>
                    <tr style="text-align:center; font-weight:bold">
                        <td style="border: 1px solid #000000;">FIRSTNAME</td>
                        <td style="border: 1px solid #000000;">SURNAME</td>
                        <td style="border: 1px solid #000000;">BIRTHDAY</td>
                    </tr>
                    <?php foreach ($tourRows as $rows): ?>
                        <tr style="text-align:center;">
                            <td style="border: 1px solid #000000;"><?php echo $rows['firstname'] ?></td>
                            <td style="border: 1px solid #000000;"><?php echo $rows['lastname'] ?></td>
                            <td style="border: 1px solid #000000;"><?php echo ($bookingData['booking_type'] == 'Educational') ? $rows['position'] : $rows['bdate']; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="col">
                <?php echo $textContent ?>
            </div>
            <p style="text-align:right;">Prepared by: <strong>Janine Rabajante</strong><br>Marketing Executive</p>
            <p style="text-align:right;">Conforme: <strong>Ms. Bernadette Cagampan</strong><br>Operations Manager</p>
        </div>
    </body>

    </html>
<?php
    return ob_get_clean();
}

function savePDF($body, $firstRow, $path)
{
    global $dompdf; // Use global variable to access the Dompdf instance
    $dompdf->loadHtml($body);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $date = date('Ymds');
    $pdfFilePath = '../' . $path . '/' . $firstRow['user_firstname'] . $firstRow['user_lastname'] . $date . '.pdf'; // Adjust the path as needed
    file_put_contents($pdfFilePath, $dompdf->output());

    return $pdfFilePath;
}

function sendEmail($mail, $recipientEmail, $pdfFilePath, $title) // Changed parameter name for clarity
{
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'gremoryyxx@gmail.com'; // Your SMTP username
    $mail->Password   = 'ioslrvtwplqqgyum'; // Your SMTP password
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    // Recipients
    $mail->setFrom('gremoryyxx@gmail.com', 'Zorella Travel and Tours');
    $mail->addAddress($recipientEmail); // Use the recipient's email correctly
    $mail->addReplyTo('gremoryyxx@gmail.com', 'Zorella Travel and Tours');

    // Email content
    $mail->isHTML(true);
    $mail->Subject = "Confirmation Booking " . $title; // $firstRow should be passed or made accessible
    $mail->Body    = "Please find attached the confirmation booking PDF.";
    $mail->addAttachment($pdfFilePath);

    // Send email
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}
