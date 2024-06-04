<?php
session_start();
session_regenerate_id();

if(!$_SESSION['userid']) {
    header("Location:index.php?login-first");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../Connections/cdn.php"?>
    <?php include "../CSS/links.php"?>
    <title>Home</title>
    <style>
        .main .col.card .row {
            align-items: center;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_GET['userid'];
$active = "dashboard";
include "../Connections/Include.php";
include "side-bar.php";
include "header.php";
?>
    <div class="main">
        <div class="row">
            <div class="col head">
                <h1><i class="fa-solid fa-house fa-fw"></i>&nbsp;&nbsp;Dashboard</h1>
            </div>
        </div>
    </div>
</body>

</html>