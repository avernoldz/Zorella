<?php
$allResults = []; // Initialize an array to hold all results

// Function to fetch results
function fetchResults($result)
{
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Educational Query
$educationalQuery = "
    SELECT
        u.userid,
        u.firstname,
        u.lastname,
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
        pt.paymentinfoid
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
    WHERE
        u.userid = ?
    ORDER BY
        b.bookid DESC
";

$stmt = $conn->prepare($educationalQuery);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result(); // Get the result
$allResults = array_merge($allResults, fetchResults($result)); // Merge results

// Ticket Query
$ticketQuery = "
    SELECT
        u.userid,
        u.firstname,
        u.lastname,
        b.bookid,
        b.booking_id,
        b.booking_type,
        b.status AS stat,
        b.branch AS branchs,
        b.created_at AS date_created,
        tc.*,
        pt.confirmation_pdf,
        pt.payment_id,
        p.paymentid,
        pt.paymentinfoid
    FROM
        booking b
    INNER JOIN
        user u ON b.userid = u.userid
    INNER JOIN
        ticket tc ON b.booking_type = 'Customize' AND b.booking_id = tc.ticketid
    INNER JOIN
        payment p ON p.booking_id = b.booking_id AND p.booking_type = 'Customize'
    LEFT JOIN
        paymentinfo pt ON pt.payment_id = p.paymentid
    WHERE
        u.userid = ?
    ORDER BY
        b.bookid DESC
";

$stmt = $conn->prepare($ticketQuery);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result(); // Get the result
$allResults = array_merge($allResults, fetchResults($result)); // Merge results

// Tour Package Query
$tourPackageQuery = "
    SELECT
        u.userid,
        u.firstname,
        u.lastname,
        b.bookid,
        b.booking_id,
        b.booking_type,
        b.status AS stat,
        b.branch AS branchs,
        b.created_at AS date_created,
        tc.*,
        pt.confirmation_pdf,
        pt.payment_id,
        p.paymentid,
        pt.paymentinfoid,
        tp.title,
        tp.duration
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
        u.userid = ?
    ORDER BY
        b.bookid DESC
";

$stmt = $conn->prepare($tourPackageQuery);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result(); // Get the result
$allResults = array_merge($allResults, fetchResults($result)); // Merge results

// Close the connection

// Now $allResults contains combined results from all queries
