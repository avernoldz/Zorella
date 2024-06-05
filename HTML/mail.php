<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//required files
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
include "../Connections/Include.php";

//Create an instance; passing `true` enables exceptions
if (isset($_POST["submit"])) {

    $mail = new PHPMailer(true);

    $total = $_POST['total'];
    $inclusions = $_POST['inclusions'];
    $exclusions = $_POST['exclusions'];
    $itinerary = $_POST['itinerary'];
    $remarks = $_POST['remarks'];
    $adminid = $_POST['adminid'];
    $id = $_POST['id'];

    $quotation = "SELECT *, quotation.email as email2, DATE(date) AS date FROM quotation INNER JOIN user ON quotation.userid = user.userid WHERE quotation.quotationid = '$id'";
    $quotationResults = mysqli_query($conn, $quotation);
    $quotationRows = mysqli_fetch_assoc($quotationResults);

    $date = date_create("$quotationRows[date]");
    $dateFormat = date_format($date, "F d, Y");

    $body='';
    $body .="
    <!DOCTYPE html>
    <html lang='en'>

    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <?php include '../Connections/cdn.php'?>
        <title>Quotation</title>
        <style>
            body {
                font-family: 'Inter', sans-serif !important;
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
        <div class='main'>
            <div class='row bkq '>
                <div class='col-12 case mr-4'>
                    <div class='row frame-quote w-100'>
                        <div class='col-12'>
                            <div style='text-align:center;'>
                                <p class='text-center'><strong>ZORELLA TRAVEL AND TOURS</strong> <br>
                                    CALUMPANG LILIW LAGUNA <br>
                                    Laguna, Philippines 4004 <br>
                                    +639237374423 <br>
                                    zorellatravelandtours@gmail.com
                                </p>
                            </div>
                            <p>
                                Clients Name: <strong>$quotationRows[firstname] $quotationRows[lastname]</strong> <br>
                                
                                Quotation Title: <strong>$quotationRows[title]</strong> <br>
                                
                                Date of Travel: <strong>$dateFormat</strong> <br>
                              
                                Number of Pax: <strong>$quotationRows[pax]</strong> <br>
                            </p>
                            <hr class='mb-3 mt-3'>
                            <strong><p>Dear Valued Client,</p></strong>
                            <p>Please see below quotation for your perusal. <br>
                                The rate below is subject to change without prior notice.
                            </p>
    
                            <div style='border:2px solid black;padding:16px;text-align:center;'>
                                <strong>P
                                $total
                                /PAX <br>
                                ALL IN PACKAGE</strong>
                            </div>
                            <strong><p >HOTEL: <label class='ml-5'>3* or Similar Class</label></p></strong>
                            <strong><p class='mt-3 w-700'>
                                INCLUSIONS:
                            </p></strong>
                            <p style='white-space: pre-wrap;'>$inclusions</p>
                            <p class='mt-3 w-700'>
                                <strong>EXCLUSIONS:</strong>
                            </p>
                            <p style='white-space: pre-wrap;'> $exclusions</p>
                            <p class='mt-3 w-700'>
                                <strong>Itinerary:</strong>
                            </p>
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

    //Server settings
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();                              //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';       //Set the SMTP server to send through
    $mail->SMTPAuth   = true;             //Enable SMTP authentication
    $mail->Username   = 'gremoryyxx@gmail.com';   //SMTP write your email
    $mail->Password   = 'ioslrvtwplqqgyum';      //SMTP password
    $mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
    $mail->Port       = 465;

    //Recipients
    $mail->setFrom('gremoryyxx@gmail.com', 'Zorella Travel and Tours'); // Sender Email and name
    $mail->addAddress('gremoryyxx@gmail.com');     //Add a recipient email
    $mail->addReplyTo('gremoryyxx@gmail.com', 'Zorella Travel and Tours'); // reply to sender email

    //Content
    $mail->isHTML(true);               //Set email format to HTML
    $mail->Subject = "Quotation for ".$quotationRows['title'];   // email subject headings
    $mail->MsgHTML($body);
    // $mail->Body    = $_POST["message"]; //email message

    // Success sent message alert
    $mail->send();
    if($mail->isError()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo
        "
           <script> 
             document.location.href = 'view-quotation.php?adminid=$adminid&qoute=$id';
            </script>
            ";
    };

}
