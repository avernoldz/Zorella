<?php
function getBookings($conn, $branch, $status)
{
    $query = "
        SELECT
    u.userid,
    u.firstname,
    u.lastname,
    b.bookid,
    b.booking_id,
    b.booking_type,
    b.status,
    b.created_at,
    tb.*,
    eb.sdate,
    eb.pax AS ebpax,
    eb.hotel,
    tc.*
    FROM
        booking b
    INNER JOIN
        user u ON b.userid = u.userid
    LEFT JOIN
        educational eb ON b.booking_type = 'Educational' AND b.booking_id = eb.educationalid
    LEFT JOIN
        tourbooking tc ON b.booking_type = 'Tour Package' AND b.booking_id = tc.tour_bookid
    LEFT JOIN
        ticket tb ON b.booking_id = tb.ticketid
        AND (b.booking_type = 'Ticketed' OR
            b.booking_type = 'Customize')
    WHERE
        b.status = ? && b.branch = ? ORDER BY b.bookid DESC";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $status, $branch);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}

function getTicketedBookings($conn, $branch, $status)
{
    $query = "
        SELECT
            u.userid,
            u.firstname,
            u.lastname,
            b.bookid,
            b.booking_id,
            b.booking_type,
            b.status,
            b.created_at,
            tb.*,
            eb.sdate,
            eb.pax,
            eb.hotel
        FROM
            booking b
        INNER JOIN
            user u ON b.userid = u.userid
        LEFT JOIN
            educational eb ON b.booking_type = 'Educational' AND b.booking_id = eb.educationalid
        LEFT JOIN
            ticket tb ON b.booking_id = tb.ticketid
            AND (b.booking_type = 'Ticketed' OR
                b.booking_type = 'Customize')
        WHERE
            b.branch = ? 
            AND b.status <> ? 
            AND b.booking_type = 'Ticketed'
        ORDER BY b.bookid DESC
    ";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $branch, $status);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result;
}
