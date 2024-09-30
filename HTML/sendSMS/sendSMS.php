<?php
require 'C:\xampp\htdocs\Zorella\vendor\autoload.php'; // Include the Composer autoload file
require 'C:\xampp\htdocs\Zorella\inc\Include.php'; // Include the Composer autoload file
include 'C:\xampp\htdocs\Zorella\HTML\user\inc\select.php';

use Twilio\Rest\Client;

$allResults = array_merge(
    getEducationalResults($conn),
    getTicketResults($conn),
    getTourPackageResults($conn)
);

processDueDates($conn, $allResults);

function checkDates($conn)
{
    $query = "SELECT 
    p.*, 
    u.firstname, 
    u.phonenumber 
    FROM paymentinfo p 
    INNER JOIN user u ON p.userid = u.userid";

    $res = mysqli_query($conn, $query);

    $rows = [];
    while ($row = mysqli_fetch_array($res)) {
        $rows[] = $row; // Collect all rows
    }

    return $rows; // Return the array of rows
}

function processDueDates($conn, $allResults)
{
    $rows = $allResults;
    $currentDate = new DateTime();

    foreach ($rows as $row) {
        $dueDatesString = $row['due_date'];

        // Check if due_dates is "NOT APPLICABLE"
        if (trim($dueDatesString) === 'NOT APPLICABLE') {
            continue; // Skip this row if the due date is not applicable
        }

        $dueDatesArray = explode('. ', trim($dueDatesString)); // Split by ". "

        foreach ($dueDatesArray as $dueDateStr) {
            $dueDateTime = DateTime::createFromFormat('F j, Y', trim($dueDateStr)); // Create DateTime object

            $phoneNumber = $row['phonenumber'];
            if ($dueDateTime) {
                // Check if the due date is within the next 3 days
                $interval = $currentDate->diff($dueDateTime);
                if ($interval->days <= 3 && $interval->invert == 0) {
                    // Prepare the SMS message
                    $message = "This is a friendly reminder that your payment is due soon on " . $dueDateTime->format('F j, Y') . ". Please make your payment at your earliest convenience to avoid any interruptions.\n\nZorella Travel and Tours";

                    // Format the phone number
                    if (strpos($phoneNumber, '0') === 0) {
                        $phoneNumber = '+63' . substr($phoneNumber, 1); // Replace the first '0' with '+69'
                    }

                    // Send SMS to the user's phone number
                    sendSms($phoneNumber, $message);
                } else {
                    echo $dueDateTime->format('F j, Y') . " PHONE NUMBER: $phoneNumber <br>";
                    echo "<a href='http://localhost/Zorella/HTML/payment/stripe-checkout.php?payment_id=$row[paymentinfoid]&userid=$row[userid]&bookid=$row[bookid]&booking_type=$row[booking_type]&booking_id=$row[booking_id]'>LINK TO PAY</a> <br>";
                }
            }
        }
    }
}
