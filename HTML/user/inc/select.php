<?php
$allResults = []; // Initialize an array to hold all results

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
            pt.confirmation_pdf,
            pt.payment_id,
            p.paymentid,
            pt.paymentinfoid,
            
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
            pt.payment_id,
            p.paymentid,
            pt.paymentinfoid,
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
