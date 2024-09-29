<?php
// Database connection
include '../../../inc/Include.php';

// Get the branch from POST data
if (isset($_POST["branch"])) {
    $branch = $_POST["branch"];
    $sql = "
    SELECT
        COUNT(CASE WHEN b.booking_type = 'Ticketed' AND b.status <> 'pending' THEN 1 END) AS ticket_count,
        COUNT(CASE WHEN b.booking_type = 'Customize' AND b.status <> 'pending' THEN 1 END) AS customize_count,
        COUNT(CASE WHEN b.booking_type = 'Educational' AND b.status <> 'pending' THEN 1 END) AS educ_count,
        COUNT(CASE WHEN b.booking_type = 'Tour Package' AND b.status <> 'pending' THEN 1 END) AS tour_count,
        COUNT(CASE WHEN b.status = 'pending' THEN 1 END) AS pending_count
    FROM
        booking b
    INNER JOIN
        user u ON b.userid = u.userid
    LEFT JOIN
        educational eb ON b.booking_type = 'Educational' AND b.booking_id = eb.educationalid
    LEFT JOIN
        ticket tb ON b.booking_id = tb.ticketid
        AND (b.booking_type = 'Ticketed' OR b.booking_type = 'Customize')
    WHERE
        b.branch = '$branch';
";

    // Execute the query
    $res = $conn->query($sql);

    // Check for errors
    if (!$res) {
        die("Query failed: " . $conn->error);
    }

    // Fetch the results
    $row = $res->fetch_assoc();

    $sql2 = "
    SELECT
        COUNT(CASE WHEN p.payment_type = 'Full Payment' THEN 1 END) AS full_count,
        COUNT(CASE WHEN p.payment_type = 'Installment' THEN 1 END) AS installment_count
    FROM
        payment p;";

    // Execute the query
    $res2 = $conn->query($sql2);

    // Check for errors
    if (!$res2) {
        die("Query failed: " . $conn->error);
    }

    // Fetch the results
    $row2 = $res2->fetch_assoc();


    // Output the results as JSON
    header('Content-Type: application/json');
    echo json_encode([
        'ticket_count' => $row['ticket_count'],
        'customize_count' => $row['customize_count'],
        'educ_count' => $row['educ_count'],
        'pending_count' => $row['pending_count'],
        'tour_count' => $row['tour_count'],
        'full_count' => $row2['full_count'],
        'installment_count' => $row2['installment_count']
    ]);

    // Close the connection
    $conn->close();
}
