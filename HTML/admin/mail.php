<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;

// Require necessary files
require '../../vendor/autoload.php'; // For Dompdf
include "../../inc/Include.php";

// Create an instance; passing `true` enables exceptions
if (isset($_POST["submit"])) {
    $mail = new PHPMailer(true);
    $dompdf = new Dompdf();

    // Collect data from POST
    $total = $_POST['total'];
    $inclusions = $_POST['inclusions'];
    $exclusions = $_POST['exclusions'];
    $itinerary = $_POST['itinerary'];
    $remarks = $_POST['remarks'];
    $adminid = $_POST['adminid'];
    $id = $_POST['id'];

    // Fetch quotation details
    $quotation = "SELECT *, quotation.email as email2, DATE(date) AS date FROM quotation INNER JOIN user ON quotation.userid = user.userid WHERE quotation.quotationid = '$id'";
    $quotationResults = mysqli_query($conn, $quotation);
    $quotationRows = mysqli_fetch_assoc($quotationResults);

    $date = date_create("$quotationRows[date]");
    $dateFormat = date_format($date, "F d, Y");

    // Create HTML for PDF
    $body = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Quotation</title>
        <style>
            body {
                font-family: 'Inter', sans-serif !important;
                color: #333;
                font-size: 14px;
            }
        </style>
    </head>
    <body>
        <div class='main'>
            <div class='row bkq '>
                <div class='col-12 case mr-4'>
                    <div class='row frame-quote w-100'>
                        <div class='col-12'>
                            <div style='text-align:center;'>
                                <p class='text-center'><strong>ZORELLA TRAVEL AND TOURS</strong><br>
                                    CALUMPANG LILIW LAGUNA<br>
                                    Laguna, Philippines 4004<br>
                                    +639237374423<br>
                                    zorellatravelandtours@gmail.com
                                </p>
                                <br>
                            </div>
                            <p>
                                Clients Name: <strong>$quotationRows[firstname] $quotationRows[lastname]</strong><br>
                                Quotation Title: <strong>$quotationRows[title]</strong><br>
                                Date of Travel: <strong>$dateFormat</strong><br>
                                Number of Pax: <strong>$quotationRows[pax]</strong><br>
                            </p>
                            <hr class='mb-3 mt-3'>
                            <strong><p>Dear Valued Client,</p></strong>
                            <p>Please see below quotation for your perusal.<br>
                                The rate below is subject to change without prior notice.
                            </p>
    
                            <div style='border:2px solid black;padding:16px;text-align:center;'>
                                <strong>$total / PAX<br>
                                ALL IN PACKAGE</strong>
                            </div>
                            <strong><p>HOTEL: <label class='ml-5'>3* or Similar Class</label></p></strong>
                            <strong><p class='mt-3 w-700'>INCLUSIONS:</p></strong>
                            <p style='white-space: pre-wrap;'>$inclusions</p>
                            <p class='mt-3 w-700'><strong>EXCLUSIONS:</strong></p>
                            <p style='white-space: pre-wrap;'>$exclusions</p>
                            <p class='mt-3 w-700'><strong>Itinerary:</strong></p>
                            <p style='white-space: pre-wrap;'>$itinerary</p>
                            <p><strong>REMARKS</strong></p>
                            <p style='white-space: pre-wrap;'>$remarks</p>

                            <br>
                            <p style='text-align:right;'>Prepared by: <strong>Janine Rabajante</strong><br>
                                Marketing Executive
                            </p>
                            <p style='text-align:right;'>Conforme: <strong>Ms. Bernadette Cagampan</strong><br>
                                Operations Manager
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>";

    // Load HTML into Dompdf
    $dompdf->loadHtml($body);

    // Set paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    $date = date('Ymds');
    // Output the generated PDF to a file
    $pdfOutput = $dompdf->output();

    $pdfFilePath = 'quotation/' . $quotationRows['firstname'] . $quotationRows['lastname'] . $date . '.pdf'; // Adjust the path as needed
    file_put_contents($pdfFilePath, $pdfOutput);

    // Email settings
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'gremoryyxx@gmail.com';
    $mail->Password   = 'lxnaxcxdslvzxhqi'; // Your SMTP password
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    // Recipients
    $mail->setFrom('gremoryyxx@gmail.com', 'Zorella Travel and Tours');
    $mail->addAddress($quotationRows['email2']);
    $mail->addReplyTo('gremoryyxx@gmail.com', 'Zorella Travel and Tours');

    // Email content
    $mail->isHTML(true);
    $mail->Subject = "Quotation for " . $quotationRows['title'];
    $mail->Body    = "Please find attached the quotation PDF.";

    // Attach PDF file
    $mail->addAttachment($pdfFilePath);

    // Send email
    if ($mail->send()) {
        header("Location: view-quotation.php?adminid=$adminid&qoute=$id");
        // Optionally update the database
        $query = "UPDATE quotation SET status = 'Sent' WHERE quotationid = $id";
        mysqli_query($conn, $query);
    } else {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}
