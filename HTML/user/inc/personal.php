<?php
session_start();
include '../../../inc/Include.php';

$target_dir = '../uploads/';
$userid = $_SESSION['userid'];
$ticketid = 0;

// Ensure the upload directory exists
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}


// Function to upload files
function uploadFile($file, $target_dir, $userid, $ticketid)
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
        header("Location: ../personal-info.php?userid=$userid&ticketid=$ticketid");
        exit();
    }

    // // Check if file already exists
    // if (file_exists($target_file)) {
    //     $uploadOk = 0;
    //     $_SESSION['upload_error'] = 'File already uploaded';
    //     header("Location: ../personal-info.php?userid=$userid&ticketid=$ticketid");
    //     exit();
    // }

    // Check file size (e.g., limit to 5MB)
    if ($file["size"] > 5000000) {
        $uploadOk = 0;
        $_SESSION['upload_error'] = 'Sorry, uploaded file is too large';
        header("Location: ../personal-info.php?userid=$userid&ticketid=$ticketid");
        exit();
    }

    // Allow certain file formats
    if (!in_array($imageFileType, $allowedTypes)) {
        $uploadOk = 0;
        $_SESSION['upload_error'] = 'Sorry, only JPG, JPEG, & PNG files are allowed.';
        header("Location: ../personal-info.php?userid=$userid&ticketid=$ticketid");
        exit();
    }

    // Check if upload is okay
    if ($uploadOk === 0) {
        $_SESSION['upload_error'] = 'Sorry, your file was not uploaded.';
        header("Location: ../personal-info.php?userid=$userid&ticketid=$ticketid");
        exit();
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return basename($file["name"]); // Return just the filename
        } else {
            $_SESSION['upload_error'] = 'Sorry, there was an error uploading your file.';
            header("Location: ../personal-info.php?userid=$userid&ticketid=$ticketid");
            exit();
        }
    }
}

if (isset($_POST['personal'])) {
    $numbers = $_POST['number'];
    $emails = $_POST['email'];
    $lastnames = $_POST['lastname'];
    $firstnames = $_POST['firstname'];
    $middlenames = $_POST['middlename'];
    $ages = $_POST['age'];
    $bdates = $_POST['bdate'];
    $sexs = $_POST['sex'];
    $civils = $_POST['civil'];
    $addresss = $_POST['address'];
    $passports = $_FILES['passport'];
    $valid_ids = $_FILES['valid-id'];
    $ticketid = $_POST['ticketid'];

    $booking_type = $_SESSION['booking_type'];


    for ($i = 0; $i < count($firstnames); $i++) {
        $number = $numbers[$i];
        $email = $emails[$i];
        $lastname = $lastnames[$i];
        $firstname = $firstnames[$i];
        $middlename = $middlenames[$i];
        $bdate = $bdates[$i];
        $age = $ages[$i];
        $sex = $sexs[$i];
        $address = $addresss[$i];
        $civil = $civils[$i];

        // Access the files for each index
        $passport = $passports['tmp_name'][$i];
        $valid_id = $valid_ids['tmp_name'][$i];

        // Make sure each file input is handled correctly
        if (isset($passport)) {
            $passportFilename = uploadFile([
                'tmp_name' => $passport,
                'name' => $passports['name'][$i],
                'type' => $passports['type'][$i],
                'size' => $passports['size'][$i],
                'error' => $passports['error'][$i],
            ], $target_dir, $userid, $ticketid);
        } else {
            $passportFilename = null; // Handle case where no file was uploaded
        }

        if (isset($valid_id)) {
            $validIdFilename = uploadFile([
                'tmp_name' => $valid_id,
                'name' => $valid_ids['name'][$i],
                'type' => $valid_ids['type'][$i],
                'size' => $valid_ids['size'][$i],
                'error' => $valid_ids['error'][$i],
            ], $target_dir, $userid, $ticketid);
        } else {
            $validIdFilename = null; // Handle case where no file was uploaded
        }

        $insert = "INSERT INTO `personalinfo`( `number`, `address`, `email`, `lastname`, `firstname`, `middlename`, `age`, `bdate`, `sex`, `civil`, `passport`, `validid`, `ticketid`, `booking_type`, `userid`)
        VALUES ('$number', '$address', '$email','$lastname','$firstname','$middlename','$age','$bdate','$sex','$civil','$passportFilename', '$validIdFilename','$ticketid', '$booking_type', '$userid')";

        if (mysqli_query($conn, $insert)) {
            $lastInsertedId = mysqli_insert_id($conn);
        } else {
            echo mysqli_error($conn);
        }
    }

    unset($_SESSION['total']);
    header("Location: ../terms.php?userid=$userid");
    exit();
}
