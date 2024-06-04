<?php
include '../../Connections/Include.php';

if(isset($_GET['save'])) {
    $subject = $_GET['subject'];

    $facultyid = $_GET['facultyid'];
    $studentid = $_GET['studentid'];

    foreach ($subject as $row) {
        $query = "INSERT INTO `studSubject`(`subject`, `facultyid`, `studentid`) 
        VALUES ('$row','$facultyid','$studentid')";

        $run = mysqli_query($conn, $query);
    }


    if($run) {
        echo "<script>window.location.href='../view-students-faculty.php?facultyid=$facultyid&studentid=$studentid&alert=1';</script>";
    }

}

if(isset($_GET['edit'])) {
    $grades = $_GET['grades'];

    $studsubjectid = $_GET['studSubject'];
    $facultyid = $_GET['facultyid'];
    $studentid = $_GET['studentid'];


    $query = "UPDATE `studsubject` SET `grades`='$grades' WHERE studSubjectid = '$studsubjectid'";

    if(mysqli_query($conn, $query)) {
        echo "<script>window.location.href='../view-students-faculty.php?facultyid=$facultyid&studentid=$studentid&alert=2';</script>";
    } else {
        echo mysqli_error($conn);
    }
    

}
