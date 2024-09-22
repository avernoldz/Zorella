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
function uploadFile($file, $target_dir, $adminid)
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
        header("Location: ../tour-package.php?adminid=$adminid");
        exit();
    }

    // Check file size (e.g., limit to 5MB)
    if ($file["size"] > 5000000) {
        $uploadOk = 0;
        $_SESSION['upload_error'] = 'Sorry, uploaded file is too large';
        header("Location: ../tour-package.php?adminid=$adminid");
        exit();
    }

    // Allow certain file formats
    if (!in_array($imageFileType, $allowedTypes)) {
        $uploadOk = 0;
        $_SESSION['upload_error'] = 'Sorry, only JPG, JPEG, & PNG files are allowed.';
        header("Location: ../tour-package.php?adminid=$adminid");
        exit();
    }

    // Check if upload is okay
    if ($uploadOk === 0) {
        $_SESSION['upload_error'] = 'Sorry, your file was not uploaded.';
        header("Location: ../tour-package.php?adminid=$adminid");
        exit();
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return basename($file["name"]); // Return just the filename
        } else {
            $_SESSION['upload_error'] = 'Sorry, there was an error uploading your file.';
            header("Location: ../tour-package.php?adminid=$adminid");
            exit();
        }
    }
}

if (isset($_POST['tour'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $datesArray = $_POST['dates'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $adminid = mysqli_real_escape_string($conn, $_POST['adminid']);
    $itinerary = $_POST['itinerary'];
    $img = $_FILES['img'];

    // Access the files for each index
    $imgFilename = uploadFile($img, $target_dir, $adminid);
    $datesJson = json_encode($datesArray);

    $insert = "INSERT INTO `tourpackage`( `title`, `price`, `duration`, `description`, `img`, `dates`, `itinerary`, `type`, `adminid`)
        VALUES ('$title', '$price', '$duration','$description','$imgFilename', '$datesJson', '$itinerary', '$type', '$adminid')";

    if (mysqli_query($conn, $insert)) {
        header("Location: ../tour-package.php?adminid=$adminid");
    } else {
        echo mysqli_error($conn);
    }

    exit();
}
