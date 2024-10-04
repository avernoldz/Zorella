<?php
$allResults = []; // Initialize an array to hold all results

require 'C:\xampp\htdocs\Zorella\vendor\autoload.php';

use Twilio\Rest\Client;

// Function to fetch results
function fetchResults($result)
{
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Function to get educational results
function getEducationalResults($conn, $userid = null)
{
    $educationalQuery = "
        SELECT
            u.userid,
            u.firstname,
            u.lastname,
            u.phonenumber,
            b.bookid,
            b.booking_id,
            b.booking_type,
            b.status AS stat,
            b.branch AS branchs,
            b.created_at AS date_created,
            eb.*,
            eb.pax AS eb_pax,
            eb.created_at AS date_eb,
            pt.downpayment,
            pt.confirmation_pdf,
            pt.payment_id,
            p.paymentid,
            pt.paymentinfoid,
            g.*,
            pt.due_date
        FROM
            booking b
        INNER JOIN
            user u ON b.userid = u.userid
        INNER JOIN
            educational eb ON b.booking_type = 'Educational' AND b.booking_id = eb.educationalid
        INNER JOIN
            payment p ON p.booking_id = b.booking_id AND p.booking_type = 'Educational'
        LEFT JOIN
            paymentinfo pt ON pt.payment_id = p.paymentid
        INNER JOIN 
            gcash g ON g.paymentinfoid = pt.paymentinfoid
    ";

    if ($userid !== null) {
        $educationalQuery .= " WHERE u.userid = ?";
    }

    $educationalQuery .= " ORDER BY b.bookid DESC"; // Add ORDER BY clause

    $stmt = $conn->prepare($educationalQuery);

    if ($userid !== null) {
        $stmt->bind_param("i", $userid);
    }

    $stmt->execute();
    return fetchResults($stmt->get_result());
}

// Function to get ticket results
function getTicketResults($conn, $userid = null)
{
    $ticketQuery = "
        SELECT
            u.userid,
            u.firstname,
            u.lastname,
            b.bookid,
            u.phonenumber,
            b.booking_id,
            b.booking_type,
            b.status AS stat,
            b.branch AS branchs,
            b.created_at AS date_created,
            tc.*,
            pt.confirmation_pdf,
            pt.downpayment,
            pt.downpayment,
            pt.payment_id,
            p.paymentid,
            pt.paymentinfoid,
            g.*,
            pt.due_date
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
        INNER JOIN 
            gcash g ON g.paymentinfoid = pt.paymentinfoid
    ";

    if ($userid !== null) {
        $ticketQuery .= " WHERE u.userid = ?";
    }

    $ticketQuery .= " ORDER BY b.bookid DESC"; // Add ORDER BY clause

    $stmt = $conn->prepare($ticketQuery);

    if ($userid !== null) {
        $stmt->bind_param("i", $userid);
    }

    $stmt->execute();
    return fetchResults($stmt->get_result());
}

// Function to get tour package results
function getTourPackageResults($conn, $userid = null)
{
    $tourPackageQuery = "
        SELECT
            u.userid,
            u.firstname,
            u.lastname,
            b.bookid,
            u.phonenumber,
            b.booking_id,
            b.booking_type,
            b.status AS stat,
            b.branch AS branchs,
            b.created_at AS date_created,
            tc.*,
            pt.confirmation_pdf,
            pt.payment_id,
            p.paymentid,
            pt.downpayment,
            pt.paymentinfoid,
            pt.due_date,
            g.*,
            tp.title
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
        INNER JOIN 
            gcash g ON g.paymentinfoid = pt.paymentinfoid
    ";

    if ($userid !== null) {
        $tourPackageQuery .= " WHERE u.userid = ?";
    }

    $tourPackageQuery .= " ORDER BY b.bookid DESC"; // Add ORDER BY clause

    $stmt = $conn->prepare($tourPackageQuery);

    if ($userid !== null) {
        $stmt->bind_param("i", $userid);
    }

    $stmt->execute();
    return fetchResults($stmt->get_result());
}

// Merge results

// Close the connection

// Now $allResults contains combined results from all queries

function sendSms($to, $message)
{
    // Your Twilio credentials
    $accountSid = 'ACac806c6af3b3fca8e4b782b9faf55960';
    $authToken = '98bb5254529fbec7be71451a9255391f';
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
