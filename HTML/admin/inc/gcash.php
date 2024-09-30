<?php
session_start();
include '../../../inc/Include.php';
require 'C:\xampp\htdocs\Zorella\vendor\autoload.php';
include 'C:\xampp\htdocs\Zorella\HTML\user\inc\select.php';

use Twilio\Rest\Client;

if (isset($_POST['update'])) {
    $price = intval(mysqli_real_escape_string($conn, $_POST['paid_amount']));
    $remaining_balance = intval(mysqli_real_escape_string($conn, $_POST['balance']));

    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $adminid = mysqli_real_escape_string($conn, $_POST['adminid']);
    $paymentinfoid = mysqli_real_escape_string($conn, $_POST['paymentinfoid']);

    $total = $remaining_balance - $price;
    $insert = "UPDATE paymentinfo SET remaining_balance = '$total', paid_date = CURRENT_TIMESTAMP WHERE paymentinfoid = '$paymentinfoid'";

    if ($type === 'Full') {
        if (mysqli_query($conn, $insert)) {
            header("Location: ../full-payment.php?adminid=$adminid&alert=1");
        } else {
            echo mysqli_error($conn);
        }
    } else {
        if (mysqli_query($conn, $insert)) {
            header("Location: ../installments.php?adminid=$adminid&alert=1");
        } else {
            echo mysqli_error($conn);
        }
    }

    exit();
}

$target_dir = '../gcash/';

// Ensure the upload directory exists
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Function to upload files
function uploadFile($file, $target_dir, $userid, $payment)
{
    $uploadOk = 1;
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png'];

    // Check if file is an image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        $uploadOk = 0;
        $_SESSION['upload_error'] = 'File is not an image.';
        header("Location: ../../../../user/gcash.php?userid=$userid&payment=$payment");
        exit();
    }

    // Check file size (e.g., limit to 5MB)
    if ($file["size"] > 5000000) {
        $uploadOk = 0;
        $_SESSION['upload_error'] = 'Sorry, uploaded file is too large';
        header("Location: ../../../../user/gcash.php?userid=$userid&payment=$payment");
        exit();
    }

    // Allow certain file formats
    if (!in_array($imageFileType, $allowedTypes)) {
        $uploadOk = 0;
        $_SESSION['upload_error'] = 'Sorry, only JPG, JPEG, & PNG files are allowed.';
        header("Location: ../../../../user/gcash.php?userid=$userid&payment=$payment");
        exit();
    }

    // Check if upload is okay
    if ($uploadOk === 0) {
        $_SESSION['upload_error'] = 'Sorry, your file was not uploaded.';
        header("Location: ../../../../user/gcash.php?userid=$userid&payment=$payment");
        exit();
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return basename($file["name"]); // Return just the filename
        } else {
            $_SESSION['upload_error'] = 'Sorry, there was an error uploading your file.';
            header("Location: ../../../../user/gcash.php?userid=$userid&payment=$payment");
            exit();
        }
    }
}

if (isset($_POST['gcash'])) {
    $userid = mysqli_real_escape_string($conn, $_POST['userid']);
    $payment = mysqli_real_escape_string($conn, $_POST['payment']);
    $infoid = mysqli_real_escape_string($conn, $_POST['infoid']);
    $img = $_FILES['img'];

    $phone = "SELECT phonenumber, firstname FROM user WHERE userid = '$userid'";
    $res = mysqli_query($conn, $phone);
    $rowP = mysqli_fetch_array($res);

    $phoneNumber = $rowP['phonenumber'];
    $name = $rowP['firstname'];

    $message = "Hi " . $name . ", thank you for your payment! Please wait for admin approval. We’ll update you as soon as possible. \n\nZorella Travel and Tours";

    // Format the phone number
    if (strpos($phoneNumber, '0') === 0) {
        $phoneNumber = '+63' . substr($phoneNumber, 1); // Replace the first '0' with '+69'
    }

    // Access the files for each index
    $imgFilename = uploadFile($img, $target_dir, $userid, $payment);
    $query = "INSERT INTO gcash(payment, paymentinfoid, img) VALUES ('$payment', '$infoid', '$imgFilename')";

    if (mysqli_query($conn, $query)) {

        sendSms($phoneNumber, $message);
        // header("Location: http://localhost/Zorella/HTML/user/my-booking.php?userid=$userid&alert=2");
    }
    exit();
}
