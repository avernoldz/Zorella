<?php
require 'C:\xampp\htdocs\Zorella\vendor\autoload.php'; // Include the Composer autoload file
require 'C:\xampp\htdocs\Zorella\inc\Include.php'; // Include the Composer autoload file

use Twilio\Rest\Client;

processDueDates($conn);

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


function sendSms($to, $message)
{
    // Your Twilio credentials
    $accountSid = 'ACac806c6af3b3fca8e4b782b9faf55960';
    $authToken = '659516f2d12555ac47d684876cf48167';
    $twilioNumber = '+12548266857';

    // Create a new Twilio client
    $client = new Client($accountSid, $authToken);

    // Send the SMS
    try {
        $client->messages->create(
            $to, // The phone number you want to send to
            [
                'from' => $twilioNumber,
                'body' => $message
            ]
        );
        echo "Message sent successfully!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

function processDueDates($conn)
{
    $rows = checkDates($conn);
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
                        $phoneNumber = '+69' . substr($phoneNumber, 1); // Replace the first '0' with '+69'
                    }

                    // Send SMS to the user's phone number
                    sendSms($phoneNumber, $message);
                } else {
                    echo "NOT APPLICABLE " . $dueDateTime->format('F j, Y') . " PHONE NUMBER: $phoneNumber <br>";
                }
            }
        }
    }
}
