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

        .main .row.body .col {}

        .wd-100 {
            width: 100%;
        }
    </style>
</head>

<body>

    <?php
    $userid = $_GET['userid'];
$active = "Quotation";
include "../Connections/Include.php";
include "side-bar.php";

?>
    <div class="main">
        <form action="" style="width: 100%;" class="needs-validation" method="GET" novalidate>
            <?php include "header.php"; ?>
            <input type="hidden" value="<?php echo "$userid";?>"
                name="userid">

            <div class="row wrap" id="ticketed">
                <div class="col body">
                    <div class="row">
                        <div class="col-12 body" style="background-color: var(--main-300);color: white;">
                            <h4><i class="fa-solid fa-plane"></i> Request Quotation</h4>
                        </div>
                    </div>

                    <div class="row body">

                        <div class="row w-100" style="justify-content: flex-end;">
                            <div class="col-2">
                                <button type="button" style="float:right;" class="btn btn-success" data-toggle="modal"
                                    data-target="#request">
                                    <i class="fa-solid fa-add fa-fw"></i>Request
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h1>Requests</h1>
                            </div>
                        </div>

                        <div class="modal" id="request" tabindex="-1" role="dialog" aria-labelledby="requestTitle"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="request">Request Quotation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body mb-4 mt-4">
                                        <div class="row w-100">
                                            <div class="col-6">
                                                <label for="">Title</label><label for="" class="required">*</label>
                                                <input type="text" id="title"
                                                    placeholder="Trip to Hongkong from Philippines" name="title"
                                                    class="form-control" required>
                                                <div class="invalid-feedback">
                                                    Please select your place of origin
                                                </div>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col-4 mt-5">
                                                <label for="">Origin</label><label for="" class="required">*</label>
                                                <select name="origin" id="" class="form-control">
                                                    <option selected>Philippine</option>
                                                    <option>Hongkong</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select your place of origin
                                                </div>
                                            </div>
                                            <div class="col-4  mt-5">
                                                <label for="">Destination</label><label for=""
                                                    class="required">*</label>
                                                <select name="destination" id="" class="form-control">
                                                    <option selected>Boracay</option>
                                                    <option>Siargao</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select your destination
                                                </div>
                                            </div>
                                            <div class="col-4  mt-5">
                                                <label for="date">Date</label><label for="" class="required">*</label>
                                                <input type="date" id="date" placeholder="Departure" name="date"
                                                    class="form-control" required>
                                                <div class="invalid-feedback">
                                                    Please select a valid date
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-5 w-100">
                                            <div class="col-4">
                                                <label for="">Duration</label><label for="" class="required">*</label>
                                                <select name="days" id="" class="form-control">
                                                    <?php
                                            for($i=1; $i <= 100; $i++) {
                                                echo "
                                                <option>$i days</option>
                                                ";
                                            }
?>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select your trip duration
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <label for="pax">Pax</label><label for="" class="required">*</label>
                                                <input type="number" id="pax" placeholder="1" name="pax"
                                                    class="form-control" required>
                                                <div class="invalid-feedback">
                                                    Please input for how many pax
                                                </div>
                                            </div>

                                            <div class="col-4">
                                                <label for="">Email</label><label for="" class="required">*</label>
                                                <input type="email" id="email" placeholder="abcded@gmail.com"
                                                    name="email" class="form-control" required>
                                                <div class="invalid-feedback">
                                                    Please input a valid email address
                                                </div>
                                            </div>
                                            <input type="hidden"
                                                value="<?php echo "$userid";?>"
                                                name="userid">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php

    if(isset($_GET['submit'])) {
        $title = $_GET['title'];
        $origin = $_GET['origin'];
        $destination = $_GET['destination'];
        $date = $_GET['date'];
        $days = $_GET['days'];
        $pax = $_GET['pax'];
        $email = $_GET['email'];
        $userid = $_GET['userid'];

        $query = "INSERT INTO quotation(title, origin, destination, date, days, pax, email, userid) 
        VALUES ('$title','$origin', '$destination', '$date', '$days', '$pax', '$email', '$userid')";

        if(mysqli_query($conn, $query)) {
            echo "<script>window.location.href='quotation.php?userid=$userid&alert=1';</script>";
        } else {
            echo mysqli_error($conn);
        }
    }
?>

    <script>
        $(document).ready(function($) {
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                    var forms = document.getElementsByClassName('needs-validation');
                    // Loop over them and prevent submission
                    var validation = Array.prototype.filter.call(forms, function(
                        form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
        });

        const queryString = window.location.search;
        const urlParams = new URLSearchParams(window.location.search);
        const alert = urlParams.get('alert');

        if (alert == 1) {
            toastr["success"]("Request for quotation sent successfully")

            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        }
    </script>
</body>

</html>