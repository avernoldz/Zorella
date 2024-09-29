<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;

// Require necessary files
require '../../../vendor/autoload.php'; // For PHPMailer and Dompdf
include '../../../inc/Include.php';
include '../function/function.php';

// Initialize PHPMailer and Dompdf
$mail = new PHPMailer(true);
$dompdf = new Dompdf();

if (isset($_POST['accept-book'])) {
    // Process the booking
    processBooking($conn, $mail);
}


if (isset($_POST['reject-book'])) {
    $bookid = $_POST['bookid'];
    $adminid = $_POST['adminid'];
    $bookingid = $_POST['booking_id'];
    $booking_type = $_POST['booking_type'];

    $query = "UPDATE booking SET status = 'Rejected' WHERE bookid = '$bookid'";
    if (mysqli_query($conn, $query)) {
        $redirects = [
            'Ticketed' => '../view-bookings.php',
            'Customize' => '../view-bookings.php',
            'Educational' => '../view-educational.php',
            'Tour Package' => '../view-tour.php'
        ];
        if (isset($redirects[$booking_type])) {
            echo "<script>window.location.href='{$redirects[$booking_type]}?adminid=$adminid&alert=4&bookingid=$bookingid&bookingtype=$booking_type&bookid=$bookid';</script>";
            // exit();
        }
    }
}
