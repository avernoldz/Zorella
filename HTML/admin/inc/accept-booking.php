<?php
session_start();
include '../../../inc/Include.php';

if (isset($_POST['accept-book'])) {
    $bookid = mysqli_real_escape_string($conn, $_POST['bookid']);
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.00;
    $adminid = mysqli_real_escape_string($conn, $_POST['adminid']);
    $userid = mysqli_real_escape_string($conn, $_POST['userid']);
    $status = 'Payment';

    $update = "UPDATE booking SET status = '$status' WHERE bookid = '$bookid'";

    $insert = "INSERT INTO `paymentinfo`( `bookid`, `price`, `userid`, `adminid`)
        VALUES ('$bookid', '$price', '$userid', '$adminid')";

    if (mysqli_query($conn, $insert)) {

        if (!mysqli_query($conn, $update)) {
            echo mysqli_error($conn);
        }
        header("Location: ../pending-bookings.php?adminid=$adminid");
        // echo "SUCCESS";
    } else {
        echo mysqli_error($conn);
    }

    exit();
}
