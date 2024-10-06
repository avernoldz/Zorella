<?php
session_start();

// Include necessary files and libraries
include '../../../inc/Include.php';
require 'C:\xampp\htdocs\Zorella\vendor\autoload.php';
include '../function/function.php';
include '../function/payment.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;

$mail = new PHPMailer(true);
$dompdf = new Dompdf();

// Sanitize and validate input
$userid = filter_input(INPUT_GET, 'userid', FILTER_SANITIZE_NUMBER_INT);
$payment_id = filter_input(INPUT_GET, 'payment_id', FILTER_SANITIZE_NUMBER_INT);
$bookid = filter_input(INPUT_GET, 'bookid', FILTER_SANITIZE_NUMBER_INT);
$booking_type = mysqli_escape_string($conn, $_GET['booking_type']);
$booking_id = mysqli_escape_string($conn, $_GET['booking_id']);

if (!$userid || !$payment_id) {
    die('Invalid input.');
}


if ($booking_type == 'Ticketed' || $booking_type == 'Customize') {
    // Prepare and execute database query
    $query = "
        SELECT
            u.userid,
            u.firstname,
            u.lastname,
            u.email,
            pt.downpayment,
            pt.total_price,
            pt.remaining_balance,
             p.payment_type,
             pt.paid_date,
             pt.price_to_pay,
            SUM(tc.adult + tc.child + tc.senior + tc.infant) AS pax,
            tc.departure as travel_date,
            tc.destination as tour
        FROM
            booking b
        INNER JOIN
            user u ON b.userid = u.userid
        INNER JOIN
            ticket tc ON (b.booking_type = 'Customize' OR b.booking_type = 'Ticketed') AND b.booking_id = tc.ticketid
        INNER JOIN
            payment p ON p.booking_id = b.booking_id AND (p.booking_type = 'Customize' OR p.booking_type = 'Ticketed')
        LEFT JOIN
            paymentinfo pt ON pt.payment_id = p.paymentid
        WHERE
            u.userid = ? AND pt.paymentinfoid = ?
        GROUP BY
            u.userid, u.firstname, u.lastname, u.email, pt.downpayment, pt.total_price, pt.remaining_balance, tc.departure ";


    processBookingPayment($conn, $userid, $payment_id, $bookid, $query, $mail);
} elseif ($booking_type == 'Educational') {

    $query = "
        SELECT
            u.userid,
            u.firstname,
            u.lastname,
            u.email,
            pt.downpayment,
             p.payment_type,
            pt.total_price,
            pt.paid_date,
            pt.price_to_pay,
            pt.remaining_balance,
            tc.pax, 
            tc.sdate as travel_date
        FROM
            booking b
        INNER JOIN
            user u ON b.userid = u.userid
        INNER JOIN
            educational tc ON b.booking_type = 'Educational' AND b.booking_id = tc.educationalid
        INNER JOIN
            payment p ON p.booking_id = b.booking_id AND p.booking_type = 'Educational'
        LEFT JOIN
            paymentinfo pt ON pt.payment_id = p.paymentid
        WHERE
            u.userid = ? AND pt.paymentinfoid = ?
        GROUP BY
            u.userid, u.firstname, u.lastname, u.email, pt.downpayment, pt.total_price, pt.remaining_balance, tc.sdate ";
    processBookingPayment($conn, $userid, $payment_id, $bookid, $query,  $mail);
} else {

    $query = "
        SELECT
            u.userid,
            u.firstname,
            u.lastname,
            u.email,
            pt.downpayment,
            pt.total_price,
            pt.remaining_balance,
            p.payment_type,
            pt.paid_date,
            pt.price_to_pay,
            tc.pax, 
            tc.tour_date as travel_date,
            tp.title as tour
        FROM
            booking b
        INNER JOIN
            user u ON b.userid = u.userid
        INNER JOIN
            tourbooking tc ON b.booking_type = 'Tour Package' AND b.booking_id = tc.tour_bookid
        INNER JOIN
            payment p ON p.booking_id = b.booking_id AND p.booking_type = 'Tour Package'
        LEFT JOIN
            paymentinfo pt ON pt.payment_id = p.paymentid
        LEFT JOIN
            tourpackage tp ON tp.tourid = tc.tourid
        WHERE
            u.userid = ? AND pt.paymentinfoid = ?
        GROUP BY
            u.userid, u.firstname, u.lastname, u.email, pt.downpayment, pt.total_price, pt.remaining_balance, tc.tour_date ";

    processBookingPayment($conn, $userid, $payment_id, $bookid, $query,  $mail);
}

// Prepare and execute database query
function processBookingPayment($conn, $userid, $payment_id, $bookid, $query, $mail)
{
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $userid, $payment_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $name = null;
    $email = null;
    $price = null;
    $travel_date = null;
    $pax = null;
    $tour = null;
    $payment_type = null;

    if ($row = $res->fetch_assoc()) {
        $name = $row['firstname'] . " " . $row['lastname'];
        $email = $row['email'];

        if (!empty($row['paid_date'])) {
            // Check if paid_date has a value
            $price = intval($row['price_to_pay']);
        } else {
            $price = intval($row['downpayment']);
        }
        $balance = $row['remaining_balance'];

        $travel_date = $row['travel_date'];
        $pax = $row['pax'];
        $payment_type = $row['payment_type'];
        $tour = isset($row['tour']) ? $row['tour'] : '';
    } else {
        die('No matching records found.');
    }

    $remaining_balance = $balance - $price;


    // Update booking status
    if ($remaining_balance == '0.00' || $remaining_balance == 0.00) {
        $stat = 'Installment';
    } else {
        $stat = 'Paid';
    }
    // $stat = ($payment_type == 'Installment') ? 'Installment' : 'Paid';

    $update = "UPDATE booking SET status = '$stat' WHERE bookid = ?";
    $update_stmt = $conn->prepare($update);
    $update_stmt->bind_param('i', $bookid);
    $update_stmt->execute();

    $update2 = "INSERT INTO installmenthistory (amount, paymentinfoid) VALUES (?, ?)";
    $update2_stmt = $conn->prepare($update2);
    $update2_stmt->bind_param('si', $price, $payment_id);
    $update2_stmt->execute();

    $update3 = "UPDATE paymentinfo SET remaining_balance = '$remaining_balance' WHERE paymentinfoid = ?";
    $update3_stmt = $conn->prepare($update3);
    $update3_stmt->bind_param('i', $payment_id);
    $update3_stmt->execute();

    $body = generatePaymentPDF($name, $tour, $price, $pax, $travel_date, $remaining_balance, 'GCASH');
    $pdfFilePath = savePDFConfirm($body, $name, 'confirm-payment');
    $confirmation_pdf = basename($pdfFilePath);

    sendEmail($mail, $email, $pdfFilePath, $tour, 'Acknowledgement Receipt ');
    $_SESSION['paid'] = 'Your booking was successfully paid. Thank you!';
    // Redirect to Stripe Checkout
    header("Location: ../gcash.php?adminid=$userid&alert=1");
    exit();
}
