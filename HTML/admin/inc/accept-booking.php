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
