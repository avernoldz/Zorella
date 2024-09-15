<?php
session_start();
include '../../../inc/Include.php';

if (isset($_POST['lead-info'])) {
    // Retrieve and escape POST data
    $number = isset($_POST['number']) ? mysqli_real_escape_string($conn, $_POST['number']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $firstname = isset($_POST['firstname']) ? mysqli_real_escape_string($conn, $_POST['firstname']) : '';
    $lastname = isset($_POST['lastname']) ? mysqli_real_escape_string($conn, $_POST['lastname']) : '';
    $middlename = isset($_POST['middlename']) ? mysqli_real_escape_string($conn, $_POST['middlename']) : '';
    $position = isset($_POST['position']) ? mysqli_real_escape_string($conn, $_POST['position']) : '';
    $pickup = isset($_POST['pickup']) ? mysqli_real_escape_string($conn, $_POST['pickup']) : '';
    $userid = $_SESSION['userid'];
    $booking_id = $_SESSION['booking_id'];

    $lastInsertedId = null;

    // Insert data into database
    $insert = "INSERT INTO `leadpersoninfo`(`number`, `email`, `firstname`, `lastname`, `middlename`, `position`, `pickup`, `booking_id`, `userid`) 
            VALUES ('$number', '$email', '$firstname', '$lastname', '$middlename', '$position', '$pickup', '$booking_id', '$userid')";

    if (mysqli_query($conn, $insert)) {

        unset($_SESSION['total']);
        $lastInsertedId = mysqli_insert_id($conn);

        header("Location: ../terms.php?userid=$userid");
        exit();
    } else {
        echo "Error inserting into database: " . mysqli_error($conn);
    }
}
