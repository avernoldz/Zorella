<?php
session_start();
include '../../../inc/Include.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['payment'])) {
        // Retrieve and escape POST data
        $payment_type = isset($_POST['payment_type']) ? mysqli_real_escape_string($conn, $_POST['payment_type']) : '';
        $payment_method = isset($_POST['payment_method']) ? mysqli_real_escape_string($conn, $_POST['payment_method']) : '';
        $booking_id = $_SESSION['booking_id'];
        $installment_type = isset($_POST['installment_type']) ? mysqli_real_escape_string($conn, $_POST['installment_type']) : '';
        $userid = $_SESSION['userid'];
        $booking_type = $_SESSION['booking_type'];

        $lastInsertedId = null;

        // Insert data into database
        $insert = "INSERT INTO `payment`(`payment_type`, `payment_method`, `installment_type`, `booking_type`, `booking_id`, `userid`) 
            VALUES ('$payment_type', '$payment_method', '$installment_type', '$booking_type', '$booking_id', '$userid')";

        if (mysqli_query($conn, $insert)) {
            unset($_SESSION['booking_type']);
            unset($_SESSION['booking_id']);

            $_SESSION['success'] = 'Your booking was successful. Thank you!';
            header("Location: ../dashboard.php?userid=$userid");
            exit();
        } else {
            echo "Error inserting into database: " . mysqli_error($conn);
        }
    } else {
        echo "No POST data or educational key not set.";
    }
} else {
    echo "Invalid request method.";
}
