<?php
session_start();
include '../../../inc/Include.php';

if (isset($_POST['tour-package'])) {
    // Retrieve and escape POST data
    $title = $_POST['title'];
    $date = $_POST['date'];
    $pax = isset($_POST['pax']) ? (int)$_POST['pax'] : 0;
    $packageid = isset($_POST['tourid']) ? mysqli_real_escape_string($conn, $_POST['tourid']) : '';
    $branch = isset($_POST['branch']) ? mysqli_real_escape_string($conn, $_POST['branch']) : '';
    $userid = $_SESSION['userid'];

    $status = 'Pending';
    $source = 'Tour Package';
    $lastInsertedId = null;
    $_SESSION['booking_type'] = $source;

    // Insert data into database
    $insert = "INSERT INTO `tourbooking`(`tourid`, `tour_title`, `pax`, `tour_date`, `userid`) 
            VALUES ('$packageid', '$title', '$pax', '$date', '$userid')";

    if (mysqli_query($conn, $insert)) {
        $lastInsertedId = mysqli_insert_id($conn);
        $_SESSION['booking_id'] = $lastInsertedId;
    } else {
        echo "Error inserting into database: " . mysqli_error($conn);
    }

    $insert3 = "INSERT INTO `booking` (`booking_type`, `booking_id`, `status`, `branch`, `userid` ) 
    VALUES ('$source','$lastInsertedId', '$status', '$branch', '$userid')";

    if (!mysqli_query($conn, $insert3)) {
        echo mysqli_error($conn);
    }

    $_SESSION['total'] = $pax;
    header("Location: ../personal-info.php?userid=$userid&ticketid=$lastInsertedId");
}
