<?php
include '../../inc/Include.php';

$options = [
    'cost' => 12,
];

if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $subject1 = $_POST['subject1'];
    $subject2 = $_POST['subject2'];
    $adminid = $_POST['adminid'];

    $hash_pass = password_hash("$password", PASSWORD_BCRYPT, $options);

    $query = "INSERT INTO `faculty`(`name`, `email`, `password`, `subject`, `subject2`) 
    VALUES ('$name','$email','$hash_pass','$subject1','$subject2')";

    if (mysqli_query($conn, $query)) {
        echo "<script>window.location.href='../faculty.php?adminid=$adminid&alert=1';</script>";
    } else {
        echo mysqli_error($conn);
    }
}
